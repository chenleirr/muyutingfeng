<?php

namespace App\Repositories;

use Log;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Exception\InvalidArgumentException;
use App\Exceptions\CustomException;
use RuntimeException;

class ModelRepository
{

    /**
     * The Model instance.
     */
    protected $model;

    /**
     * Define the max lines can be returned by API.
     *
     * @var int
     */
    protected $maxQueryLines = 10000;

    /**
     * 让所有的Repository支持数组格式的查询参数
     * 注意：这个函数只支持以下格式的where：
     *
     *    ['status', '>', 200]                     ---> where('status', '>', 200)
     *    ['status', '<', 200]                     ---> where('status', '<', 200)
     *    ['status', '=', 200]                     ---> where('status', '=', 200)
     *    ['status', '<>', 200]                    ---> where('status', '<>', 200)
     *    ['type', 'in', ['normal', 'deleted']]    ---> whereIn('type', ['normal', 'deleted'])
     *    ['type', 'notIn', ['normal', 'deleted']] ---> whereNotIn('type', ['normal', 'deleted'])
     *    ['created_at', 'between', [$start, $end]] --->whereBetween('created_at', [$start, $end])
     *
     * 以上几种查询，不支持其他查询方式
     *
     * @param Array $where 查询参数数组
     */
    public function buildQuery($where)
    {
        $query = $this->model;

        if (!is_array($where)) {
            return $query;
        }

        foreach ($where as $item) {
            if (!is_array($item) || (count($item) !== 3)) {
                abort(500, "查询条件错误, 必须为三个元素的数组：" . print_r($item, true));
            }

            $key = $item[0];
            $option = $item[1];
            $value = $item[2];

            if ($option === 'notIn') {
                $query = $query->whereNotIn($key, $value);
            } elseif ($option === 'in') {
                $query = $query->whereIn($key, $value);
            } elseif ($option === 'between') {
                $query = $query->whereBetween($key, $value);
            } else {
                $query = $query->where($key, $option, $value);
            }
        }

        return $query;
    }

    /**
     * 获取单个记录
     */
    public function getOne($where)
    {
        $query = $this->buildQuery($where);

        Log::info('执行SQL:' . $query->toSql());

        return $query->first();
    }

    /**
     * 获取多个记录
     */
    public function get($where)
    {
        $query = $this->buildQuery($where);

        Log::info('执行SQL:' . $query->toSql());

        return $query->get();
    }

    /**
     * 更新当前model的记录
     */
    public function update($where, $updateDate)
    {
        $query = $this->buildQuery($where);

        Log::info('执行SQL:' . $query->toSql());
        Log::info('参数:');
        Log::info($updateDate);

        return $query->update($updateDate);
    }

    /**
     * 通用的获取列表返回结果的函数
     *
     * @param $query 组装好查询条件的query
     * @param int $page 页码
     * @param int $perPage 一页显示的条数
     * @param string $countKey 计数字段
     * @param array $sortBy 排序字段、排序方式
     * @param bool $noLimitPerPage
     * @return array [pagination=>分页信息, list=>当前页记录]
     */
    public function buildList(
        $query,
        $page = 1,
        $perPage = 1,
        $countKey = '',
        $sortBy = ['created_at' => '0'],
        $noLimitPerPage = false
    ) {
    

        $result = [
            'pagination' => [
                'total' => 0,
                'total_pages' => 0,
            ],
            'list' => []
        ];

        $perPage = (int)$perPage;
        $page = (int)$page;

        $maxLimit = $noLimitPerPage ? $this->maxQueryLines : 500;
        //因为一次获取数量过多会造成性能问题，所以这里做限制
        if ($perPage > $maxLimit) {
            $errorCode = config('custom_code.params_invalid.code');
            throw new CustomException(
                "Try to load too many records, exceeding the limit: $maxLimit.",
                $errorCode
            );
        }

        $result['pagination']['per_page'] = $perPage;
        $result['pagination']['page'] = $page;

        //Get total count number.
        if (empty($countKey)) {
            $result['pagination']['total'] = $query->count();
        } else {
            $result['pagination']['total'] = $query->count($countKey);
        }

        if (!$result['pagination']['total']) {
            return $result;
        }
        $result['pagination']['total_pages'] = ceil($result['pagination']['total']/$perPage);

        $skip = $page > 1 ? ($page - 1) * $perPage : 0;

        if (!empty($sortBy)) {
            foreach ($sortBy as $k => $v) {
                $query = $query->orderBy($k, ($v == 1) ? 'asc' : 'desc');
            }
        }
        $result['list'] = $query->take($perPage)->skip($skip)->get();
        return $result;
    }

    public function buildStat($query, $aggregationFields, $groupByParams, $sortBy)
    {
        $result = [
            'count' => 0,
            'list' => []
        ];

        if (empty($groupByParams)) {
            $count = 1;
        } else {
            //The following code is used to estimate count of query results to avoid get too many rows.
            //For NULL value, the mysql count(col) function will ignore, so we need to converted to an invalid value.
            $convertedParams = array_map(array($this, 'handleEmptyColumnForCount'), $groupByParams);
            $col = join(',', $convertedParams);
            $selectFields = "COUNT(DISTINCT $col) AS count";
            //Without clone, first() operation will pollute the $query.
            $countQuery = clone $query;
            $getCountResult = $countQuery->select(DB::raw($selectFields))->first();

            $count = object_get($getCountResult, 'count', 0);
        }

        if ($count > $this->maxQueryLines) {
            $errorCode = config('statuscode.DATABASE_ERROR');
            throw new RuntimeException(
                'Query too many lines: ' . $query->toSql(),
                $errorCode
            );
        }

        $result['total'] = $count;

        $query = $query->select(DB::raw($aggregationFields));
        if (!empty($groupByParams)) {
            $query = $query->groupBy($groupByParams);
        }

        if (is_array($sortBy) && count($sortBy)) {
            $query = $query->orderBy($sortBy['column'], $sortBy['direction']);
        }

        $result['list'] = $query->get();
        return $result;
    }

    /**
     * Used to test field value in a parameters and append "where" condition to input query object.
     *
     * @param $query
     * @param $params
     * @param $field
     * @param $option
     * @param $dbField
     * @return mixed
     */
    public function appendWhere($query, $params, $field, $option, $dbField = '')
    {
        if (empty($dbField)) {
            $dbField = $field;
        }

        if (isset($params[$field])) {
            $query = $query->where($dbField, $option, $params[$field]);
        }

        return $query;
    }

    /**
     * This function is used to generate sql to convert NULL col to an empty value for further aggregation.
     * @param $field
     * @param int $emptyValue
     * @return string
     */
    private function handleEmptyColumnForCount($field, $emptyValue = -1)
    {
        return 'IFNULL('. $field . ','. $emptyValue. ')';
    }


    /**
     * 分批查询数据（可用于取代laravel的chunk方法）
     * 要求：查询sql必须是按照$sortField字段升序排列的
     *      $sortField字段必须具有唯一性和非空性，并且在$callback回调函数中不会对$sortField字段做更新操作
     * 建议：$sortField字段最好为Number类型的字段
     * 跳出：可以使用return false来跳出此方法
     *
     * @param $query 查询语句
     * @param $count 一批处理数据条数
     * @param $sortField 用于排序的字段名字
     * @param $sortFieldAlias 用于排序的字段在查询结果中的名字
     * @param callable $callback 回调函数
     * @return bool
     */
    public function batch($query, $count, $sortField, $sortFieldAlias, callable $callback)
    {

        $results = $query->forPage(1, $count)->get();

        while (count($results) > 0) {
            if (call_user_func($callback, $results) === false) {
                break;
            }

            $sortFieldValue = object_get($results[count($results)-1], $sortFieldAlias);

            $query->where($sortField, '>', $sortFieldValue);

            $results = $query->forPage(1, $count)->get();
        }

        return true;
    }
}

<?php

namespace App\Libs;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use App\Exceptions\CustomException;
use GuzzleHttp\Promise;
use GuzzleHttp\Pool;

class RequestApiLibrary
{
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function get($url, array $params = [], $options = [])
    {
        $response = $this->request('GET', $url, $params, $options);
        $response = json_decode($response, true);
        if (array_get($response, 'code', 1)) {
            throw new CustomException('Request error: ' . array_get($response, 'msg', ''));
        }
        return array_get($response, 'info', []);
    }

    public function post($url, array $params = [], $options = [])
    {
        $response = $this->request('POST', $url, $params, $options);
        $response = json_decode($response, true);
        if (array_get($response, 'code', 1)) {
            throw new CustomException('Request error: ' . array_get($response, 'msg', ''));
        }
        return array_get($response, 'info', []);
    }

    public function postSmsCode($url, array $params = [], $options = [])
    {
        $response = $this->request('POST_FORM', $url, $params, $options);
        $response = json_decode($response, true);
        if (array_get($response, 'code', 1) != 200) {
            throw new CustomException('Request error: ' . array_get($response, 'msg', ''));
        }
        return $response;
    }

    public function put($url, array $params = [], $options = [])
    {
        $response =  $this->request('PUT', $url, $params, $options);
        $response = json_decode($response, true);
        if (array_get($response, 'code', 1)) {
            throw new CustomException('Request error: ' . array_get($response, 'msg', ''));
        }
        return array_get($response, 'info', []);
    }

    public function delete($url, array $params = [], $options = [])
    {
        $response =  $this->request('delete', $url, $params, $options);
        $response = json_decode($response, true);
        if (array_get($response, 'code', 1)) {
            throw new CustomException('Request error: ' . array_get($response, 'msg', ''));
        }
        return array_get($response, 'info', []);
    }

    protected function request($method, $url, array $params, array $options = [])
    {
        if (empty($url)) {
            throw new CustomException('Request error: url不能为空');
        }
        if (strtoupper($method) === 'GET') {
            $qs = 'query';
        } elseif (strtoupper($method) === 'POST_FORM') {
            $qs = 'form_params';
            $method = 'POST';
        } else {
            $qs = 'json';
        }
        $options = array_merge($options, [$qs => $params]);
        $options = $this->buildHeader($options);
        try {
            $res = $this->client->request($method, $url, $options);
            return $res->getBody()->getContents();//返回json字符串
        } catch (ConnectException $connectException) {
            return json_encode([
                'code' => 900,
                'msg' => $connectException->getMessage(),
                'error' => '请求连接出错'
            ]);
        } catch (RequestException $requestException) {
            $response = $requestException->getResponse();
            $body = $response->getBody();
            return json_encode([
                'code' => $requestException->getCode(),
                'msg' => $body->getContents(),
                'error' => $requestException->getMessage()
            ]);
        }
    }


    protected function buildHeader(array $options)
    {
        $options['headers']['Accept'] = 'application/json';
        $options['headers']['X-REQUEST-ID'] = isset($_SERVER['HTTP_X_REQUEST_ID']) ? $_SERVER['HTTP_X_REQUEST_ID'] : 0;
        return $options;
    }

    public function addAsync($method, $name, $url, array $params = [], array $options = [])
    {
        $method = strtolower($method);
        if (! in_array($method, ['get', 'post', 'put', 'delete'])) {
            throw new RequestInvalidArgumentException("请求的方便不支持 $method", 1003);
        }
        if (empty($url)) {
            throw new RequestInvalidArgumentException("请求的地址 url 不能为空", 1003);
        }
        $this->asyncTasks[$name] = [
            'method' => $method,
            'url' => $url,
            'params' => $params,
            'options' => $options
        ];
    }

    public function runAsync()
    {
        if (empty($this->asyncTasks)) {
            return [];
        }
        $promises = [];
        foreach ($this->asyncTasks as $name => $request) {
            $method = array_get($request, 'method');
            $url = array_get($request, 'url');
            $params = array_get($request, 'params');
            $options = array_get($request, 'options', []);
            if (strtoupper($method) === 'get') {
                $qs = 'query';
            } else {
                $qs = 'json';
            }
            $methodAsync = $method . 'Async';
            $params = array_merge($params, ['from' => get_yn_from()]);
            $options = array_merge($options, [$qs => $params]);
            $options = $this->buildHeader($options);
            $promises[$name] = $this->client->$methodAsync($url, $options);
        }

        $begin = microtime(true);
        $results = Promise\settle($promises)->wait();
        $elapsed = $this->getElapsed($begin);

        foreach ($results as $name => &$value) {
            $state = array_get($value, 'state');
            $detail = json_encode(array_get($this->asyncTasks, $name));
            if ($state == 'fulfilled') {
                $response = array_get($value, 'value');
                $this->log->info('async ' . $response->getStatusCode() . ' time:' . $elapsed . ' ' . $detail);
                $value = $response->getBody()->getContents();
            } else {
                $e = array_get($value, 'reason');
                if ($e instanceof ConnectException) {
                    $this->log->error('async time:' . $elapsed . ' ' . $detail . ' ' . $e->getMessage());
                    $value = json_encode([
                        'code' => 900,
                        'message' => $e->getMessage(),
                        'error' => '请求连接出错'
                    ]);

                } elseif ($e instanceof RequestException) {
                    $response = $e->getResponse();
                    $body = $response->getBody();
                    $this->log->error('async ' . $response->getStatusCode() . ' time:' . $elapsed . ' ' . $detail);
                    $value = json_encode([
                        'code' => $e->getCode(),
                        'message' => $body->getContents(),
                        'error' => $e->getMessage()
                    ]);
                } else {
                    $this->log->error('async time:' . $elapsed . ' ' . $detail . ' ' . $e->getMessage());
                    $value = json_encode([
                        'code' => ($e->getCode() != 0) ? $e->getCode() : 900,
                        'message' => $e->getMessage(),
                        'error' => get_class($e)
                    ]);
                }
            }
        }

        $this->asyncTasks = [];
        return $results;
    }
}
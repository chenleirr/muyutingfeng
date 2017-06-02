<?php
namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\RedirectResponse;
use Log;
use Smarty;
use App;

@error_reporting(E_ALL & ~E_NOTICE);

class DisplayTpl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $input = $request->all();
        $display = isset($input['display']) ? $input['display'] : '';
        $output = array();
        try {
            $result = $next($request);

            if ($result instanceof RedirectResponse) {
                return $result;
            }

            if (method_exists($result, 'getOriginalContent')) {
                $output = $result->getOriginalContent();
                // 返回模板

                if ($this->display($display)) {
                    return $output;
                } elseif (isset($output['tpl'])) {

                    try {
                        ob_start();
                        $this->renderTpl($output['tpl'], $output['data']);
                        $html = ob_get_clean();
                        return $html;
                    } catch (Exception $ex) {
                        ob_get_clean();
                        throw $ex;
                    }
                } else {
                    throw new Exception("无法获取页面内容");
                }
            } else {
                throw new Exception("无法获取页面内容");
            }
        } catch (Exception $ex) {
            if (!$this->display($display)) {
                Log::error($ex->getMessage());
                $this->renderTpl(
                    'error.tpl',
                    isset($output['data']) ? $output['data'] : null
                );
                return;
            }
            throw $ex;
        }
    }

    private function renderTpl($tpl, $data)
    {
        $base = base_path();
        $smarty = new Smarty();

        $smarty->template_dir = $base . '/smarty/template';
        $smarty->compile_dir = $base . '/smarty/template_c';
        $smarty->config_dir = $base . '/smarty/config';
        $smarty->cache_dir = $base . '/smarty/cache';
        $smarty->left_delimiter='{%';
        $smarty->right_delimiter='%}';
        $smarty->caching = false;
        $smarty->debugging = false;
        $smarty->assign('info', $data);
        $smarty->display($tpl);
    }

    private function display($display)
    {
        if ('json' == $display) {
            return true;
        }

        return false;
    }
}

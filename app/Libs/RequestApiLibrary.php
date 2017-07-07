<?php

namespace App\Libs;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use App\Exceptions\CustomException;

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
}
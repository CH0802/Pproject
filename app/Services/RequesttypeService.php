<?php

namespace App\Services;

use App\Exceptions\BusinessException;
use GuzzleHttp\Client;
use Log;

class RequesttypeService extends BaseService
{

    public $client;

    public function __construct()
    {
       $this->client = new Client();
    }

    public function sendPostRequest($url,$params)
    {
        try {
            $response = $this->client->request('POST', $url,[
                'form_params' => $params,
                'timeout' => 5
            ]);
            $code = $response->getStatusCode();
            $body = $response->getBody();
           
            return json_decode((string)$body,true);
           
        }catch (\GuzzleHttp\Exception\RequestException $e) {
            throw new BusinessException($e->getMessage(),$e->getCode());
        }
    }

    public  function  sendGetRequest($url)
    {

        if(empty($url))
        {
            throw new BusinessException('请填写请求参数及请求地址');
        }

        try {

            $response = $this->client->request('Get', $url);

            $body = $response->getBody();
            $remainingBytes  =  $body->getContents();

            Log::info(['url'=>$url,'return'=>$remainingBytes]);

            return json_decode((string)$remainingBytes,true);

        } catch (Exception $e) {
             throw new BusinessException($e->getMessage(),$e->getCode());
        }
    }

    public function SendPostRequestHead($url,$headers,$json)
    {

         try {
            $response = $this->client->request('POST',$url,[
                'headers' => $headers,
                'json' => $json,
                'timeout' => 50
            ]);

            $code = $response->getStatusCode();
            $body = $response->getBody();
            return ['StateCode'=>$code,'Data'=>json_decode((string)$body,true)];
           
        }catch (\GuzzleHttp\Exception\RequestException $e) {
            throw new BusinessException($e->getMessage(),$e->getCode());
        }
    }

    public function SendPostJsonRequest($url,$json)
    {

         try {
            $response = $this->client->request('POST',$url,[
                'json' => $json,
                'timeout' => 50
            ]);

            $code = $response->getStatusCode();
            $body = $response->getBody();
            return ['Code'=>$code,'massage'=>json_decode((string)$body,true)];
           
        }catch (\GuzzleHttp\Exception\RequestException $e) {
            Log::error($e->getMessage());
            return ['Code'=>$e->getCode(),'massage'=>json_decode((string)$e->getMessage(),true)];
        }
    }

    public function SendPutRequestHead($url,$headers,$json)
    {

         try {
            $response = $this->client->request('PUT',$url,[
                'headers' => $headers,
                'json' => $json,
                'timeout' => 50
            ]);

            $code = $response->getStatusCode();
            $body = $response->getBody();
            return ['StateCode'=>$code,'Data'=>json_decode((string)$body,true)];
           
        }catch (\GuzzleHttp\Exception\RequestException $e) {
            throw new BusinessException($e->getMessage(),$e->getCode());
        }
    }

    public  function  sendGetHeadersRequest($url,$headers)
    {

        if(empty($url))
        {
            throw new BusinessException('请填写请求参数及请求地址');
        }

        try {

            $response = $this->client->request('Get', $url,['headers'=>$headers]);

            $code = $response->getStatusCode();
            $body = $response->getBody();
            $remainingBytes  =  $body->getContents();

            Log::info(['url'=>$url,'return'=>$remainingBytes]);

            return ['code'=>$code,'FileText'=>$remainingBytes];

        } catch (Exception $e) {
             throw new BusinessException($e->getMessage(),$e->getCode());
        }
    }

    //适用于返回文件流请求
    public function SendPostJsonRequest_returnfileflow($url,$json)
    {

         try {
            $response = $this->client->request('POST',$url,[
                'json' => $json,
                'timeout' => 50
            ]);

            $code = $response->getStatusCode();
            $body = $response->getBody();
            $remainingBytes  =  $body->getContents();
            
            Log::info(['url'=>$url,'return'=>$remainingBytes]);

            return ['code'=>$code,'FileText'=>$remainingBytes];
           
        }catch (\GuzzleHttp\Exception\RequestException $e) {
            Log::error($e->getMessage());
            return ['Code'=>$e->getCode(),'massage'=>json_decode((string)$e->getMessage(),true)];
        }
    }

    //文件上传专用
    public function sendPostHeaderRequest($url,$headers,$params)
    {
        try {
            $response = $this->client->request('POST', $url,[
                'headers' => $headers,
                'multipart' => $params,
                'timeout' => 20
            ]);
            $code = $response->getStatusCode();
            $body = $response->getBody();
           
            return ['StateCode'=>$code,'Data'=>json_decode((string)$body,true)];
           
        }catch (\GuzzleHttp\Exception\RequestException $e) {
            throw new BusinessException($e->getMessage(),$e->getCode());
        }
    }
    
}


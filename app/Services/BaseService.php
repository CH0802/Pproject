<?php

namespace App\Services;

use App\Exceptions\BusinessException;
use GuzzleHttp\Client;
use Log,DB,Response;;

class BaseService
{
    protected static $service = [];
    const CIPHER_ALGO = 'AES-128-ECB';
    const ANCRYPTIONKEY = 'Shangceng.com.cn/!@#nicaiya';

    /**
     * Method  instance
     *
     * @static
     * @return static
     */
    public static function instance() {
        if (isset(self::$service[static::class])) {
            return self::$service[static::class];
        } else {
            $obj = new static();
            self::$service[static::class] = $obj;
            return $obj;
        }
    }
    
    public static function log($filename,$loginfo,$catalogue = 'Bi_log',$header = [],$httpMethod = '',$isJson = False)
    {        
        $url='https://'.$_SERVER['HTTP_HOST'].$_SERVER["REQUEST_URI"];
        //打开文件
        // $fd = fopen('D:/'.$catalogue.'/'.$filename,"a"); 
        $fd = fopen('/var/log/nginx/'.$catalogue.'/'.$filename,"a"); 
        //增加文件
        $str = "\n Time : [".date("Y-m-d h:i:s",time())."]\n Method : ".$httpMethod."\n IsJson : ".$isJson."\n Url : ".$url."\n header : ".json_encode($header)."\n params : ".$loginfo;
        //写入字符串
        fwrite($fd, $str."\n");
        //关闭文件
        fclose($fd);
    }
    

    public static function isnumerical($num)
    {

        $value = 0;
        if(!$num)
        {
            return $value;
        }

        $amt = number_format($num, 2, '.', '');

        return $amt;
    }


    public static function isformat($value)
    {
       $data =  !isset($value)? '' :($value<'2000-01-01 00:00:00' ? '' :substr($value,0,-4));
       return $data;
    }
    

    public static function HandleWhere($where)
    {

        $string = '';
        if($where)
        {
            foreach ($where as $key => $value) 
            {
                $string .= implode($value,' and ').' or ';
            }
        }

        return rtrim($string,' or ');
    }

    public function ReturnHttpCode($code)
    {
        http_response_code($code);
        exit();
    }

    protected function RenderErrorAgeServer($message, $errorCode = 400)
    {
        return Response::json(['state'=>false,'code' => $errorCode, 'message' => $message]);
    }
}


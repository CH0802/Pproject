<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Response;
use Auth,Log;
use App\Exceptions\BusinessException;
use App\Exceptions\PermissionException;
use App\Services\PermissionService;


class BaseController extends Controller
{
    
    protected $requestData;
    protected $uid;
    protected $user;
    protected $v;
    protected $agent;
    protected $size;
    protected $currentPage;
    protected $offset;
    
    public function __construct(Request $request)
    {
        $json = $request->getContent();
        Log::debug('url:'. $request->url(),['params' => $json]);
        $this->requestData = json_decode($json,true);
        
        $this->user = Auth::user();
        if($this->user){
            $this->userCode = $this->user->Account;
            $this->userName = $this->user->DisplayName;
        }
        $this->size = isset($this->requestData['pageSize']) &&  $this->requestData['pageSize'] ? $this->requestData['pageSize'] : '10';
        $this->currentPage = isset($this->requestData['currPage']) &&  $this->requestData['currPage'] ? $this->requestData['currPage'] : '1';
        if($this->currentPage <= 0){
            $this->currentPage = 1;
        }
        $this->offset = ($this->currentPage - 1) * $this->size;

        $WebUrl = $request->header('url', '');
        if(isset($this->user->Account))
        {
            $this->perission = PermissionService::instance()->rbac($this->user->Account,$WebUrl);
            if(!isset($this->perission['report'][0]['admin_url']))
            {
                throw new PermissionException('您已经被监控非法访问！');
            }
            
            $Path = substr($request->url(),strrpos($request->url(),'/')+1);
            $ALLPath = explode(',',$this->perission['report'][0]['admin_url']);
            if(!in_array($Path,$ALLPath))
            {
                throw new PermissionException('您已经被监控非法访问！');
            }
            
        }
    }

    protected function renderError($message, $errorCode = 400)
    {
        return Response::json(['error_code' => $errorCode, 'message' => $message, 'data' => '']);
    }

    protected function renderJson($data=[],$message='成功',$maxAge = 0)
    {
        
        if (is_bool($data)) {
            $data = ['success' => $data];
        }

        $ret = ['state'=>true,'code' => 20000, 'message' => $message, 'data' => $data];
        $response = Response::json($ret);
        if ($maxAge) {
            $response = $response->header('Cache-Control', 'public, max-age=' . $maxAge);
        }
       
        return $response;
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

    /**
     * 电子合同专用
     * @param  [type]  $data    [description]
     * @param  integer $maxAge  [description]
     * @return [type]           [description]
     */
    protected function renderElecJson($data=[],$maxAge = 0)
    {

        $message = 'SUCCESS';
        $code = 20000;
        $result = [];

        if(!$data)
        {
            $ret = ['state'=>true,'code' => 20000, 'message' => '暂无可用数据', 'data' => $data];
            $response = Response::json($ret);
            return $response;
        }

        if(isset($data['message']))
        {
            $message = $data['message'];
        }

        if(isset($data['code']))
        {
            $code = $data['code'];
        }

        if(isset($data['result']))
        {
            $result = $data['result'];
        }

         

        $ret = ['code' => $code, 'message' => $message, 'data' => $result];
        $response = Response::json($ret);
        if ($maxAge) {
            $response = $response->header('Cache-Control', 'public, max-age=' . $maxAge);
        }
       
        return $response;
    }

    protected function RenderErrorAge($message, $errorCode = 400)
    {
        return Response::json(['state'=>false,'code' => $errorCode, 'message' => $message]);
    }


    /**
     * 解密
     * [AesDerypt description]
     * @param [type] $encrypt $secret [description]
     */
    public function AesDerypt($encrypt, $secret){
        $result = openssl_decrypt(base64_decode($encrypt), 'AES-128-ECB', $secret, OPENSSL_RAW_DATA);
        return $result;
    }


    /**
     * 加密
     * [Ancryption description]
     * @param [type] $encrypt $secret [description]
     */
    public function Ancryption($encrypt, $secret){
        $result = openssl_encrypt(base64_encode($encrypt), 'AES-128-ECB', $secret, OPENSSL_RAW_DATA);
        return base64_encode($result);
    }


    /**
     * 获取当前时间戳
     * @return string
    */
    public function get_millistime(){
        $microtime = microtime();
        $comps = explode(' ', $microtime);
        return sprintf('%d%03d', $comps[1], $comps[0] * 1000);
    }

    /**
     * 获取全局uuid
     * @return string
    */
    public function get_guid(){
        mt_srand((double) microtime() * 10000);
        $charid = md5(uniqid(rand(), true));
        $hyphen = chr(45); 
        $uuid = substr($charid, 0, 8) . $hyphen
            . substr($charid, 8, 4) . $hyphen
            . substr($charid, 12, 4) . $hyphen
            . substr($charid, 16, 4) . $hyphen
            . substr($charid, 20, 12);
        return $uuid;
    }
    
    
}
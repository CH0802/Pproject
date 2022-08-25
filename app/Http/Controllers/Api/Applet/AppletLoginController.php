<?php 
namespace App\Http\Controllers\Api\Applet;
use Log;
use Illuminate\Http\Request;
use App\Exceptions\ExampleException;
use App\Services\Applet\WechatAppletService;
use App\Services\RequesttypeService;
use App\Http\Controllers\Api\BaseController;
use App\Exceptions\BusinessException;
use App\Services\BpmProjectInfo\PMProjectpriceService;
use App\Models\AppletLoginLog;
   
class AppletLoginController extends BaseController
{
 
	/**
	 * 打卡登录
	 */

	protected $wxLoginUrl = "https://api.weixin.qq.com/sns/jscode2session";//微信登陆地址

	/**
	 * 微信小程序登陆并记录登录信息
	 */
	public function WechatLogin(Request $request)
	{

		$iv = $userData = $encryptedData = $rawData = $uuid = $signature = $me_signature =$userProfile = $token = '';
		$returnData = $userinfos = [] ;

		if(!isset($request['UserCode']) || empty($request['UserCode']))
		{
              throw new ExampleException('参数错误:用户Code不能为空');
        }

        if(!isset($request['encryptedData']) || empty($request['encryptedData']))
		{
              throw new ExampleException('参数错误:encryptedData	信息不能为空');
        }

        if(!isset($request['iv']) || empty($request['iv']))
		{
              throw new ExampleException('参数错误:iv信息不能为空');
        }

        if(!isset($request['rawData']) || empty($request['rawData']))
		{
              throw new ExampleException('参数错误:rawData信息不能为空');
        }

        if(!isset($request['signature']) || empty($request['signature']))
		{
              throw new ExampleException('参数错误:signature信息不能为空');
        }

        //获取用户ID与key
        $userData = $this->GetUserID($request['UserCode']);

        if ($userData && !isset($userData['openid'])) 
        {
        	Log::error('获取微信code失败，失败信息如下：'.json_encode($userData));
        	throw new ExampleException('获取微信code失败:'.$userData['errmsg'].',错误码：'.$userData['errcode']);
        }

        $iv = isset($request['iv']) ? $request['iv'] : '';
        $encryptedData = isset($request['encryptedData']) ? $request['encryptedData'] : '';
        $rawData = isset($request['rawData']) ? $request['rawData'] : '';
        $signature = isset($request['signature']) ? $request['signature'] : '';
		$uuid = WechatAppletService::instance()->getGuids();

        //验证签名信息
        $me_signature = sha1($rawData.$userData['session_key']);

        //签名1为前端传递，2为个人生成
        Log::error("Sign1:".$signature.";Sign2:".$me_signature);

        if($me_signature != $signature)
        {
        	throw new ExampleException('验签失败:请检查原始数据且保证该数据为微信返回数据');
        }

        //获取用户信息
        $userProfile = WechatAppletService::instance()->decryptData(Config('AppletConfig.DK-AppID'),$userData['session_key'],$encryptedData,$iv);

        // Log::info(['code'=>json_encode($userProfile)]);dd($userProfile);

        $userinfos = array_merge($userProfile,$userData,['uuid'=>$uuid]);

        $token = self::Ancryption(json_encode($userData),$userData['session_key']);

        //记录登陆数据
         $result = $this->RecordLoginInfo(array_merge($userinfos,['token'=>$token]),'DK');

         if(isset($result['code']) && $result['code']==20000)
        {
	         $returnData = [
	         	'uId'=>$userData['openid'],
	         	'headPortraitUrl'=>$userProfile['avatarUrl'],
	         	'nickName'=>$userProfile['nickName'],
	         ];
     	}

        return $this->renderElecJson(['code'=>0,'result'=>$returnData]);

	}


	/**
	 * 获取用户ID与key
	 * 【$userCode】微信返回用户code
	*/
	public function GetUserID($userCode)
	{
        $UserInfo = [];

		$url = $this->wxLoginUrl.'?appid='.Config('AppletConfig.DK-AppID').'&secret='.Config('AppletConfig.DK-Secret').'&js_code='.$userCode.'&grant_type=authorization_code';
		$UserInfo = RequesttypeService::instance()->sendGetRequest($url);

		if(is_array($UserInfo))
		{
			return $UserInfo;
		}

		return json_decode($UserInfo);
		
	}


    /**
     * 记录登录信息
     */
    public function RecordLoginInfo($datas,$types)
    {
        $resule = [];

        try {

            switch ($types) 
            {
                case 'DK':
                    $resule = AppletLoginLog::create($datas);
                    break;
                
                default:
                    # code...
                    break;
            }

        } catch (Exception $e) {
            throw new ExampleException('登录失败');
        }

        return ['code'=>20000,'result'=>$resule]; 

    } 

}
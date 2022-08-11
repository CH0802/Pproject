<?php

namespace App\Services\Applet;
use App\Exceptions\BusinessException;
use App\Exceptions\WechatErrorCode;
use GuzzleHttp\Client;
use Log;
use App\Models\VBIPerforanceCompany;
use Illuminate\Support\Facades\DB;
use App\Services\BaseService;
use App\Services\RequesttypeService;

class WechatAppletService extends BaseService
{

	protected $WeChatTonkerUrl = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential';//微信token地址
	protected $WeChatQRCodeUrl = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit';//微信获取二维码地址

	/**
	 * 检验数据的真实性，并且获取解密后的明文.
	 * @param $encryptedData string 加密的用户数据
	 * @param $iv string 与用户数据一同返回的初始向量
	 * @param $data string 解密后的原文
     *
	 * @return int 成功0，失败返回对应的错误码
	 */
	public function decryptData($appid, $sessionKey, $encryptedData, $iv)
	{
		$datas = [];

		if (strlen($sessionKey) != 24) {
			 throw new WechatErrorCode(WechatErrorCode::$IllegalAesKey);
		}
		$aesKey=base64_decode($sessionKey);

        
		if (strlen($iv) != 24) {
			throw new WechatErrorCode(WechatErrorCode::$IllegalIv);
		}
		$aesIV=base64_decode($iv);

		$aesCipher=base64_decode($encryptedData);

		$result=openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);

		$dataObj=json_decode( $result );
		if( $dataObj  == NULL )
		{
			throw new WechatErrorCode(WechatErrorCode::$IllegalBuffer);
		}
		
		if( $dataObj->watermark->appid != $appid )
		{
			throw new WechatErrorCode(WechatErrorCode::$IllegalBuffer);
		}
		$datas = $result;

		return json_decode($datas,true);
	}


    /**
     * 获取uuid
     * @return string
    */
    public function getGuids(){
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
	
	/**
     * 获取微信AccessToken
     * @return string
    */
    public function GetAccessToken($AppId,$Secret)
    {
    	$tonker = '';

    	$Url = $this->WeChatTonkerUrl.'&appid='.$AppId.'&secret='.$Secret;

    	$tonker = RequesttypeService::instance()->sendGetRequest($Url);

    	return $tonker;

    }


    /**
     * 生成微信二维码
     *【$tokens】微信身份验证token
     *【$datas】 二维码参数
     * @return string
    */
    public function GenerateWxQRCodes($tokens,$datas)
    {
    	$QRCode = '';

    	$Url = $this->WeChatQRCodeUrl.'?access_token='.$tokens;

    	//获取微信二维码
    	$QRCode = RequesttypeService::instance()->SendPostJsonRequest_returnfileflow($Url,$datas);

    	return $QRCode;

    }
}
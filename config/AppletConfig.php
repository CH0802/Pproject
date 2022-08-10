<?php
return [
	"wxLoginUrl"=>"https://api.weixin.qq.com/sns/jscode2session",//登录地址
	"WeChatTonkerUrl"=>"https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential",//获取token地址
	"WeChatQRCodeUrl"=>"https://api.weixin.qq.com/wxa/getwxacodeunlimit",//微信获取二维码地址

    'DK-AppID' => env('DK_AppID',''),
    'DK-Secret' => env('DK_Secret',''),
    // 'DK_Version' => env('DK-EnvVersion',''),
	];
	
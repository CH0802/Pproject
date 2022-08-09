<?php 
namespace App\Http\Controllers\Api;
use Log;
use App\Exceptions\BusinessException;
use Illuminate\Http\Request;
use App\Services\DepartmentGamesService;
use Illuminate\Support\Facades\DB;
use EasyWeChat\OfficialAccount\Application;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Messages\Text;
use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;
use EasyWeChat\OpenPlatform\Guard;
class DemoController extends BaseController
{


    public function Testing()
    {

    	$config = [
		    'app_id' => 'wx3b8a2b6b5481192b',
		    'secret' => '516f407a02ee9f6315306428523d42c4',
		    // 'aes_key' => 'abcdefghijklmnopqrstuvwxyz0123456789ABCDEFG',
		    'token' => 'easywechat',
		];

        $app = new Application($config);
        $server = $app->getServer();

$app->getServer()->with(fn() => "您好！EasyWeChat！");

$response = $server->serve();
$message = $server->getRequestMessage();
dd($message);

//         $config = [
//     'app_id' => env('WX_APP_ID'),
//     'secret' => env('WX_SECRET'),
//     'response_type' => 'array',
// ];
// $app = Factory::officialAccount($config);
// $app->template_message->send([
//     'touser' => $user->openid,
//     'template_id' => env('WX_WARNING_PUSH_TEMPLATE_ID'),
//     'url' => $url,
//     'miniprogram' => [],
//     'data' => [
//         'first' => '设备报警推送',
//         'keyword1' => $var,
//         'keyword2' => [$var, '#F00'],
//         'keyword3' => $var,
//         'remark' => '点击此条消息可以查看详情'
//     ],
// ]);
      dd("Hello word");
    }


}
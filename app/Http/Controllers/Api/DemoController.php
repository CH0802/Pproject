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
      dd("Hello word");
    }


}
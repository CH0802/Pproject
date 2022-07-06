<?php 
namespace App\Http\Controllers\Api;
use Log;
use App\Exceptions\BusinessException;
use Illuminate\Http\Request;
use App\Services\DepartmentGamesService;
use Illuminate\Support\Facades\DB;
class DemoController extends BaseController
{


    public function Testing()
    {
      dd("Hello word");
    }


}
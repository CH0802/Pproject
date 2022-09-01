<?php
namespace App\Http\Middleware;

use Closure;
use DB,Log,App;
use Request;
use App\Services\RequesttypeService;
use App\Exceptions\AbnormalException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use App\Models\AppletUser;
class IdentityVerification
{
   
    /**
     * 接口认证
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        // OAuth
        $Account = $request->header('Authorization', '');
        $Auth = $request->header('Dk-Auth', ' CH');
        $datas = $request->header();
        $testUrl = "";

        if($Auth === ' CH')
        {

          if(empty($Account))
          {
              throw new AbnormalException("您未通过接口授权认证！！！");
          }

          if($Account){

            $IsUserExist = AppletUser::where('token',$Account)->select()->get()->toArray();

            if(empty($IsUserExist))
            {
                throw new AbnormalException('您未通过接口授权认证！！！');
            }
             
          }

          Log::debug('url:'. $request->url(),  ['headers' => $datas]);

        }

        return $next($request);
    }

}

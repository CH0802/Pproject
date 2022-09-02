<?php 

namespace App\Http\Controllers\Api\SelfdisciplineApplet;
use Log;
use Illuminate\Http\Request;
use App\Exceptions\ExampleException;
use App\Services\RequesttypeService;
use App\Http\Controllers\Api\BaseController;
use App\Exceptions\BusinessException;
use App\Models\UserTemplate;
use App\Models\TemplateMaster;
use App\Models\AppletUser;
   
/**
 * 我的管理--接口
 */
	
class MyAdministrationController extends BaseController
{

	/**
	 * 获取我的分类
	 */
	public function GetMyClassificationList()
	{
		
		$request = $this->requestData;
		$userclass = $TemplateMasters = $newTemplateList = $newTemplate = [];

		if(!isset($request['UserCode']) || empty($request['UserCode']))
		{
              throw new ExampleException('参数错误:用户Code不能为空');
        }

        //当前用户的分类
        $userclass = UserTemplate::where('uid',$request['UserCode'])->select()->get()->toArray();

        if($userclass)
        {

        	foreach ($userclass as $key => $value) 
        	{
        		$newTemplateList[$key]['id'] = $value['id'];
        		$newTemplateList[$key]['uid'] = $value['uid'];
        		$newTemplateList[$key]['tempnames'] = $value['tempname'];
        	}

        }

        //不存在先从系统模版取数据新增后在返回
        if(empty($userclass))
        {
        	$TemplateMasters = TemplateMaster::where('state',TemplateMaster::STATE_QY)->select()->get()->toArray();

        	if($TemplateMasters)
        	{
        		foreach ($TemplateMasters as $key => $value) 
        		{
        			$newTemplate['uid'] = $request['UserCode'];	
        			$newTemplate['tempname'] = $value['tempname'];

        			$newTemplateList[$key]['id'] = $value['id'];
        			$newTemplateList[$key]['uid'] = $request['UserCode'];
        			$newTemplateList[$key]['tempnames'] = $value['tempname'];

        			UserTemplate::create($newTemplate);
        		}
        		
        	}
        	
        }

        return $this->renderElecJson(['code'=>0,'result'=>$newTemplateList]);

	}



}


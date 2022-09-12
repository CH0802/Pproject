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
use App\Models\PlanTask;
   
/**
 * 我的管理--接口
 */
	
class TaskPlanController extends BaseController
{

	/**
	 * This is a function
	 * @Author   CH。
	 * @DateTime 2022-09-12
	 */
	public function CreatePlan()
	{
		$request = $this->requestData;

		if(!isset($request['uid']) || empty($request['uid']))
		{
              throw new ExampleException('参数错误:用户Code不能为空');
        }

        if(!isset($request['planitle']) || empty($request['planitle']))
		{
              throw new ExampleException('参数错误:计划标题不能为空');
        }

        if(!isset($request['plancycletype']) || empty($request['plancycletype']))
		{
              throw new ExampleException('参数错误:计划类型不能为空');
        }

        if(!isset($request['plancyclevalue']) || empty($request['plancyclevalue']))
		{
              throw new ExampleException('参数错误:计划值不能为空');
        }

        if(!isset($request['plannum']) || empty($request['plannum']))
		{
              throw new ExampleException('参数错误:打卡次数不能为空');
        }

        if(!isset($request['planclassify']) || empty($request['planclassify']))
		{
              throw new ExampleException('参数错误:所属分类不能为空');
        }

        if(!isset($request['planjournalstate']) )
		{
              throw new ExampleException('参数错误:是否开启日志选项不能为空');
        }

        try {

        	PlanTask::create($request);

        } catch (Exception $e) {
        	
        	Log::error('创建任务计划失败,失败原因:'.$e->getMessage());
        	throw new ExampleException('创建任务计划失败,失败原因:'.$e->getMessage());
        }

        return $this->renderElecJson(['code'=>0,'result'=>[]]);

	}

}
<?php

namespace App\Exceptions;

use Exception;

class AbnormalException extends Exception
{

    const DEAULT_ERROR_CODE = 50000;


    /**
     * 转换异常为 HTTP 响应
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response(['state'=>false,'code'=>self::DEAULT_ERROR_CODE,'message'=>$this->getMessage() ?: '发生异常啦']);
    }
}

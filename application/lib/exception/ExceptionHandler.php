<?php
/**
 *  Filename    ExceptionHandler.php
 *  Creator     frankie
 *  CreateTime  0:18
 */

namespace app\lib\exception;


use think\Exception;
use think\exception\Handle;
use think\Log;
use think\Request;
use think\Response;

class ExceptionHandler extends Handle
{
    private $code;
    private $errorCode;
    private $msg;
    # 返回客户端当前请求的URL路径

    /**
     * 复写父辈render方法, REMEMBER 修改 CONFIG 中的 exception_handler 变量
     * @param \Exception $e
     * @return Response|void
     */
    public function render(\Exception $e)
    {
        # 判断错误类别
        if($e instanceof BaseException){
            # 处理自定义类的一场
            $this->code     = $e->code;
            $this->errorCode= $e->errorCode;
            $this->msg      = $e->msg;
        }else{
            # 设置一个参数, 当本地调试时调用父辈Render方法,反之返回自定义JSON字符串
            # 使用tp默认的config函数, 调用config.php app_debug参数,
            # 当 app_debug=true 时,当本地调试时调用父辈Render方法
            # 当 app_debug=false 时,返回自定义JSON字符串
            # 另外一种获取方法  Config::get('app_debug')
            if(config('app_debug')){
                return parent::render($e);
            }else{
                $this->code     = 500;
                $this->errorCode= 999;
                $this->msg      = '服务器内部错误, 不想告诉你,~';
                # 记录服务器错误日志
                $this->recordErrorLog($e);
            }

        }
        # 获取请求参数
        $request    = Request::instance();

        $return = [
            'msg'           => $this->msg,
            'error_code'    => $this->errorCode,
            'request_url'   => $request->url(), # NOTICE: 这里不是成员变量, 是方法
        ];
        return json($return,$this->code);
    }

    /**
     * @param \Exception $e
     * @content 根据异常级别写入LOG日志
     */
    private function recordErrorLog(\Exception $e)
    {
        Log::init([
            # 初始化日志参数, File 记录文件类型, test 关闭日志记录
            'type'  => 'File',
            # 日志路径
            'path'  => LOG_PATH,
            # 记录日志级别
            'level' => ['error']
        ]);
        Log::record($e->getMessage(),'error');
    }
}
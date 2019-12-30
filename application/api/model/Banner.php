<?php
/**
 *  Filename    Banner.php
 *  Creator     frankie
 *  CreateTime  23:28
 */

namespace app\api\model;


use app\lib\exception\BannerMissException;

class Banner extends BaseModel
{
    # 设置隐藏字段
    protected $hidden = ['update_time','delete_time'];
    # 设置模型关联数据库表明
    protected $table = 'banner';
    # 外联banner_item
    public function items()
    {
        # 一对多
        # 三个参数: 关联模型的模型名, 关联模型的外键, 当前模型的主键
        # with('items') 调用
        return $this->hasMany('BannerItem','banner_id','id');
    }
    /**
     * @param $id
     * @return array
     */
    public static function getBannerByID($id)
    {
        /*try{
            1 / 0 ;
        }catch (Exception $ex){
            throw $ex;
        }
        return "this is banner info from banner model!";*/
        # 原生SQL查询语句
        # $result = Db::query('SELECT * FROM banner_item where banner_id = ?',[$id]);
        # 构造器查询
        # $result = DB::table('banner_item')->where('banner_id','=',$id)->select();
        # 闭包函数使用
        # $result = DB::table('banner_item')
                # 如果想获取当前闭包函数的SQL语句 添加 ->fetchSql()
                # ->fetchSql()
                # ->where(function($query) use ($id){
                    # 内部无法使用闭包函数外的变量, 由函数中使用use 引入
                    # $query->where('banner_id','=',$id);
                #  })
                #  ->select();
        $banner = Banner::with(['items','items.img'])->find($id);
        if(!$banner){
            # 下面是全局异常处理. 如果想使用全局异常处理, 记得添加  use think\Exception;
            # throw new Exception("服务器内部错误!"); # 测试服务器内部错误 记录日志使用
            throw new BannerMissException(); # 使用自定义的Exception抛出$banner false的错误
        }
        return $banner;
    }
}
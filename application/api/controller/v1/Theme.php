<?php

namespace app\api\controller\v1;


use app\api\validate\IDCollection;
use app\api\model\Theme as ThemeModel;
use app\api\validate\IDMustBePostiveInt;
use app\lib\exception\ThemeException;

class Theme
{
    /**
     * @param $ids string
     * @url /theme?ids=id1,id2,id3,id4...
     * @return json 一组theme模型
     */
    public function getSimpleList($ids = '')
    {
        (new IDCollection())->goCheck();
        # $ids = explode(',',$ids);
        $result = ThemeModel::with('headImg,topicImg')->select($ids);
        if(!$result){
            throw new ThemeException();
        }
        return $result;
    }
    /**
     * @param $id int
     * @url /theme/1
     * @return json theme详情
     */
    public function getComplexOne($id)
    {
        (new IDMustBePostiveInt())->goCheck();
        $result = ThemeModel::getThemeWithProduct($id);
        if(!$result){
            throw new ThemeException();
        }
        return $result;
    }
}

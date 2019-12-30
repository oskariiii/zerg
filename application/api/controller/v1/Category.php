<?php
/**
 *  Filename    Category.php
 *  Creator     frankie
 *  CreateTime  23:20
 */

namespace app\api\controller\v1;

use app\api\model\Category as CategoryModel;
use app\lib\exception\CategoryException;

class Category
{
    public function getAllCategories()
    {
        # all 方法查询全部的时候, 给空数组就好
        $categories = CategoryModel::all([],'img');
        if($categories->isEmpty()){
            throw new CategoryException();
        }
        return $categories;
    }
}
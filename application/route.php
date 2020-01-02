<?php

use think\Route;

# Route::get('api/v1/banner/:id','api/v1.Banner/getBanner');
Route::get('api/:version/banner/:id','api/:version.Banner/getBanner');
# 获取theme列表简要信息
Route::get('api/:version/theme','api/:version.Theme/getSimpleList');
# 获取theme详情信息  #todo 下面添加的路由与上面重复,config.php 修改'路由使用完整匹配' route_complete_match=>true
Route::get('api/:version/theme/:id','api/:version.Theme/getComplexOne');
# 获取最新商品
Route::get('api/:version/product/recent','api/:version.Product/getRecent');
Route::get('api/:version/product/by_category','api/:version.Product/getAllInCategory');
# 这里第四个参数是为了预防上面recent接口在下面时,会误认为传入'recent'导致无法走通
Route::get('api/:version/product/:id','api/:version.Product/getOne',[],['id'=>'\d+']);
# 获取所有标签
Route::get('api/:version/category/all','api/:version.Category/getAllCategories');

# 获取用户token令牌
Route::post('api/:version/token/user','api/:version.Token/getToken');
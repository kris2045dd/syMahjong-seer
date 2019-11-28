<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
header('Content-type:text/html;charset=UTF-8');
// 指定允许其他域名访问
header('Access-Control-Allow-Origin:*');
// 响应类型
header('Access-Control-Allow-Methods:POST');
// 响应头设置
header('Access-Control-Allow-Headers:x-requested-with,content-type');
class TestController extends Controller {
    public function index(){
        $a = M("user")->select();echo "<pre>";var_dump($a);
    }

}
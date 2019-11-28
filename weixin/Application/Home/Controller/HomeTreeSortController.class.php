<?php
// 本类由系统自动生成，仅供测试用途
//提现记录还没完成,马上完成,以及页面底部的菜单栏
namespace Home\Controller;
use Think\Controller;
header('Content-type:text/html;charset=UTF-8');
// 指定允许其他域名访问
header('Access-Control-Allow-Origin:*');
// 响应类型
header('Access-Control-Allow-Methods:POST');
// 响应头设置
header('Access-Control-Allow-Headers:x-requested-with,content-type');
//首页排序
class HomeTreeSortController extends Controller {
    //json
    function json_encode($array)
    {
        if(version_compare(PHP_VERSION,'5.4.0','<')){
            $str = json_encode($array);
            $str = preg_replace_callback("#\\\u([0-9a-f]{4})#i",function($matchs){
                return iconv('UCS-2BE', 'UTF-8', pack('H4', $matchs[1]));
            },$str);
            return $str;
        }else{
            return json_encode($array, JSON_UNESCAPED_UNICODE);
        }
    }
    public function index(){
        echo "index";
    }
    //排序
    public function order(){
        $order = $_POST["order"];//排序方式
        $phone = $_POST["phone"];//手机号码
        $uidinput = $_POST["uidinput"];//input框输入的玩家uid
        $series = $_POST["series"];//判断是一级还是二级分销商
        $where = "phone='$phone'";
        $row = M("user_list")->where($where)->select();
        $next_uids = $row[0]["next_uids"];
        $pan = str_replace("[","",$next_uids);
        $pan = str_replace("]","",$pan);
        if(empty($pan)){
            echo $this->json_encode("");
        }
        else {

        }
    }
    public function order1(){
        $order = $_POST["order"];//排序方式
        $phone = $_POST["phone"];//手机号码
        $uidinput = $_POST["uidinput"];//input框输入的玩家uid
        $series = $_POST["series"];//判断是一级还是二级分销商
        if($series==1){ //一级分销商详情列表
            $where = "uid like '%$uidinput%'";
            $orders = $order." desc";
            $row = M("user_list")->where($where)->order($orders)->select();
            $json = $this->json_encode($row);
            $json = str_replace("\\/","/",$json);
            echo $json;
        }
        else if($series==2){ //二级分销商详情列表
            $where = "uid like '%$uidinput%'";
            $orders = $order." desc";
            $row = M("user_list")->where($where)->order($orders)->select();
            $json = $this->json_encode($row);
            $json = str_replace("\\/","/",$json);
            echo $json;
        }

    }
}
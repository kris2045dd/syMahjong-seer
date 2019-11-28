<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
header("Content-type:text/html;charset=utf8");
// 指定允许其他域名访问
header('Access-Control-Allow-Origin:*');
// 响应类型
header('Access-Control-Allow-Methods:POST');
// 响应头设置
header('Access-Control-Allow-Headers:x-requested-with,content-type');
class PutForwardController extends Controller {
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
        $phone = I('post.phone');//手机号码
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $proxy_lv = $row[0]['proxy_lv'];
        if($proxy_lv==4 or $proxy_lv==5){ //可点击提现
            $array['status']=1;
        }
        else { //不可点击提现
            $array['status']=-1;
        }
        echo json_encode($array);
    }
}
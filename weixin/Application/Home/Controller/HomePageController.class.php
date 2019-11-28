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
class HomePageController extends Controller {
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
    //首页
    public function index(){
        echo "这是馆主后台的首页";echo "<br>";
        echo date("Y-m-d H:i:s");
    }

    //待审核
    public function beAudited(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        // 待审核 examine
        $rows = M("user_list")
                ->field("next_uids")
                ->where("uid='$uid' and (proxy_lv='3' or proxy_lv='4' or proxy_lv='5')")
                ->select();
        $uids = $rows[0]['next_uids'];
        $uids = substr($uids,0,-1);
        $uids = substr($uids,1);
        //uid in
        $rowuids = M("user_list")
                   ->where("uid in($uids) and examine=0")
                   ->count();
        if(empty($rows)){
            echo $this->json_encode("0");
        }
        else {
            echo $this->json_encode($rowuids);
        }
    }

}
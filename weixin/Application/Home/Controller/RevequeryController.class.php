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
class RevequeryController extends Controller {
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
    //收益查询
    //总收益查询
    public function index(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $rows = M("profit_log")->field("SUM(self_profit+pre_profit+pre_pre_profit) as sum,time")->where("uid='$uid'")->select();
        $time = $rows[0]['time'];
        if(empty($time)){
            echo $this->json_encode(0);
        }
        else{
            $total = $rows[0]['sum'];
            echo $this->json_encode($total);
        }
    }
    //本月收益查询
    public function thisMonth(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $now = date('Y-m',time());
        //得到系统目前的时间后只取日期年月日时间
        $start = $now."-1"." 00:00:00";
        $next = date("Y-m",strtotime("$now +1 month"));
        $end = $next."-1"." 00:00:00";
        $rows = M("profit_log")
                ->field("SUM(self_profit+pre_profit+pre_pre_profit) as sum,time")
                ->where("uid='$uid' and time>='$start' and time<'$end'")
                ->select();
        $time = $rows[0]['time'];
        if(empty($time)){
            echo $this->json_encode(0);
        }
        else{
            $total = $rows[0]['sum'];
            echo $this->json_encode($total);
        }
    }
    //上六个月的查询数据
    public function a1(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $now = date('Y-m',strtotime("-1 month"));
        //得到系统目前的时间后只取日期年月日时间
        $start = $now."-1"." 00:00:00";
        $next = date("Y-m",time());
        $end = $next."-1"." 00:00:00";
        $rows = M("profit_log")
            ->field("SUM(self_profit+pre_profit+pre_pre_profit) as sum,time")
            ->where("uid='$uid' and time>='$start' and time<'$end'")
            ->select();
        $time = $rows[0]['time'];
        if(empty($time)){
            //echo $this->json_encode(0);
            $result = $now.":0";
            echo $this->json_encode($result);
        }
        else{
            $total = $rows[0]['sum'];
            $result = $now.":".$total;
            echo $this->json_encode($result);
        }
    }
    public function a2(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $now = date('Y-m',strtotime("-2 month"));
        //得到系统目前的时间后只取日期年月日时间
        $start = $now."-1"." 00:00:00";
        $next = date("Y-m",strtotime("-1 month"));
        $end = $next."-1"." 00:00:00";
        $rows = M("profit_log")
            ->field("SUM(self_profit+pre_profit+pre_pre_profit) as sum,time")
            ->where("uid='$uid' and time>='$start' and time<'$end'")
            ->select();
        $time = $rows[0]['time'];
        if(empty($time)){
            //echo $this->json_encode(0);
            $result = $now.":0";
            echo $this->json_encode($result);
        }
        else{
            $total = $rows[0]['sum'];
            $result = $now.":".$total;
            echo $this->json_encode($result);
        }
    }
    public function a3(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $now = date('Y-m',strtotime("-3 month"));
        //得到系统目前的时间后只取日期年月日时间
        $start = $now."-1"." 00:00:00";
        $next = date("Y-m",strtotime("-2 month"));
        $end = $next."-1"." 00:00:00";
        $rows = M("profit_log")
            ->field("SUM(self_profit+pre_profit+pre_pre_profit) as sum,time")
            ->where("uid='$uid' and time>='$start' and time<'$end'")
            ->select();
        $time = $rows[0]['time'];
        if(empty($time)){
            //echo $this->json_encode(0);
            $result = $now.":0";
            echo $this->json_encode($result);
        }
        else{
            $total = $rows[0]['sum'];
            $result = $now.":".$total;
            echo $this->json_encode($result);
        }
    }
    public function a4(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $now = date('Y-m',strtotime("-4 month"));
        //得到系统目前的时间后只取日期年月日时间
        $start = $now."-1"." 00:00:00";
        $next = date("Y-m",strtotime("-3 month"));
        $end = $next."-1"." 00:00:00";
        $rows = M("profit_log")
            ->field("SUM(self_profit+pre_profit+pre_pre_profit) as sum,time")
            ->where("uid='$uid' and time>='$start' and time<'$end'")
            ->select();
        $time = $rows[0]['time'];
        if(empty($time)){
            //echo $this->json_encode(0);
            $result = $now.":0";
            echo $this->json_encode($result);
        }
        else{
            $total = $rows[0]['sum'];
            $result = $now.":".$total;
            echo $this->json_encode($result);
        }
    }
    public function a5(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $now = date('Y-m',strtotime("-5 month"));
        //得到系统目前的时间后只取日期年月日时间
        $start = $now."-1"." 00:00:00";
        $next = date("Y-m",strtotime("-4 month"));
        $end = $next."-1"." 00:00:00";
        $rows = M("profit_log")
            ->field("SUM(self_profit+pre_profit+pre_pre_profit) as sum,time")
            ->where("uid='$uid' and time>='$start' and time<'$end'")
            ->select();
        $time = $rows[0]['time'];
        if(empty($time)){
            $result = $now.":0";
            echo $this->json_encode($result);
        }
        else{
            $total = $rows[0]['sum'];
            $result = $now.":".$total;
            echo $this->json_encode($result);
        }
    }
    public function a6(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $now = date('Y-m',strtotime("-6 month"));
        //得到系统目前的时间后只取日期年月日时间
        $start = $now."-1"." 00:00:00";
        $next = date("Y-m",strtotime("-5 month"));
        $end = $next."-1"." 00:00:00";
        $rows = M("profit_log")
            ->field("SUM(self_profit+pre_profit+pre_pre_profit) as sum,time")
            ->where("uid='$uid' and time>='$start' and time<'$end'")
            ->select();
        $time = $rows[0]['time'];
        if(empty($time)){
            $result = $now.":0";
            echo $this->json_encode($result);
        }
        else{
            $total = $rows[0]['sum'];
            $result = $now.":".$total;
            echo $this->json_encode($result);
        }
    }

    //查看页面上input输入框输入的游戏ID是否正确
    public function isNull(){
        $phone = I('post.phone');
        $uid = I('post.uid');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $next_uids = $row[0]['next_uids'];
        $next_uids_arr = json_decode($next_uids);
        if(empty($next_uids_arr)){
            $array['status']=-1;//没有收益
        }
        else {
            //$array['status']=1;//有收益
            if(in_array($uid,$next_uids_arr)){ //输入的值包含
                $array['status']=1;//输入的值包含
            }
            else { //输入的值不包含
                $array['status']=0;//输入的值不包含
            }
        }
        echo json_encode($array);
    }
    //得到游戏ID
    public function getId(){
        $uid = I('post.uid');
        echo $this->json_encode($uid);
    }

}
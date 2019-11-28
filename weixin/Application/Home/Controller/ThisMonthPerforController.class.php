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
class ThisMonthPerforController extends Controller {
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
    //馆主后台的个人中心的本月业绩接口
    //本月业绩总和接口
    public function index(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $now = date('Y-m',time());
        //得到系统目前的时间后只取日期年月日时间
        $start = $now."-1"." 00:00:00";
        $next = date("Y-m",strtotime("$now +1 month"));
        $end = $next."-1"." 00:00:00";
        $rows = M('profit_log')
                ->where("uid='$uid' and time>='$start' and time<'$end'")
                ->field("sum(self_profit+pre_profit+pre_pre_profit)*0.01 as sum,time")
                ->select();//echo "<pre>";var_dump($rows);
        /*$profit = $rows[0]['sum'];
        echo $this->json_encode($profit);*/
        $time = $rows[0]['time'];
        if(empty($time)){
            echo $this->json_encode(0);
        }
        else {
            $profit = $rows[0]['sum'];
            echo $this->json_encode($profit);
        }

    }
    //查询馆主后台近七日的业绩
    //当天的业绩
    public function fewday(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $now = date("Y-m-d",time());
        $start = $now." 00:00:00";
        $next = date("Y-m-d",strtotime("+1 day"));
        $end = $next." 00:00:00";
        $rows = M('profit_log')
                ->where("uid='$uid' and time>='$start' and time<'$end'")
                ->field("sum(self_profit+pre_profit+pre_pre_profit)*0.01 as sum,time")
                ->select();
        //echo $this->json_encode($rows);
        $time = $rows[0]['time'];
        if(empty($time)){
            $json = "[{'sum':'0.00','time':'$start'}]";
            $a = array(array("sum"=>'0.00','time'=>$start));
            echo json_encode($a);
        }
        else {
            echo $this->json_encode($rows);
        }
    }
    //当天-1
    public function fewday1(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $now = date("Y-m-d",strtotime("-1 day"));
        $start = $now." 00:00:00";
        $next =date("Y-m-d");
        $end = $next." 00:00:00";
        $rows = M('profit_log')
            ->where("uid='$uid' and time>='$start' and time<'$end'")
            ->field("sum(self_profit+pre_profit+pre_pre_profit)*0.01 as sum,time")
            ->select();
        //echo $this->json_encode($rows);
        $time = $rows[0]['time'];
        if(empty($time)){
            $json = "[{'sum':'0.00','time':'$start'}]";
            $a = array(array("sum"=>'0.00','time'=>$start));
            echo json_encode($a);
        }
        else {
            echo $this->json_encode($rows);
        }
    }
    //当天-2
    public function fewday2(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $now = date("Y-m-d",strtotime("-2 day"));
        $start = $now." 00:00:00";
        $next =date("Y-m-d",strtotime("-1 day"));
        $end = $next." 00:00:00";
        $rows = M('profit_log')
            ->where("uid='$uid' and time>='$start' and time<'$end'")
            ->field("sum(self_profit+pre_profit+pre_pre_profit)*0.01 as sum,time")
            ->select();
        //echo $this->json_encode($rows);
        $time = $rows[0]['time'];
        if(empty($time)){
            $json = "[{'sum':'0.00','time':'$start'}]";
            $a = array(array("sum"=>'0.00','time'=>$start));
            echo json_encode($a);
        }
        else {
            echo $this->json_encode($rows);
        }
    }
    //当天-3
    public function fewday3(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $now = date("Y-m-d",strtotime("-3 day"));
        $start = $now." 00:00:00";
        $next =date("Y-m-d",strtotime("-2 day"));
        $end = $next." 00:00:00";
        $rows = M('profit_log')
            ->where("uid='$uid' and time>='$start' and time<'$end'")
            ->field("sum(self_profit+pre_profit+pre_pre_profit)*0.01 as sum,time")
            ->select();
        //echo $this->json_encode($rows);
        $time = $rows[0]['time'];
        if(empty($time)){
            $json = "[{'sum':'0.00','time':'$start'}]";
            $a = array(array("sum"=>'0.00','time'=>$start));
            echo json_encode($a);
        }
        else {
            echo $this->json_encode($rows);
        }
    }
    //当天-4
    public function fewday4(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $now = date("Y-m-d",strtotime("-4 day"));
        $start = $now." 00:00:00";
        $next =date("Y-m-d",strtotime("-3 day"));
        $end = $next." 00:00:00";
        $rows = M('profit_log')
            ->where("uid='$uid' and time>='$start' and time<'$end'")
            ->field("sum(self_profit+pre_profit+pre_pre_profit)*0.01 as sum,time")
            ->select();
        //echo $this->json_encode($rows);
        $time = $rows[0]['time'];
        if(empty($time)){
            $json = "[{'sum':'0.00','time':'$start'}]";
            $a = array(array("sum"=>'0.00','time'=>$start));
            echo json_encode($a);
        }
        else {
            echo $this->json_encode($rows);
        }
    }
    //当天-5
    public function fewday5(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $now = date("Y-m-d",strtotime("-5 day"));
        $start = $now." 00:00:00";
        $next =date("Y-m-d",strtotime("-4 day"));
        $end = $next." 00:00:00";
        $rows = M('profit_log')
            ->where("uid='$uid' and time>='$start' and time<'$end'")
            ->field("sum(self_profit+pre_profit+pre_pre_profit)*0.01 as sum,time")
            ->select();
        //echo $this->json_encode($rows);
        $time = $rows[0]['time'];
        if(empty($time)){
            $json = "[{'sum':'0.00','time':'$start'}]";
            $a = array(array("sum"=>'0.00','time'=>$start));
            echo json_encode($a);
        }
        else {
            echo $this->json_encode($rows);
        }
    }
    //当天-6
    public function fewday6(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $now = date("Y-m-d",strtotime("-6 day"));
        $start = $now." 00:00:00";
        $next =date("Y-m-d",strtotime("-5 day"));
        $end = $next." 00:00:00";
        $rows = M('profit_log')
            ->where("uid='$uid' and time>='$start' and time<'$end'")
            ->field("sum(self_profit+pre_profit+pre_pre_profit)*0.01 as sum,time")
            ->select();
        $time = $rows[0]['time'];
        if(empty($time)){
            $json = "[{'sum':'0.00','time':'$start'}]";
            $a = array(array("sum"=>'0.00','time'=>$start));
            echo json_encode($a);
        }
        else {
            echo $this->json_encode($rows);
        }
    }

    //上月业绩综合接口数据
    public function lastMonthPer(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $lastmonth = date("Y-m",strtotime("-1 month"));
        $thismonth = date("Y-m");
        $start = $lastmonth."-01 00:00:00";
        $end = $thismonth."-01 00:00:00";
        $rows = M('profit_log')
            ->where("uid='$uid' and time>='$start' and time<'$end'")
            ->field("sum(self_profit+pre_profit+pre_pre_profit)*0.01 as sum,time")
            ->select();
        /*$profit = $rows[0]['sum'];
        echo $this->json_encode($profit);*/
        $time = $rows[0]['time'];
        if(empty($time)){
            echo $this->json_encode(0);
        }
        else {
            $profit = $rows[0]['sum'];
            echo $this->json_encode($profit);
        }
    }

    //修改密码
    public function editPwd(){
        $phone = I('post.phone');
        $oldpwd = I('post.oldpwd');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $soldpwd = $row[0]['password'];
        if($oldpwd==$soldpwd){ //原密码输入正确
            $data['password'] = I('post.newpwd');
            $rows = M("user_list")->where("phone_number='13200231520'")->save($data);
            if($rows){
                $array['status']=1;//修改成功
            }
            else{
                $array['status']=-1;//修改失败
            }
        }
        else{ //原密码输入不正确
            $array['status']=-2;
        }
        echo json_encode($array);
        /*$data['password'] = I('post.newpwd');
        $row = M("user_list")->where("phone_number='13200231520'")->save($data);
        if($row){
            $array['status']=1;//修改成功
        }
        else{
            $array['status']=-1;//修改失败
        }
        echo json_encode($array);*/
    }

}
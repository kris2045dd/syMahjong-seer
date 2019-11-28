<?php
namespace Home\Controller;
use Think\Controller;
class HomeTreeComputerController extends Controller {
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
    //底部三个总数的查询
    //二级推广总人数
    public function pretotal(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $row = M("user_list")->where("uid='$uid'")->select();
        $next_uids = $row[0]['next_uids'];
        $arr = json_decode($next_uids);
        $count = count($arr);
        echo $this->json_encode($count);
    }
    //三级推广总人数
    public function prepretotal(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $row = M("user_list")->where("uid='$uid'")->select();
        $next_uids = $row[0]['next_uids'];
        $arr = json_decode($next_uids);
        $count = count($arr);
        $uids = "";
        for($i=0;$i<$count;$i++){
            $uids .= $arr[$i].",";
        }
        $uids = substr($uids,0,-1);
        $counts = M("user_list")
            ->where("pre_uid in($uids)")
            ->count();
        $json = $this->json_encode($counts);
        echo $json;
    }
    //当前总收益
    /*
     * 当前总收益应该为本身收益+二级推广收益+三级推广收益的总数和
     * */
    public function profittotal(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        //第一级
        $row1 = M("profit_log")
            ->field("sum(self_profit+pre_profit+pre_pre_profit) as sum")
            ->where("uid='$uid'")
            ->select();
        $profit1 = $row1[0]['sum'];
        $next_uids = $row[0]['next_uids'];
        $arr = json_decode($next_uids);
        $count = count($arr);
        $uids = "";
        for($i=0;$i<$count;$i++){
            $uids .= $arr[$i].",";
        }
        $uids = substr($uids,0,-1);
        //第二级
        $row2 = M("profit_log")
            ->field("sum(self_profit+pre_profit+pre_pre_profit) as sum")
            ->where("uid in($uids)")
            ->select();
        $profit2 = $row2[0]['sum'];
        //第三级
        $suid = M("user_list")
            ->where("pre_uid in($uids)")
            ->select();
        $suidcount = count($suid);
        $suids = "";
        for($i=0;$i<$suidcount;$i++){
            $suids .= $suid[$i]['uid'].",";
        }
        $suids = substr($suids,0,-1);
        $row3 = M("profit_log")
            ->field("sum(self_profit+pre_profit+pre_pre_profit) as sum")
            ->where("uid in($suids)")
            ->select();
        $profit3 = $row3[0]['sum'];
        $profit = $profit1+$profit2+$profit3;
        echo $this->json_encode($profit);
    }
    //个人返利情况
    public function index(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $rows = M("profit_log")
            ->where("uid='$uid'")
            ->field("uid,sum(self_profit+pre_profit+pre_pre_profit) as sum")
            ->select();
        $json = $this->json_encode($rows);
        echo $json;
    }
    //二级推广员返利情况
    public function preList(){
        $phone = I('post.phone');
        $uidlike = I('post.uid');
        //$uidlike = "10003";
        if(empty($uidlike)){ //echo "空";echo "<br>";
            $row = M("user_list")->where("phone_number='13200231520'")->select();
            $uid = $row[0]['uid'];
            //第一级
            $row1 = M("profit_log")
                ->field("sum(self_profit+pre_profit+pre_pre_profit) as sum")
                ->where("uid='$uid'")
                ->select();
            $profit1 = $row1[0]['sum'];
            $next_uids = $row[0]['next_uids'];
            $arr = json_decode($next_uids);
            $count = count($arr);
            $uids = "";
            for($i=0;$i<$count;$i++){
                $uids .= $arr[$i].",";
            }
            $uids = substr($uids,0,-1);//echo $uids;
            //第二级
            $row2 = M("profit_log")
                ->join("left join user_list on user_list.uid=profit_log.gen_uid")
                ->field("profit_log.gen_uid,sum(profit_log.self_profit+profit_log.pre_profit+profit_log.pre_pre_profit) as sum,user_list.*")
                ->where("profit_log.gen_uid in($uids)")
                ->group("profit_log.gen_uid")
                ->select();
            //echo "<pre>";var_dump($row2);
            echo $this->json_encode($row2);
        }
        else { //echo "不为空";echo "<br>";
            $row = M("user_list")->where("phone_number='13200231520'")->select();
            //echo "<pre>";var_dump($row);
            $uid = $row[0]['uid'];
            //第一级
            $row1 = M("profit_log")
                ->field("sum(self_profit+pre_profit+pre_pre_profit) as sum")
                ->where("uid='$uid'")
                ->select();
            $profit1 = $row1[0]['sum'];
            $next_uids = $row[0]['next_uids'];
            $arr = json_decode($next_uids);
            $count = count($arr);
            $uids = "";
            for($i=0;$i<$count;$i++){
                $uids .= $arr[$i].",";
            }
            $uids = substr($uids,0,-1);//echo $uids;
            //第二级
            $row2 = M("profit_log")
                ->join("left join user_list on user_list.uid=profit_log.gen_uid")
                ->field("profit_log.gen_uid,sum(profit_log.self_profit+profit_log.pre_profit+profit_log.pre_pre_profit) as sum,user_list.*")
                ->where("profit_log.gen_uid in($uids) and profit_log.gen_uid like '%$uidlike%'")
                ->group("profit_log.gen_uid")
                ->select();
            //echo "<pre>";var_dump($row2);
            echo $this->json_encode($row2);
        }
        /*$row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        //第一级
        $row1 = M("profit_log")
            ->field("sum(self_profit+pre_profit+pre_pre_profit) as sum")
            ->where("uid='$uid'")
            ->select();
        $profit1 = $row1[0]['sum'];
        $next_uids = $row[0]['next_uids'];
        $arr = json_decode($next_uids);
        $count = count($arr);
        $uids = "";
        for($i=0;$i<$count;$i++){
            $uids .= $arr[$i].",";
        }
        $uids = substr($uids,0,-1);//echo $uids;
        //第二级
        $row2 = M("profit_log")
            ->join("left join user_list on user_list.uid=profit_log.gen_uid")
            ->field("profit_log.gen_uid,sum(profit_log.self_profit+profit_log.pre_profit+profit_log.pre_pre_profit) as sum,user_list.*")
            ->where("profit_log.gen_uid in($uids)")
            ->group("profit_log.gen_uid")
            ->select();
        echo $this->json_encode($row2);*/
    }
    //三级推广员返利情况
    public function prepreList(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        //第一级
        $row1 = M("profit_log")
            ->field("sum(self_profit+pre_profit+pre_pre_profit) as sum")
            ->where("uid='$uid'")
            ->select();
        $profit1 = $row1[0]['sum'];
        $next_uids = $row[0]['next_uids'];
        $arr = json_decode($next_uids);
        $count = count($arr);
        $uids = "";
        for($i=0;$i<$count;$i++){
            $uids .= $arr[$i].",";
        }
        $uids = substr($uids,0,-1);
        //第二级
        $row2 = M("profit_log")
            ->field("sum(self_profit+pre_profit+pre_pre_profit) as sum")
            ->where("uid in($uids)")
            ->select();
        $profit2 = $row2[0]['sum'];
        //第三级
        $suid = M("user_list")
            ->where("pre_uid in($uids)")
            ->select();
        $suidcount = count($suid);
        $suids = "";
        for($i=0;$i<$suidcount;$i++){
            $suids .= $suid[$i]['uid'].",";
        }
        $suids = substr($suids,0,-1);//echo $suids;
        $row3 = M("profit_log")
            ->join("left join user_list on user_list.uid=profit_log.gen_uid")
            ->field("profit_log.gen_uid,sum(profit_log.self_profit+profit_log.pre_profit+profit_log.pre_pre_profit) as sum,user_list.*")
            ->where("profit_log.gen_uid in($suids)")
            ->group("profit_log.gen_uid")
            ->select();
        //echo "<pre>";var_dump($row3);
        $row4 = M("user_list")
            ->join("left join profit_log on user_list.uid=profit_log.gen_uid")
            ->field("profit_log.gen_uid,sum(profit_log.self_profit+profit_log.pre_profit+profit_log.pre_pre_profit) as sum,user_list.*")
            ->where("user_list.uid in($suids)")
            ->group("profit_log.gen_uid")
            ->select();
        //echo "";var_dump($row4);
        echo $this->json_encode($row4);
    }
    //自身累计的收益
    public function accumulativeIncome(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $row1 = M("user_list")->where("uid='$uid'")->select();
        $profit = $row1[0]['profit'];
        echo $this->json_encode($profit);
    }
    //是否隐藏掉提现按钮
    public function isshow(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $proxy_lv = $row[0]['proxy_lv'];
        if($proxy_lv==3 or $proxy_lv==4 or $proxy_lv==5){
            $array['status']=1;
        }
        else {
            $array['status']=-1;
        }
        echo json_encode($array);
    }
}
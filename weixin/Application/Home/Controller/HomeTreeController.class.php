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
class HomeTreeController extends Controller {
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
    //一级分销详情
    public function preList(){
        $phone = I('post.phone');
        $uidlike = I('post.uid');
        //$uidlike = "10003";
        if(empty($uidlike)){ //echo "空";echo "<br>";
            $row1 = M("user_list")->where("phone_number='13200231520'")->select();
            //$profit = $row1[0]["profit"];
            $next_uids = $row1[0]["next_uids"];
            $next_uids = str_replace("[","",$next_uids);
            $next_uids = str_replace("]","",$next_uids);
            if(empty($next_uids)){ //没有下级
                //echo "没有下家代理";
                echo $this->json_encode("");
            }
            else { //有下级
                //echo "有下家代理";
                $arr = explode(",",$next_uids);//echo "<pre>";var_dump($arr);
                $count = count($arr);
                $list = "";
                for($i=0;$i<$count;$i++){
                    $row2 = M("user_list")->where("uid=$arr[$i]")->select();
                    $profit = ($row2[0]["pre_profit"])/100;
                    $list .= '{"head_pic":"'.$row2[0]["head_pic"].'","wx_name":"'.$row2[0]["wx_name"].'","uid":"'.$row2[0]["uid"].'","profit":'.$profit.'}'.',';
                }
                $resultjson1 = substr($list,0,-1);
                $resultjson1 = "[".$resultjson1."]";//echo $resultjson1;
                $arrr = json_decode($resultjson1,true);//echo "<pre>";var_dump($arrr);
                $arrr1 = json_decode(json_encode($arrr),true);
                $json = $this->json_encode($arrr1);
                echo stripslashes($json);
            }
        }
        else { //echo "不为空";echo "<br>";
            $row1 = M("user_list")->where("phone_number='13200231520'")->select();
            //$profit = $row1[0]["profit"];
            $next_uids = $row1[0]["next_uids"];
            $next_uids = str_replace("[","",$next_uids);
            $next_uids = str_replace("]","",$next_uids);
            if(empty($next_uids)){ //没有下级
                //echo "没有下家代理";
                echo $this->json_encode($next_uids);
            }
            else { //echo "全不为空";
                $row2 = M("user_list")
                        ->where("uid in ($next_uids) and uid like '%$uidlike%'")
                        ->select();
                $count = count($row2);//echo "总数为:".$count;echo "<pre>";var_dump($row2);
                $list = "";
                for($i=0;$i<$count;$i++){
                    $chauid = $row2[$i]['uid'];//echo $chauid;echo "<br>";
                    $row3 = M("user_list")->where("uid=$chauid")->select();//echo "<pre>";var_dump($row3);
                    $profit = ($row3[0]["pre_profit"])/100;
                    $list .= '{"head_pic":"'.$row3[0]["head_pic"].'","wx_name":"'.$row3[0]["wx_name"].'","uid":"'.$row3[0]["uid"].'","profit":'.$profit.'}'.',';
                }
                $resultjson1 = substr($list,0,-1);
                $resultjson1 = "[".$resultjson1."]";
                $arrr = json_decode($resultjson1);
                $arrr1 = json_decode(json_encode($arrr),true);
                $json = $this->json_encode($arrr1);
                echo stripslashes($json);
            }
        }
    }
    //二级推广员返利情况
    public function prepreList(){
        $phone = I('post.phone');
        $uidlike = I('post.uid');
        //$uidlike = 100;
        if(empty($uidlike)){
            $row1 = M("user_list")->where("phone_number='13200231520'")->select();
            $next_uids = $row1[0]["next_uids"];
            $next_uids = str_replace("[","",$next_uids);
            $next_uids = str_replace("]","",$next_uids);
            if(empty($next_uids)){
                echo $this->json_encode("");
            }
            else { //有一级
                //echo "有一级";
                $arr = explode(",",$next_uids);//echo "<pre>";var_dump($arr);
                $count = count($arr);
                $next_uids1 = "";
                for($i=0;$i<$count;$i++){
                    $uid1 = $arr[$i];//echo $uid1;echo "<br>";
                    $row2 = M("user_list")->where("uid=$uid1")->select();
                    $next_uids1 .= $row2[0]["next_uids"].",";
                }
                $next_uids1 = substr($next_uids1,0,-1);
                $next_uids1 = str_replace(",[]","",$next_uids1);
                $next_uids1 = str_replace("[],","",$next_uids1);
                $next_uids1 = str_replace("],[",",",$next_uids1);
                $next_uids1 = str_replace("[","",$next_uids1);
                $next_uids1 = str_replace("]","",$next_uids1);
                //判断第二级
                if(empty($next_uids1)){
                    echo $this->json_encode("");
                }
                else {
                    $row3 = M("user_list")->where("uid in ($next_uids1)")->select();
                    $arr1 = explode(",",$next_uids1);
                    $count1 = count($arr1);
                    $list = "";
                    for($i=0;$i<$count1;$i++){
                        $row4 = M("user_list")->where("uid=$arr1[$i]")->select();
                        $pre_uid = $row4[0]["pre_uid"];
                        $row5 = M("user_list")->where("uid=$pre_uid")->select();
                        $profit = ($row4[0]["pre_pre_profit"])/100;
                        $list .= '{"head_pic":"'.$row4[0]["head_pic"].'","wx_name":"'.$row4[0]["wx_name"].'","uid":"'.$row4[0]["uid"].'","profit":'.$profit.'}'.',';
                    }
                    $resultjson1 = substr($list,0,-1);
                    $resultjson1 = "[".$resultjson1."]";
                    $arrr = json_decode($resultjson1,true);
                    $arrr1 = json_decode(json_encode($arrr),true);
                    $json = $this->json_encode($arrr1);
                    echo stripslashes($json);
                }
            }
        }
        else {
            $row1 = M("user_list")->where("phone_number='13200231520'")->select();
            $next_uids = $row1[0]["next_uids"];
            $next_uids = str_replace("[","",$next_uids);
            $next_uids = str_replace("]","",$next_uids);
            if(empty($next_uids)){
                echo $this->json_encode("");
            }
            else {
                $arr = explode(",",$next_uids);//echo "<pre>";var_dump($arr);
                $count = count($arr);
                $next_uids1 = "";
                for($i=0;$i<$count;$i++){
                    $uid1 = $arr[$i];//echo $uid1;echo "<br>";
                    $row2 = M("user_list")->where("uid=$uid1")->select();
                    $next_uids1 .= $row2[0]["next_uids"].",";
                }
                $next_uids1 = substr($next_uids1,0,-1);
                $next_uids1 = str_replace(",[]","",$next_uids1);
                $next_uids1 = str_replace("[],","",$next_uids1);
                $next_uids1 = str_replace("],[",",",$next_uids1);
                $next_uids1 = str_replace("[","",$next_uids1);
                $next_uids1 = str_replace("]","",$next_uids1);
                //判断第二级
                if(empty($next_uids1)){
                    echo $this->json_encode("");
                }
                else {
                    $row3 = M("user_list")->where("uid in ($next_uids1) and  uid like '%$uidlike%'")->select();
                    $arr1 = explode(",",$next_uids1);
                    $count1 = count($arr1);
                    $list = "";
                    for($i=0;$i<$count1;$i++){
                        $row4 = M("user_list")->where("uid=$arr1[$i]")->select();
                        $pre_uid = $row4[0]["pre_uid"];
                        $row5 = M("user_list")->where("uid=$pre_uid")->select();
                        $profit = ($row4[0]["pre_pre_profit"])/100;
                        $list .= '{"head_pic":"'.$row4[0]["head_pic"].'","wx_name":"'.$row4[0]["wx_name"].'","uid":"'.$row4[0]["uid"].'","profit":'.$profit.'}'.',';
                    }
                    $resultjson1 = substr($list,0,-1);
                    $resultjson1 = "[".$resultjson1."]";
                    $arrr = json_decode($resultjson1,true);
                    $arrr1 = json_decode(json_encode($arrr),true);
                    $json = $this->json_encode($arrr1);
                    echo stripslashes($json);
                }
            }
        }
    }
    //自己的收益
    public function selfYield(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='$phone'")->select();
        $profit = $row[0]["profit"];
        $profit = $profit/100;
        echo $this->json_encode($profit);
    }
    //自身累计的收益【改为总累计收益】
    public function accumulativeIncome(){
        $phone = I('post.phone');
        $row1 = M("user_list")->where("phone_number='13200231520'")->select();
        $profit = $row1[0]["profit"];
        $next_uids = $row1[0]["next_uids"];
        $next_uids = str_replace("[","",$next_uids);
        $next_uids = str_replace("]","",$next_uids);
        if(empty($next_uids)){
            $profit = $profit/100;
            echo $this->json_encode($profit);
        }
        else { //有一级
            //echo "有一级";
            $arr = explode(",",$next_uids);//echo "<pre>";var_dump($arr);
            $count = count($arr);
            $next_uids1 = "";
            $profit1 = "";
            for($i=0;$i<$count;$i++){
                $uid1 = $arr[$i];//echo $uid1;echo "<br>";
                $row2 = M("user_list")->where("uid=$uid1")->select();
                $profit1 += $row2[0]["pre_profit"];
                $next_uids1 .= $row2[0]["next_uids"].",";
            }
            $profit1 = $profit+$profit1;
            $next_uids1 = substr($next_uids1,0,-1);
            $next_uids1 = str_replace(",[]","",$next_uids1);
            $next_uids1 = str_replace("[],","",$next_uids1);
            $next_uids1 = str_replace("],[",",",$next_uids1);
            $next_uids1 = str_replace("[","",$next_uids1);
            $next_uids1 = str_replace("]","",$next_uids1);
            //判断第二级
            if(empty($next_uids1)){
                $profit1 = $profit1/100;
                echo $this->json_encode($profit1);
            }
            else {
                $row3 = M("user_list")->where("uid in ($next_uids1)")->select();
                $arr1 = explode(",",$next_uids1);
                $count1 = count($arr1);
                $profit2 = "";
                for($i=0;$i<$count1;$i++){
                    $row4 = M("user_list")->where("uid=$arr1[$i]")->select();
                    $profit2 += $row4[0]["pre_pre_profit"];
                }
                $profit2 = ($profit1+$profit2)/100;
                echo $this->json_encode($profit2);
            }
        }
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
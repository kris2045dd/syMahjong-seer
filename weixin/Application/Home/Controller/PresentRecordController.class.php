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
//提现记录
class PresentRecordController extends Controller {
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
    public function index1(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $row = M("user_list")
               //->field("")
               ->where("uid='$uid'")
               ->select();
        echo $this->json_encode($row);
    }

    public function index(){
        //SELECT * FROM bank_katti GROUP BY  date_format(presentation_time ,'%Y-%m')
        $phone = I('post.phone');
        $row = M("bank_katti")
              ->where("uid='100005'")
              ->group("date_format(presentation_time ,'%Y-%m')")
              ->select();
        //echo "<pre>";var_dump($row);
        $count  = count($row);//echo $count;
        $time = "";
        $a = "";
        $times = "";
        for($i=0;$i<$count;$i++){
            //$time .= $row[$i]['presentation_time'].",";
            //$time .= date_format($row[$i]['presentation_time'],'%Y-%m').",";
            $time = $row[$i]['presentation_time'];
            $time = explode(" ",$time);
            /*$time = $time[$i];
            $time .= $time.",";*/
            $time = $time[0];
            /*$count1 = count($time);
            for($j=0;$j<$count1;$j++){
                $a = $time[0];
            }
            $a .= $a.",";*/
            //echo $time;echo "<br>";
            $time = substr($time,0,-3);
            $times .= $time.",";
        }
        $times = substr($times,0,-1);//echo $times;
        $arr = explode(",",$times);
        $count2 = count($arr);
        $resultjson1 = "";
        for($i=0;$i<$count2;$i++){
            $date = $arr[$i];
            $start = $date."-01 00:00:00";
            $endStr = strtotime($date,"now");
            //$endStr1 = date("Y-m-d H:i:s", $endStr);
            /*$endStr1 = (strtotime("+1 month"));
            $endStr2 = date("Y-m-d H:i:s", $endStr1);
            echo $endStr2;echo "<br>";*/
            //echo $date;echo "<br>";
            $s = explode("-",$date);
            $ss = $s[1];
            $ss = $ss+1;
            if(strlen($ss)==1){
                $sd = "0".$ss;
                //echo $sd;echo "<br>";
            }
            else {
                $sd = $ss;
                //echo $sd;echo "<br>";
            }
            $timee = $s[0]."-".$sd;
            //$end = date("Y-m-d H:i:s", $timee);
            //echo $timee;echo "<br>";
            $end = $timee."-01 00:00:00";
            /*echo "<pre>";var_dump($start);
            echo "<pre>";var_dump($end);*/
            $result1 = M('bank_katti')->where("presentation_time>='$start' and presentation_time<='$end'")->select();
            //echo "<pre>";var_dump($result1);
            $json1 = $this->json_encode($result1);//echo $json1;echo "<br>";
            $resultjson1 .= '{"dater":"'.$date.'","sites":'.$json1.'}'.',';
            //echo $resultjson1;echo "<br>";
        }
        $resultjson1 = substr($resultjson1,0,-1);
        $resultjson1 = "[".$resultjson1."]";
        //echo $resultjson1;echo "<br>";
        //$resultjson1 = str_replace('"},{"','"}],[{"',$resultjson1);
        echo $resultjson1;echo "<br>";
        $arrr = json_decode($resultjson1);
        $arrr1 = json_decode(json_encode($arrr),true);echo "<pre>";var_dump($arrr1);
        echo "第一大点:".$arrr1[0]['sites'][0]['bank_card_type'];
        //echo $this->json_encode($row);
    }
    public function trrr(){
    	echo "trrr";
    }
    public function indexa(){
        //SELECT * FROM bank_katti GROUP BY  date_format(presentation_time ,'%Y-%m')
        $phone = I('post.phone');
        $row = M("bank_katti")
            ->where("uid='100005'")
            ->group("date_format(presentation_time ,'%Y-%m')")
            ->select();
        //echo "<pre>";var_dump($row);
        $count  = count($row);//echo $count;
        $time = "";
        $a = "";
        $times = "";
        for($i=0;$i<$count;$i++){
            //$time .= $row[$i]['presentation_time'].",";
            //$time .= date_format($row[$i]['presentation_time'],'%Y-%m').",";
            $time = $row[$i]['presentation_time'];
            $time = explode(" ",$time);
            /*$time = $time[$i];
            $time .= $time.",";*/
            $time = $time[0];
            /*$count1 = count($time);
            for($j=0;$j<$count1;$j++){
                $a = $time[0];
            }
            $a .= $a.",";*/
            //echo $time;echo "<br>";
            $time = substr($time,0,-3);
            $times .= $time.",";
        }
        $times = substr($times,0,-1);//echo $times;
        $arr = explode(",",$times);
        $count2 = count($arr);
        $resultjson1 = "";
        for($i=0;$i<$count2;$i++){
            $date = $arr[$i];
            $start = $date."-01 00:00:00";
            $endStr = strtotime($date,"now");
            //$endStr1 = date("Y-m-d H:i:s", $endStr);
            /*$endStr1 = (strtotime("+1 month"));
            $endStr2 = date("Y-m-d H:i:s", $endStr1);
            echo $endStr2;echo "<br>";*/
            //echo $date;echo "<br>";
            $s = explode("-",$date);
            $ss = $s[1];
            $ss = $ss+1;
            if(strlen($ss)==1){
                $sd = "0".$ss;
                //echo $sd;echo "<br>";
            }
            else {
                $sd = $ss;
                //echo $sd;echo "<br>";
            }
            $timee = $s[0]."-".$sd;
            //$end = date("Y-m-d H:i:s", $timee);
            //echo $timee;echo "<br>";
            $end = $timee."-01 00:00:00";
            /*echo "<pre>";var_dump($start);
            echo "<pre>";var_dump($end);*/
            $result1 = M('bank_katti')
                       ->field("bank_katti.*,(case present_status when 0 then '已提交,银行处理中' else '已到账' end) as whether,date_format(presentation_time ,'%m-%d %H:%i') as mdhi")
                       ->where("presentation_time>='$start' and presentation_time<='$end'")
                       ->order("bank_katti.presentation_time asc")
                       ->select();
            //echo "<pre>";var_dump($result1);
            $json1 = $this->json_encode($result1);//echo $json1;echo "<br>";
            $counts = M('bank_katti')->where("presentation_time>='$start' and presentation_time<='$end'")->count();
            $resultjson1 .= '{"count":"'.$counts.'","dater":"'.$date.'","sites":'.$json1.'}'.',';
            //echo $resultjson1;echo "<br>";
            //得到每一个月的数据信息的长度 [1,4]
            /*$counts = M('bank_katti')->where("presentation_time>='$start' and presentation_time<='$end'")->count();
            echo $counts;echo "<br>";*/
        }
        $resultjson1 = substr($resultjson1,0,-1);
        //$resultjson1 = '{"county":"3"},'.$resultjson1;
        $resultjson1 = "[".$resultjson1."]";
        //echo $resultjson1;echo "<br>";
        //$resultjson1 = str_replace('"},{"','"}],[{"',$resultjson1);
        //echo $resultjson1;echo "<br>";
        $arrr = json_decode($resultjson1);
        $arrr1 = json_decode(json_encode($arrr),true);//echo "<pre>";var_dump($arrr1);
        //echo "第一大点:".$arrr1[0]['sites'][0]['bank_card_type'];
        //echo $this->json_encode("第一大点:".$arrr1[0]['sites'][0]['whether']);
        echo $this->json_encode($arrr1);
    }

    public function test(){
        //$json = '{"dater":"2018-07","sites":[{"id":"1","uid":"100005","bank_card_type":"建设银行储蓄卡","card_number":"6222085211254215220","presentation_time":"2018-07-11 10:21:28","cash_amount":"100.00","present_status":"0","total":"2650.00","balance":"2550.00"},{"id":"2","uid":"100005","bank_card_type":"招商银行储蓄卡","card_number":"62222154260265323","presentation_time":"2018-07-10 10:24:40","cash_amount":"200.00","present_status":"1","total":"2850.00","balance":"2650.00"},{"id":"6","uid":"100005","bank_card_type":"中国建设银行","card_number":"123456789123456789","presentation_time":"2018-07-17 15:14:46","cash_amount":"100.00","present_status":"0","total":"2550.00","balance":"2450.00"}]}';
        //$json = '{"dater":"2018-06","sites":[{"id":"3","uid":"100005","bank_card_type":"建设银行储蓄卡","card_number":"6222085211254211111","presentation_time":"2018-06-01 10:32:49","cash_amount":"150.00","present_status":"1","total":"3000.00","balance":"2850.00"}]},{"dater":"2018-07","sites":[{"id":"1","uid":"100005","bank_card_type":"建设银行储蓄卡","card_number":"6222085211254215220","presentation_time":"2018-07-11 10:21:28","cash_amount":"100.00","present_status":"0","total":"2650.00","balance":"2550.00"},{"id":"2","uid":"100005","bank_card_type":"招商银行储蓄卡","card_number":"62222154260265323","presentation_time":"2018-07-10 10:24:40","cash_amount":"200.00","present_status":"1","total":"2850.00","balance":"2650.00"},{"id":"6","uid":"100005","bank_card_type":"中国建设银行","card_number":"123456789123456789","presentation_time":"2018-07-17 15:14:46","cash_amount":"100.00","present_status":"0","total":"2550.00","balance":"2450.00"}]}';
        //$json = '{"dater":"2018-07","sites":[{"id":"1","uid":"100005","bank_card_type":"建设银行储蓄卡","card_number":"6222085211254215220","presentation_time":"2018-07-11 10:21:28","cash_amount":"100.00","present_status":"0","total":"2650.00","balance":"2550.00"}]}';
        //$json = '[{"dater":"2018-06","sites":[{"id":"3","uid":"100005","bank_card_type":"建设银行储蓄卡","card_number":"6222085211254211111","presentation_time":"2018-06-01 10:32:49","cash_amount":"150.00","present_status":"1","total":"3000.00","balance":"2850.00"}]},{"dater":"2018-07","sites":[{"id":"1","uid":"100005","bank_card_type":"建设银行储蓄卡","card_number":"6222085211254215220","presentation_time":"2018-07-11 10:21:28","cash_amount":"100.00","present_status":"0","total":"2650.00","balance":"2550.00"},{"id":"2","uid":"100005","bank_card_type":"招商银行储蓄卡","card_number":"62222154260265323","presentation_time":"2018-07-10 10:24:40","cash_amount":"200.00","present_status":"1","total":"2850.00","balance":"2650.00"},{"id":"6","uid":"100005","bank_card_type":"中国建设银行","card_number":"123456789123456789","presentation_time":"2018-07-17 15:14:46","cash_amount":"100.00","present_status":"0","total":"2550.00","balance":"2450.00"}]}]';
        $json = '[{"dater":"2018-06","sites":[{"id":"3","uid":"100005","bank_card_type":"建设银行储蓄卡","card_number":"6222085211254211111","presentation_time":"2018-06-01 10:32:49","cash_amount":"150.00","present_status":"1","total":"3000.00","balance":"2850.00"}]},{"dater":"2018-07","sites":[{"id":"1","uid":"100005","bank_card_type":"建设银行储蓄卡","card_number":"6222085211254215220","presentation_time":"2018-07-11 10:21:28","cash_amount":"100.00","present_status":"0","total":"2650.00","balance":"2550.00"},{"id":"2","uid":"100005","bank_card_type":"招商银行储蓄卡","card_number":"62222154260265323","presentation_time":"2018-07-10 10:24:40","cash_amount":"200.00","present_status":"1","total":"2850.00","balance":"2650.00"},{"id":"6","uid":"100005","bank_card_type":"中国建设银行","card_number":"123456789123456789","presentation_time":"2018-07-17 15:14:46","cash_amount":"100.00","present_status":"0","total":"2550.00","balance":"2450.00"}]}]';
        echo $json;
        $arrr = json_decode($json);
        $arrr1 = json_decode(json_encode($arrr),true);echo "<pre>";var_dump($arrr1);
        echo "第一大点:".$arrr1[0]['sites'][0]['bank_card_type'];
    }

    //日期搜索
    public function search(){
        $phone = I('post.phone');
        $datemd = I('post.datemd');
        //$datemd = "2018-06";
        $rowuid = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $rowuid[0]['uid'];
        $start = $datemd."-01 00:00:00";
        $s = explode("-",$datemd);
        $ss = $s[1];
        $ss = $ss+1;
        if(strlen($ss)==1){
            $sd = "0".$ss;
        }
        else {
            $sd = $ss;
        }
        $timee = $s[0]."-".$sd;
        $end = $timee."-01 00:00:00";
        $result1 = M('bank_katti')
            ->field("bank_katti.*,(case present_status when 0 then '已提交,银行处理中' else '已到账' end) as whether,date_format(presentation_time ,'%m-%d %h:%i') as mdhi")
            ->where("uid='$uid' and presentation_time>='$start' and presentation_time<'$end'")
            ->order("bank_katti.presentation_time asc")
            ->select();
        $json1 = $this->json_encode($result1);
        $counts = M('bank_katti')->where("presentation_time>='$start' and presentation_time<='$end'")->count();
        $resultjson1 = '{"count":"'.$counts.'","dater":"'.$datemd.'","sites":'.$json1.'}';
        $resultjson1 = "[".$resultjson1."]";
        $arrr = json_decode($resultjson1);
        $arrr1 = json_decode(json_encode($arrr),true);
        echo $this->json_encode($arrr1);
    }

    //日历得到默认的月份
    public function de(){
        $defaultDate = date("Y-m");
        $beginYear = "1898";
        $arr = explode("-",$defaultDate);
        $year = $arr[0];
        $endYear = $year;//echo $endYear;
        $json = '{"defaultDate":"'.$defaultDate.'","beginYear":"'.$beginYear.'","endYear":"'.$endYear.'"}';
        echo $json;
    }

}
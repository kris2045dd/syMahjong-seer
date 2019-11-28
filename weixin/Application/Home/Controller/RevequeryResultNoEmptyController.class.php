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
class RevequeryResultNoEmptyController extends Controller {
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

    //收益查询uid为空的结果
    /*
     * 游戏ID为空的意思就是查询profit_log等于登陆时得到的uid
     * */
    //总收益
    public function index(){
        $uid = I('post.uid');
        $rows = M("profit_log")
            ->field("SUM(self_profit+pre_profit+pre_pre_profit) as sum")
            ->where("gen_uid='$uid'")
            ->select();
        $profit = $rows[0]['sum'];
        if(empty($profit)){
            echo $this->json_encode("0.00");
        }
        else {
            echo $this->json_encode($profit);
        }
        //echo $this->json_encode($profit);
    }
    //玩家贡献
    public function player(){
        $uid = I('post.uid');
        $rows = M("profit_log")
            ->field("SUM(self_profit+pre_profit+pre_pre_profit) as sum")
            ->where("gen_uid='$uid' and gen_lv='1'")
            ->select();
        $profit = $rows[0]['sum'];
        if(empty($profit)){
            echo $this->json_encode("0.00");
        }
        else {
            echo $this->json_encode($profit);
        }
        //echo $this->json_encode($profit);
    }
    //推广员贡献
    public function promoters(){
        $uid = I('post.uid');
        $rows = M("profit_log")
            ->field("SUM(self_profit+pre_profit+pre_pre_profit) as sum")
            ->where("gen_uid='$uid' and gen_lv='2'")
            ->select();
        $profit = $rows[0]['sum'];
        if(empty($profit)){
            echo $this->json_encode("0.00");
        }
        else {
            echo $this->json_encode($profit);
        }
        //echo $this->json_encode($profit);
    }
    //馆主贡献
    public function owner(){
        $uid = I('post.uid');
        $rows = M("profit_log")
            ->field("SUM(self_profit+pre_profit+pre_pre_profit) as sum")
            ->where("gen_uid='$uid' and gen_lv='3'")
            ->select();
        $profit = $rows[0]['sum'];
        if(empty($profit)){
            echo $this->json_encode("0.00");
        }
        else {
            echo $this->json_encode($profit);
        }
        //echo $this->json_encode($profit);
    }
    //茶馆玩家列表
    public function playerList(){
        $uid = I('post.uid');
        $rows = M("profit_log")
            ->field("SUM(profit_log.self_profit+profit_log.pre_profit+profit_log.pre_pre_profit) as sum,user_list.head_pic,user_list.uid,user_list.wx_name,date_format(last_login_time,'%Y-%m-%d') as times")
            ->join("LEFT JOIN user_list ON profit_log.gen_uid=user_list.uid")
            ->where("profit_log.gen_lv=1 AND profit_log.gen_uid=$uid")
            ->group("profit_log.gen_uid")
            ->select();
        if(empty($rows)){
            echo $this->json_encode("");
        }
        else {
            echo $this->json_encode($rows);
        }
        //echo $this->json_encode($rows);

    }
    //推广员列表
    public function promotersList(){
        $uid = I('post.uid');
        $rows = M("profit_log")
            ->field("SUM(profit_log.self_profit+profit_log.pre_profit+profit_log.pre_pre_profit) as sum,user_list.head_pic,user_list.uid,user_list.wx_name,date_format(last_login_time,'%Y-%m-%d') as times")
            ->join("LEFT JOIN user_list ON profit_log.gen_uid=user_list.uid")
            ->where("(profit_log.gen_lv=2 or profit_log.gen_lv=3) AND profit_log.gen_uid=$uid")
            ->group("profit_log.gen_uid")
            ->select();
        if(empty($rows)){
            echo $this->json_encode("");
        }
        else {
            echo $this->json_encode($rows);
        }
        //echo $this->json_encode($rows);
    }
    //馆主列表
    public function ownerList(){
        $uid = I('post.uid');
        $rows = M("profit_log")
            ->field("SUM(profit_log.self_profit+profit_log.pre_profit+profit_log.pre_pre_profit) as sum,user_list.head_pic,user_list.uid,user_list.wx_name,date_format(last_login_time,'%Y-%m-%d') as times")
            ->join("LEFT JOIN user_list ON profit_log.gen_uid=user_list.uid")
            ->where("profit_log.gen_lv=3 AND profit_log.gen_uid=$uid")
            ->group("profit_log.gen_uid")
            ->select();
        if(empty($rows)){
            echo $this->json_encode("");
        }
        else {
            echo $this->json_encode($rows);
        }
        //echo $this->json_encode($rows);
    }

}
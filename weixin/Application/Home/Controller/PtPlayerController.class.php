<?php
// 普通玩家
namespace Home\Controller;
use Think\Controller;
header('Content-type:text/html;charset=UTF-8');
// 指定允许其他域名访问
header('Access-Control-Allow-Origin:*');
// 响应类型
header('Access-Control-Allow-Methods:POST');
// 响应头设置
header('Access-Control-Allow-Headers:x-requested-with,content-type');
class PtPlayerController extends Controller {
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
    //普通玩家查询出来的数据
    /*
     * 需要得到的数据有:
     * head_pic(微信头像)
     * wx_name(游戏昵称)
     *(推广收益)
     * */
    public function index1(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $rows = M("user_list")
            ->field("next_uids")
            //->where("uid='$uid' and (proxy_lv='0' or proxy_lv='1')")
            ->where("uid='$uid'")
            ->select();
        //查询出来的数据只有一个
        $uids = $rows[0]['next_uids'];//echo $uids;
        $uids = substr($uids,0,-1);
        $uids = substr($uids,1);//echo $uids;
        $rowu = M("user_list")
                ->where("uid in ($uids) and (proxy_lv='0' or proxy_lv='1')")
                ->select();//echo "<pre>";var_dump($rowu);



        $arr = json_decode($uids);
        $count = count($rowu);
        $str = "";
        for($i=0;$i<$count;$i++){
            $str .= "gen_uid=".$rowu[$i]['uid']." or ";
        }
        $where = substr($str,0,-4);//echo $where;

        //SELECT SUM(self_profit+pre_profit+pre_pre_profit) as sum FROM profit_log WHERE gen_uid=100038 or gen_uid=100039 GROUP BY gen_uid
        //SELECT SUM(profit_log.self_profit+profit_log.pre_profit+profit_log.pre_pre_profit) as sum,user_list.last_login_time,user_list.head_pic,user_list.uid,user_list.wx_name FROM profit_log LEFT JOIN user_list ON profit_log.gen_uid=user_list.uid WHERE profit_log.gen_uid=100038 or profit_log.gen_uid=100039 GROUP BY profit_log.gen_uid
        //date_format(last_login_time,'%Y-%m-%d') as last_login_time

        /*$rowss = M("profit_log")
            //->field("next_uids")
            ->where($where)
            ->select();echo "<pre>";var_dump($rowss);*/
        $rowss = M("profit_log")
            ->field("SUM(profit_log.self_profit+profit_log.pre_profit+profit_log.pre_pre_profit) as sum,user_list.head_pic,user_list.uid,user_list.wx_name,date_format(last_login_time,'%Y-%m-%d') as last_login_time")
            ->join("LEFT JOIN user_list ON profit_log.gen_uid=user_list.uid")
            ->where($where)
            ->group("profit_log.gen_uid")
            ->select();
        $json = $this->json_encode($rowss);
        echo $json;
    }

    public function index(){
        $phone = I('post.phone');
        $uidInput = I('post.uid');
        //$uidInput = "100039";
        if(empty($uidInput)){
            $row = M("user_list")->where("phone_number='13200231520'")->select();
            $uid = $row[0]['uid'];
            $rows = M("user_list")
                ->field("next_uids")
                ->where("uid='$uid'")
                ->select();
            //查询出来的数据只有一个
            $uids = $rows[0]['next_uids'];
            $uids = substr($uids,0,-1);
            $uids = substr($uids,1);
            $rowu = M("user_list")
                ->where("uid in ($uids) and (proxy_lv='0' or proxy_lv='1')")
                ->select();
            $arr = json_decode($uids);
            $count = count($rowu);
            $str = "";
            for($i=0;$i<$count;$i++){
                $str .= "gen_uid=".$rowu[$i]['uid']." or ";
            }
            $where = substr($str,0,-4);
            $rowss = M("profit_log")
                ->field("SUM(profit_log.self_profit+profit_log.pre_profit+profit_log.pre_pre_profit) as sum,user_list.head_pic,user_list.uid,user_list.wx_name,date_format(last_login_time,'%Y-%m-%d') as last_login_time")
                ->join("LEFT JOIN user_list ON profit_log.gen_uid=user_list.uid")
                ->where($where)
                ->group("profit_log.gen_uid")
                ->select();
            $json = $this->json_encode($rowss);
            echo $json;
        }
        else {
            /*$row = M("user_list")->where("phone_number='13200231520'")->select();
            $uid = $row[0]['uid'];
            $rows = M("user_list")
                ->field("next_uids")
                ->where("uid='$uid'")
                ->select();
            //查询出来的数据只有一个
            $uids = $rows[0]['next_uids'];
            $uids = substr($uids,0,-1);
            $uids = substr($uids,1);
            $rowu = M("user_list")
                ->where("uid in ($uids) and (proxy_lv='0' or proxy_lv='1')")
                ->select();
            $arr = json_decode($uids);
            $count = count($rowu);
            $str = "";
            for($i=0;$i<$count;$i++){
                $str .= "gen_uid=".$rowu[$i]['uid']." or ";
            }
            $where = substr($str,0,-4);*/
            $rowss = M("profit_log")
                ->field("SUM(profit_log.self_profit+profit_log.pre_profit+profit_log.pre_pre_profit) as sum,user_list.head_pic,user_list.uid,user_list.wx_name,date_format(last_login_time,'%Y-%m-%d') as last_login_time")
                ->join("LEFT JOIN user_list ON profit_log.gen_uid=user_list.uid")
                ->where("gen_uid='$uidInput'")
                ->group("profit_log.gen_uid")
                ->select();
            $json = $this->json_encode($rowss);
            echo $json;
        }
        /*$row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $rows = M("user_list")
            ->field("next_uids")
            ->where("uid='$uid'")
            ->select();
        //查询出来的数据只有一个
        $uids = $rows[0]['next_uids'];
        $uids = substr($uids,0,-1);
        $uids = substr($uids,1);
        $rowu = M("user_list")
            ->where("uid in ($uids) and (proxy_lv='0' or proxy_lv='1')")
            ->select();
        $arr = json_decode($uids);
        $count = count($rowu);
        $str = "";
        for($i=0;$i<$count;$i++){
            $str .= "gen_uid=".$rowu[$i]['uid']." or ";
        }
        $where = substr($str,0,-4);
        $rowss = M("profit_log")
            ->field("SUM(profit_log.self_profit+profit_log.pre_profit+profit_log.pre_pre_profit) as sum,user_list.head_pic,user_list.uid,user_list.wx_name,date_format(last_login_time,'%Y-%m-%d') as last_login_time")
            ->join("LEFT JOIN user_list ON profit_log.gen_uid=user_list.uid")
            ->where($where)
            ->group("profit_log.gen_uid")
            ->select();
        $json = $this->json_encode($rowss);
        echo $json;*/
    }

}
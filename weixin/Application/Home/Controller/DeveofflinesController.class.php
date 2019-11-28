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
class DeveofflinesController extends Controller {
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
    //普通玩家总数
    public function ordinaryCount(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $count = M("user_list")->where("pre_uid='$uid' and (proxy_lv='0' or proxy_lv='1')")->count();
        echo json_encode($count);
    }
    //普通玩家近七日新增人数
    /*
     * 1、根据注册时间regisder_time来判断近七日的数据
     * */
    public function ordinaryCountFew(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $end = date("Y-m-d H:i:s");//echo 111;
        $start = date("Y-m-d H:i:s",strtotime("-7 day"));
        $count = M("user_list")->where("pre_uid='$uid' and (proxy_lv='0' or proxy_lv='1') and regisder_time>='$start' and regisder_time<='$end'")->count();
        echo json_encode($count);
    }
    //金豆玩家总和
    public function beansCount(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $count = M("user_list")->where("pre_uid='$uid' and proxy_lv='2'")->count();
        echo json_encode($count);
    }
    //金豆玩家近七日新增人数
    public function beansCountFew(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $end = date("Y-m-d H:i:s");
        $start = date("Y-m-d H:i:s",strtotime("-7 day"));
        $count = M("user_list")->where("pre_uid='$uid' and proxy_lv='2' and regisder_time>='$start' and regisder_time<='$end'")->count();
        echo json_encode($count);
    }
    //推广员总和
    public function promotersCount(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $count = M("user_list")->where("pre_uid='$uid' and (proxy_lv='3' or proxy_lv='4'  or proxy_lv='5')")->count();
        echo json_encode($count);
    }
    //推广员近七日新增人数
    public function promotersCountFew(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $end = date("Y-m-d H:i:s");
        $start = date("Y-m-d H:i:s",strtotime("-7 day"));
        //$count = M("user_list")->where("pre_uid='$uid' and (proxy_lv='3' or proxy_lv='4' or proxy_lv='5') and regisder_time>='$start' and regisder_time<='$end'")->count();
        $count = M("user_list")->where("pre_uid='$uid' and (proxy_lv='3' or proxy_lv='4' or proxy_lv='5') and regisder_time>='$start' and regisder_time<='$end'")->count();
        echo json_encode($count);
    }
    //馆主总和
    public function ownerCount(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $count = M("user_list")->where("pre_uid='$uid' and proxy_lv='5'")->count();
        echo json_encode($count);
    }
    //馆主近七日新增人数
    public function ownerCountFew(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $end = date("Y-m-d H:i:s");
        $start = date("Y-m-d H:i:s",strtotime("-7 day"));
        $count = M("user_list")->where("pre_uid='$uid' and proxy_lv='5' and regisder_time>='$start' and regisder_time<='$end'")->count();
        echo json_encode($count);
    }
    //只有中级推广员以上才有待审核(proxy_lv:[4,5])
    //搜索
    public function search(){
        $phone = I('post.phone');
        $uid = I('post.uid');
        $row = M("user_list")
               ->where("phone_number='13200231520'")
               ->select();
        //得到是他下级的所有玩家的uid
        $uids = $row[0]['next_uids'];
        $uids = substr($uids,0,-1);
        $uids = substr($uids,1);
        $arr = explode(",",$uids);
        $isin = in_array($uid,$arr);
        //得到他下级所有玩家的游戏昵称
        $count = count($arr);
        $wx_name = "";
        for($i=0;$i<$count;$i++){
            $wx_name_arr = M("user_list")->where("uid='$arr[$i]'")->select();
            $wx_name .= $wx_name_arr[0]['wx_name'].",";
        }
        $wx_name = substr($wx_name,0,-1);
        $arr1 = explode(",",$wx_name);//echo "<pre>";var_dump($wx_name_arr1);
        $isin1 = in_array($uid,$arr1);
        if(empty($uid)){
            $array['status']=-2;
        }
        else {
            $self = $row[0]['uid'];
            if($uid===$self){
                $array['status']=-1;
            }
            else {
                if($isin or $isin1){
                    //$array['status']=1;//输入的信息正确
                    $rowz = M("user_list")->where("uid='$uid' or wx_name='$uid'")->select();
                    $proxy_lv = $rowz[0]['proxy_lv'];
                    if($proxy_lv==1){ //为普通玩家
                        $array['status']=1;
                    }
                    else if($proxy_lv==2){ //为金贝玩家
                        $array['status']=2;
                    }
                    else if($proxy_lv==3 or $proxy_lv==4 or $proxy_lv==5){ //为所有的推广员
                        $array['status']=3;
                    }
                }
                else {
                    $array['status']=0;
                }
            }
        }
        echo json_encode($array);
    }
}
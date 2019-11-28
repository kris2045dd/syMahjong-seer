<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
use Think\Db;
use Think\Log;
header("Content-type:text/html;charset=utf8");
// 指定允许其他域名访问
header('Access-Control-Allow-Origin:*');
// 响应类型
header('Access-Control-Allow-Methods:POST');
// 响应头设置
header('Access-Control-Allow-Headers:x-requested-with,content-type');
//扫描二维码跳转得到uid和...
class Wx5Controller extends Controller {
    //微信公众号
    public $appid = "wxc2564e2762b88b85";
    public $appsecret = "8bf2480259bd7eebd5bacb473f395bd7";

    //添加或修改数据
    public function index($unionid,$nickname,$headimgurl,$openid){
        $where = "unionid='$unionid'";//echo "unionid为:".$unionid;
        $row = D('Wx5')->list1()->where($where)->select();//echo "<pre>";var_dump($row);
        if(empty($row)){ //没有数据就添加数据
            $uidArr = D("Wx5")->list1()->order("uid desc")->select();
            if(empty($uidArr)){
                $uid = "100001";
                $next_uids = "[]";
                $data["uid"] = $uid;
                $data["wx_name"] = $nickname;
                $data["head_pic"] = $headimgurl;
                $data["openid"] = $openid;
                $data["follow_time"] = date("Y-m-d H:i:s");
                $data["next_uids"] = $next_uids;//unionid
                $data["unionid"] = $unionid;
                $data["login_time"] = date("Y-m-d H:i:s");
                D('Wx5')->list1()->add($data);
            }
            else {
                $uid = $uidArr[0]["uid"];
                $uid = ++$uid;
                $next_uids = "[]";
                $data["uid"] = $uid;
                $data["wx_name"] = $nickname;
                $data["head_pic"] = $headimgurl;
                $data["openid"] = $openid;
                $data["follow_time"] = date("Y-m-d H:i:s");
                $data["login_time"] = date("Y-m-d H:i:s");
                $data["next_uids"] = $next_uids;
                $data["unionid"] = $unionid;
                D('Wx5')->list1()->add($data);
            }
        }
        else { //有数据就直接修改login_time[最近登录时间]
            $getUid = $row[0]['uid'];//echo $getUid;
            $data["login_time"] = date("Y-m-d H:i:s");
            D('Wx5')->list1()->where("uid='$getUid'")->save($data);
        }
    }

    public function curl($url,$data_jsons=""){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_jsons);
        curl_setopt($ch,CURLOPT_TIMEOUT,30);
        curl_setopt($ch,CURLOPT_NOSIGNAL,true);
        $output = curl_exec($ch);

        curl_close($ch);
        return $output;
    }
    public function code(){
        //1.获取到code
        $appid="wxc2564e2762b88b85";//这里的appid是假的演示用
        $redirect_uri=urlencode("http://caiyunyigou.com/tp3.2/home/Wx5/getUserInfoAll");//这里的地址需要http://
        $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_userinfo&state=100001#wechat_redirect";
        header('location:'.$url);
    }
    function getUserInfoAll()
    {

        $appid = "wxc2564e2762b88b85";
        $secret = "8bf2480259bd7eebd5bacb473f395bd7";
        $access_token = "";

        //根据code获得Access Token
        $code = $_GET['code'];
        $access_token_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
        $access_token_json = $this->curl($access_token_url);
        $access_token_array = json_decode($access_token_json, true);
        $access_token = $access_token_array['access_token'];
        $openid = $access_token_array['openid'];

        //根据Access Token和OpenID获得用户信息
        $userinfo_url = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid ";//无subscribe
        $userinfo = $this->curl($userinfo_url);
        $userinfo = json_decode($userinfo, true);
        $unionid = $userinfo["unionid"];
        $nickname = $userinfo["nickname"];
        $headimgurl = $userinfo["headimgurl"];
        $openid1 = $userinfo["openid"];
        $this->index($unionid,$nickname,$headimgurl,$openid1);
    }
}
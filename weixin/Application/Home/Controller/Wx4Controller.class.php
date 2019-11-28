<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
use Think\Log;
header("Content-type:text/html;charset=utf8");
// 指定允许其他域名访问
header('Access-Control-Allow-Origin:*');
// 响应类型
header('Access-Control-Allow-Methods:POST');
// 响应头设置
header('Access-Control-Allow-Headers:x-requested-with,content-type');
//unionid为空的情况下微信授权操作得到微信用户的信息
class Wx4Controller extends Controller {
    //微信公众号
    public $appid = "wxc2564e2762b88b85";
    public $appsecret = "8bf2480259bd7eebd5bacb473f395bd7";
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
    function https_request($url,$type='get',$res='json',$data = ''){
        //1.初始化curl
        $curl = curl_init();
        //2.设置curl的参数
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,2);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        if ($type == "post"){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        //3.采集
        $output = curl_exec($curl);
        //4.关闭
        curl_close($curl);
        if ($res == 'json') {
            return json_decode($output,true);
        }
    }
    function getUserOpenId(){
        //2.获取到网页授权的access_token
        $appid="wxc2564e2762b88b85";//这里的appid是假的演示用
        $appsecret="8bf2480259bd7eebd5bacb473f395bd7";//这里的appsecret是假的演示用
        $code=$_GET['code'];echo $code;echo "<br>";
        $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$appsecret."&code=".$code."&grant_type=authorization_code";
        //3.拉取用户的openid
        $res = $this->https_request($url,'get');

        echo "<pre>";var_dump($res);//打印即可看到用户的openid

    }
    public function getUid(){
        $uid = $_POST["uid"];
        echo json_encode($uid);
    }
    public function code(){
        //1.获取到code
        $appid="wxc2564e2762b88b85";//这里的appid是假的演示用
        $uid = $_GET['uid'];Log::record("uid为:".$uid);
        $redirect_uri=urlencode("http://caiyunyigou.com/tp3.2/home/Wx2/getUserInfoAll?uid=".$uid);//这里的地址需要http://
        $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_userinfo&state=100001#wechat_redirect";
        header('location:'.$url);
    }
    function getUserInfoAll()
    {
        //echo "得到uid11";echo "<br>";
        $uid = $_GET["uid"];//echo $uid;echo "<br>";

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
        $json = "{'uid':'".$uid."','unionid':'".$userinfo["unionid"]."'}";
        //$json = '{"uid":"'.$uid.'","unionid":"'.$userinfo["unionid"].'"}';
        $json = json_encode($json,JSON_UNESCAPED_UNICODE);
        $json = str_replace("\\/","/",$json);
        //echo $json;
        //$arr = json_decode($json,true);echo "<pre>";var_dump($arr);
        $url = "http://caiyunyigou.com/xunbao/hometree/maid_rule/indexs.html?uid=".$uid."&unionid=".$userinfo["unionid"];
        header('location:'.$url);
    }

    //扫码跳转到返佣规则页面
    public function tiao(){
        $json = $this->getUserInfoAll();
        $json = str_replace("",'\"',$json);echo $json;
        $json = substr($json,0,-1);
        $json = substr($json,1);
        $json = "[".$json."]";
        $arr = json_decode($json,true);echo "<pre>";var_dump($arr);
        //{"uid":"100001","unionid":"ovbcLwRgas-cXlcEyQxHt93vqfTo"}
        $uid = $arr["uid"];
        $unionid = $arr["unionid"];//echo $unionid;
        /*$url = "http://caiyunyigou.com/xunbao/hometree/maid_rule/indexs.html?uid=".$uid."&unionid=".$unionid;
        header('location:'.$url);*/
    }

}
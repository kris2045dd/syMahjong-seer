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
class Wx1Controller extends Controller {
    //微信公众号
    public $appid = "wx8a3eddde82a2186f";
    public $appsecret = "be6744d7f24537b20a0f1539c8b2aea4";
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
        $appid="wx8a3eddde82a2186f";//这里的appid是假的演示用
        $appsecret="be6744d7f24537b20a0f1539c8b2aea4";//这里的appsecret是假的演示用
        $code=$_GET['code'];echo $code;echo "<br>";
        $url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$appsecret."&code=".$code."&grant_type=authorization_code";
        //3.拉取用户的openid
        $res = $this->https_request($url,'get');

        echo "<pre>";var_dump($res);//打印即可看到用户的openid

    }
    public function code(){
        //1.获取到code
        $appid="wx8a3eddde82a2186f";//这里的appid是假的演示用
        $uid = $_GET['uid'];Log::record("uid为:".$uid);
        $redirect_uri=urlencode("http://caiyunyigou.com/tp3.2/home/Wx1/getUserInfoAll?uid=".$uid);//这里的地址需要http://
        $url="https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_userinfo&state=100001#wechat_redirect";
        //$content='<a href="'.$url.'">点我跳转</a>';echo $content;
        header('location:'.$url);
    }
    function getUserInfoAll()
    {
        //echo "得到uid11";echo "<br>";
        $uid = $_GET["uid"];//echo $uid;echo "<br>";

        $appid = "wx8a3eddde82a2186f";
        $secret = "be6744d7f24537b20a0f1539c8b2aea4";
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
        //echo "<pre>";var_dump($userinfo);
        //$arr = '{"uid":"uid","openid":"openid","nickname":"nickname","sex":"sex","language":"language","city":"city","province":"province","country":"country","headimgurl":"headimgurl","unionid":"unionid"}';
        //$json = "{'uid':'".$uid."','openid':'".$userinfo["openid"]."','nickname':'".$userinfo["nickname"]."','sex':'".$userinfo["sex"]."','language':'".$userinfo["language"]."','city':'".$userinfo["city"]."','province':'".$userinfo["province"]."','country':'".$userinfo["country"]."','headimgurl':'".$userinfo["headimgurl"]."','unionid':'".$userinfo["unionid"]."'}";
        $json = "{'uid':'".$uid."','unionid':'".$userinfo["unionid"]."'}";
        $json = json_encode($json,JSON_UNESCAPED_UNICODE);
        $json = str_replace("\\/","/",$json);//以免url地址被反编译
        echo $json;
        //return $userinfo;
    }

}
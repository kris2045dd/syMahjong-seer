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
class WxController extends Controller {
    //微信公众号
    public $appid = "wx8a3eddde82a2186f";
    public $appsecret = "be6744d7f24537b20a0f1539c8b2aea4";
    private $postObj;//获取的数据


    public $ip = "http://192.168.9.252:7001";
    public $key = "__tazai_wolf__key";
    public $iv = "1234567890000000";
    //转化为json
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
    //解密
    public function decode($cryptkey, $iv, $secretdata){
        return openssl_decrypt($secretdata,'aes-256-cbc',$cryptkey,false,$iv);
    }
    //加密
    public function encode($cryptkey, $iv, $secretdata){
        return openssl_encrypt($secretdata,'aes-256-cbc',$cryptkey,false,$iv);
    }
    public function cryptkey(){
        $cryptkey = hash('sha256',$this->key,true);
        return $cryptkey;
    }

    public function url($url,$data_jsons){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_jsons);
        curl_setopt($ch,CURLOPT_TIMEOUT,30);
        curl_setopt($ch,CURLOPT_NOSIGNAL,true);
        $output = curl_exec($ch);

        curl_close($ch);

        $a = json_decode($output);
        $b = json_decode(json_encode($a),true);
        $jie = $this->decode($this->cryptkey(), $this->iv, $output);
        Log::record("返回结果是:".$jie);
        return $jie;
    }
    public function commonFun($getUnionId,$uid){
        $url = $this->ip;

        $post_data = array (
            "mode" => "gm",
            "act" => "qrScan",
            "unionId" => $getUnionId,
            "uid" => $uid
        );
        $data_jsons = json_encode($post_data);
        $data_jsons = $this->encode($this->cryptkey(),$this->iv,$data_jsons);

        return $this->url($url,$data_jsons);
    }
    //关注公众号与取消公众号接口
    public function commonFun1($unionId){
        $url = $this->ip;

        $post_data = array (
            "mode" => "gm",
            "act" => "subPub",
            "unionId" => $unionId
        );
        $data_jsons = json_encode($post_data);
        $data_jsons = $this->encode($this->cryptkey(),$this->iv,$data_jsons);

        return $this->url($url,$data_jsons);
    }
    public function getUnionId($FromUserName){
        $access_token = $this->getToken();
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$FromUserName."&lang=zh_CN";
        $data_jsons = "";
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

    public function wxPuMsg(){
        $signature = $_GET['signature'];
        //$echostr = $_GET['echostr'];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = "nyn";//这儿的token应该要判断是否过期
        $list = array($token,$timestamp,$nonce);
        sort($list, SORT_STRING);
        $tmpStr = implode($list);
        $hashcode = sha1($tmpStr);
        Log::record("signature: ".$signature);
        Log::record("hashcode: ".$hashcode);
        //echo $echostr;
        if($hashcode == $signature){
            //第一次的时候应该是返回$echostr这个变量
            //echo $echostr;

            $file_in = file_get_contents("php://input");
            Log::record("file_in: ".$file_in);
            $xml = simplexml_load_string($file_in);

            $ToUserName = $xml->ToUserName;
            $FromUserName = $xml->FromUserName;
            $CreateTime = $xml->CreateTime;
            $MsgType = $xml->MsgType;
            $Event = $xml->Event;
            $EventKey = $xml->EventKey;
            $Ticket = $xml->Ticket;

            /*if($MsgType=="event" and $Event=="SCAN"){
                //echo "success";
                $info = $this->getUnionId($FromUserName);
                $infoArr = json_decode($info,true);
                $getUnionId = $infoArr["unionid"];
                $list = M("wechat")->where("ticket='$Ticket'")->select();
                $uid = $list[0]["uid"];
                $arrs = $this->commonFun($getUnionId,$uid);
                $info = $this->getUnionId1($FromUserName);
                $infoArr = json_decode($info,true);
                $getUnionId = $infoArr["unionid"];
                $result = $this->receiveEvent($getUnionId);
                Log::record($getUnionId);
                Log::record($arrs);
                Log::record("success");
                echo 11;
            }
            else {
                echo 23;
            }*/

            $info = $this->getUnionId1($FromUserName);
            $infoArr = json_decode($info,true);
            $getUnionId = $infoArr["unionid"];

            //获取推广者的id
            $str = $xml->EventKey;
            $arr = explode("_",$str);
            $id = end($arr);

            //extract post data
            if (!empty($file_in)) {
                $this->postObj = simplexml_load_string($file_in, 'SimpleXMLElement', LIBXML_NOCDATA);
                $type=strtolower($this->postObj->MsgType);
                switch ($type) {
                    case 'event':
                        $result = $this->receiveEvent($getUnionId);
                        break;
                    case 'text':
                        $result = $this->receiveText();
                        break;
                    case 'image':
                        $result = $this->receiveImage();
                        break;
                    default:
                        $result = $this->receiveDefault();
                        break;
                }
                echo $result;
                exit;
            } else {
                echo "success";
                exit;
            }
        }
        echo "success";
    }

    //每天只能刷新200次
    function getToken(){
        $appid=$this->appid;
        $appsecret=$this->appsecret;
        $file = file_get_contents("data/access_token.json",true);
        $result = json_decode($file,true);
        if (time() > $result['expires']){
            $data = array();
            $data['access_token'] = $this->getNewToken($appid,$appsecret);
            $data['expires']=time()+7000;
            $jsonStr =  json_encode($data);
            $fp = fopen("data/access_token.json", "w");
            fwrite($fp, $jsonStr);
            fclose($fp);
            return $data['access_token'];
        }else{
            return $result['access_token'];
        }
    }
    function getNewToken($appid,$appsecret){
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$appsecret}";
        $access_token_Arr =  $this->https_request($url);
        return $access_token_Arr['access_token'];
    }
    function https_request ($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $out = curl_exec($ch);
        curl_close($ch);
        echo $out;
        return  json_decode($out,true);
    }

    //获得access_token值并且判断token是否过期
    public function getAccessToken(){
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$this->appid."&secret=".$this->appsecret;
        $data_jsons = "";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_jsons);
        curl_setopt($ch,CURLOPT_TIMEOUT,30);
        curl_setopt($ch,CURLOPT_NOSIGNAL,true);
        $output = curl_exec($ch);

        curl_close($ch);
        echo $output;
    }



    //获得一个json文件中的access_token值
    public function getAccessToken1(){

        // 从文件中读取数据到PHP变量
        $json_string = file_get_contents('data/access_token.json');

        // 用参数true把JSON字符串强制转成PHP数组
        $data = json_decode($json_string, true);
        $access_token = $data['access_token'];//echo $access_token;
        echo $access_token;
        return $access_token;
    }

    //创建二维码tiket接口 永久二维码
    /*
     * http请求方式: POST
     * URL: https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=TOKEN
     * POST数据格式：json
     * POST数据例子：{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": 123}}}
     * 返回:{"ticket":"gQH47joAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xL2taZ2Z3TVRtNzJXV1Brb3ZhYmJJAAIEZ23sUwMEmm
3sUw==","expire_seconds":60,"url":"http://weixin.qq.com/q/kZgfwMTm72WWPkovabbI"}
     * 返回的有两个参数ticket、expire_seconds、url
     * **/
    public function createCode($variable){
        $access_token = $this->getToken();//echo $access_token;
        $url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".$access_token;
        //$variable = "123";
        //$data_jsons = '{"action_name": "QR_LIMIT_SCENE", "action_info": {"scene": {"scene_id": '.$variable.'}}}';
        //test
        $data_jsons = '{"action_name": "QR_LIMIT_STR_SCENE", "action_info": {"scene": {"scene_str": "'.$variable.'"}}}';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_jsons);
        curl_setopt($ch,CURLOPT_TIMEOUT,30);
        curl_setopt($ch,CURLOPT_NOSIGNAL,true);
        $output = curl_exec($ch);

        curl_close($ch);
        //echo $output;
        return $output;
    }
    private function _request($method='get',$url,$data=array(),$ssl=true){
        //curl完成，先开启curl模块
        //初始化一个curl资源
        $curl = curl_init();
        //设置curl选项
        curl_setopt($curl,CURLOPT_URL,$url);//url
        //请求的代理信息
        $user_agent = isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:38.0) Gecko/20100101 Firefox/38.0 FirePHP/0.7.4';
        curl_setopt($curl,CURLOPT_USERAGENT,$user_agent);
        //referer头，请求来源
        curl_setopt($curl,CURLOPT_AUTOREFERER,true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);//设置超时时间
        //SSL相关
        if($ssl){
            //禁用后，curl将终止从服务端进行验证;
            curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,false);
            //检查服务器SSL证书是否存在一个公用名
            curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,2);
        }
        //判断请求方式post还是get
        if(strtolower($method)=='post') {
            /**************处理post相关选项******************/
            //是否为post请求 ,处理请求数据
            curl_setopt($curl,CURLOPT_POST,true);
            curl_setopt($curl,CURLOPT_POSTFIELDS,$data);
        }
        //是否处理响应头
        curl_setopt($curl,CURLOPT_HEADER,false);
        //是否返回响应结果
        curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);

        //发出请求
        $response = curl_exec($curl);
        if (false === $response) {
            echo '<br>', curl_error($curl), '<br>';
            return false;
        }
        //关闭curl
        curl_close($curl);
        return $response;
    }
    //保存微信推广二维码图片
    public function getQRCode($file,$uid,$ticket){
        //获取ticket
        $uids = $uid-10000;
        $url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".$ticket;
        //发送，取得图片数据
        $result = $this->_request('get',$url);//echo "<pre>";var_dump($result);
        if($file){//echo "1";
            file_put_contents($file,$result);
        }else{//echo "2";
            header('Content-Type:image/png');
            echo $result;
        }
    }
    public function getTicket(){
        $row1 = M("wechat")->order("variable desc")->select();
        $variable = $row1[0]['variable'];
        $variable = $variable+1;
        $row = $this->createCode($variable);
        $arr = json_decode($row,true);
        $ticket = $arr['ticket'];
        $enStr = $_POST["enStr"];
        $enStr = $this->decode($this->cryptkey(),$this->iv,$enStr);
        Log::record("数据为:".$enStr);
        $arr = json_decode($enStr,true);
        $uid = $arr["uid"];
        Log::record("uid为:".$uid);
        $data["uid"] = $uid;
        $data["ticket"] = $ticket;
        $data["variable"] = $variable;
        $data["time"] = date("Y-m-d H:i:s");
        M("wechat")->add($data);
        $this->getQRCode('./'.$uid.'.png',$uid,$ticket);
        $url = 'http://caiyunyigou.com/tp3.2/'.$uid.'.png';
        $post_data = array (
            "uid" => $uid,
            "ticket" => $ticket,
            "variable" => $variable,
            "url" => $url
        );

        $data_jsons = json_encode($post_data,JSON_UNESCAPED_UNICODE);
        Log::record("json数据为:".$data_jsons);
        echo $data_jsons;
    }
    //保存微信头像
    public function getHeader($file,$url){
        //$url = "http://thirdwx.qlogo.cn/mmopen/vi_32/urYjX4OaSmZ8nMNNTOvODQVD1uZt71HsEDtfMsD7XMDuWpBBhtOcx9QBeJJbw1ibv0wNuicvaT6U11nWsrwpLb4w/132";
        //发送，取得图片数据
        $result = $this->_request('get',$url);//echo "<pre>";var_dump($result);
        if($file){//echo "1";
            file_put_contents($file,$result);
        }else{//echo "2";
            header('Content-Type:image/png');
            echo $result;
        }
    }
    public function saveImg(){
        $uid = $_POST["uid"];
        $head_url = $_POST["head_url"];
        $pic = $uid.".png";
        $row = D('Header')->where("pic='$pic'")->select();
        if(!empty($row)){
            echo $this->json_encode("存在");
        }
        else{
            $url = $head_url;
            $this->getHeader('./Public/upload/header/'.$uid.'.png',$url);
            $save_url = "http://caiyunyigou.com/tp3.2/Public/upload/header/".$pic;
            $data["uid"] = $uid;
            $data["url"] = $url;
            $data["pic"] = $pic;
            $data["save_url"] = $save_url;
            D('Header')->add($data);
            $this->getHeader('./Public/upload/header/'.$pic,$url);
            echo $this->json_encode("不存在");
        }
    }
    public function saveImg1(){
        $uid = $_POST["uid"];
        $head_url = $_POST["head_url"];
        $pic = $uid.".png";
        $row = D('Header1')->header()->where("pic='$pic'")->select();
        if(!empty($row)){
            echo $this->json_encode("存在1");
        }
        else{
            $url = $head_url;
            $this->getHeader('./Public/upload/header1/'.$uid.'.png',$url);
            $save_url = "http://caiyunyigou.com/tp3.2/Public/upload/header1/".$pic;
            $data["uid"] = $uid;
            $data["url"] = $url;
            $data["pic"] = $pic;
            $data["save_url"] = $save_url;
            D('Header1')->header()->add($data);
            $this->getHeader('./Public/upload/header1/'.$pic,$url);
            echo $this->json_encode("不存在1");
        }
    }
    //测试回复消息
    public function receiveEvent($getUnionId){
        switch (strtolower($this->postObj->Event)){
            case 'subscribe':
                //$content="欢迎你关注此微信号,首次关注微信公众号后再次扫描二维码才能成为他的推广员!";
                //点击关注时候的操作
                $this->commonFun1($getUnionId);
                $content = "点击关注微信公众号按钮进入公众号得到用户的unionID为:".$getUnionId;
                break;
            case 'unsubscribe':
                $this->commonFun1($getUnionId);
                $content="你取消了关注!";
                break;
            case 'click':
                $content="你点击了菜单!";
                break;
            default:
                $content = "进入公众号得到的unionID为:".$getUnionId;
                break;
        }
        return $this->transmitText($content);
    }

    /**
     * 回复消息
     * 判断消息类型处理后,生成Xml回复消息
     */
    private function transmitText($content){
        $textTpl = "<xml>
                <ToUserName><![CDATA[%s]]></ToUserName>
                <FromUserName><![CDATA[%s]]></FromUserName>
                <CreateTime>%s</CreateTime>
                <MsgType><![CDATA[%s]]></MsgType>
                <Content><![CDATA[%s]]></Content>
                <FuncFlag>0</FuncFlag>
                </xml>";
        $result = sprintf($textTpl,$this->postObj->FromUserName,$this->postObj->ToUserName,time(),'text',$content);
        return $result;
    }

    public function getUnionId1($FromUserName){
        $access_token = $this->getToken();
        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$FromUserName."&lang=zh_CN";
        $data_jsons = "";
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

}
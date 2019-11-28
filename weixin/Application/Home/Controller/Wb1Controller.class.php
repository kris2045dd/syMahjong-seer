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
//保存header头像地址到数据库
class Wb1Controller extends Controller {
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
        $row = D('Header1')->header()->where("pic='$pic'")->select();
        if(!empty($row)){
            echo $this->json_encode("存在");
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
            echo $this->json_encode("不存在");
        }
    }
}
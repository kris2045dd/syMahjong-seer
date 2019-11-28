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
class SaveController extends Controller {
    function saveImage($path) {
        if(!preg_match('/\/([^\/]+\.[a-z]{3,4})$/i',$path,$matches))
            die('Use image please');
        $image_name = strToLower($matches[1]);echo $image_name;
        $ch = curl_init ($path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        $img = curl_exec ($ch);
        curl_close ($ch);
        //$image_name就是要保存到什么路径,默认只写文件名的话保存到根目录
        $fp = fopen($image_name,'w');//保存的文件名称用的是链接里面的名称
        fwrite($fp, $img);
        fclose($fp);
    }
    public function index(){
        $image = file_get_contents('http://www.jb51.net/images/logo.gif');
        file_put_contents('http://caiyunyigou.com/img/', $image);//Where to save the image

    }
    public function aa(){
        //"https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket="+ticket+"&uid="+uid;
        $this->saveImage('http://www.jb51.net/images/logo.gif');
    }

    public function upload(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =      './Uploads/'; // 设置附件上传根目录
        $upload->savePath  =      ''; // 设置附件上传（子）目录
// 上传文件
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功 获取上传文件信息
            foreach($info as $file){
                echo $file['savepath'].$file['savename'];
            }
        }
    }

    public function upload1(){
        $typeArr = array("jpg", "png", "gif");//允许上传文件格式
        $path = "http://caiyunyigou.com/img/";//上传路径
        $img = "http://www.jb51.net/images/logo.gif";
        if (isset($img)) { echo "null";echo "<br>";
            $name = "http://www.jb51.net/images/logo.gif";
            $size = $_FILES['file']['size'];
            $name_tmp = "http://www.jb51.net/images/logo.gif";
            if (empty($name)) {
                echo json_encode(array("error"=>"您还未选择图片"),JSON_UNESCAPED_UNICODE);
                exit;
            }
            $type = strtolower(substr(strrchr($name, '.'), 1)); //获取文件类型

            if (!in_array($type, $typeArr)) {
                echo json_encode(array("error"=>"清上传jpg,png或gif类型的图片！"),JSON_UNESCAPED_UNICODE);
                exit;
            }
            if ($size > (500 * 1024)) {
                echo json_encode(array("error"=>"图片大小已超过500KB！"),JSON_UNESCAPED_UNICODE);
                exit;
            }

            $pic_name = time() . rand(10000, 99999) . "." . $type;//图片名称
            echo $pic_name;echo "<br>";
            $pic_url = $path . $pic_name;//上传后图片路径+名称
            echo $pic_url;echo "<br>";
            if (move_uploaded_file($name_tmp, $pic_url)) { //临时文件转移到目标文件夹
                echo json_encode(array("error"=>"0","pic"=>$pic_url,"name"=>$pic_name));
            } else {
                echo json_encode(array("error"=>"上传有误，请检查服务器配置！"),JSON_UNESCAPED_UNICODE);
            }
        }
    }

    public function upload2(){

        $fileName = "http://caiyunyigou.com/img/"."gif";echo $fileName;echo "<br>";
        $upload = move_uploaded_file("http://www.jb51.net/images/logo.gif",$fileName);
        if($upload){
            echo "上传成功";
        }
        else {
            echo "上传失败";
        }
    }

    public function upload3(){
        $localfile = "php_homepage.txt";
        $fp = fopen ($localfile, "r");
        $arr_ip = gethostbyname("caiyunyigou.com");
        echo $arr_ip;echo "<br>";
        $ftp = "ftp://".$arr_ip."/public_html/".$localfile;echo $ftp;echo "<br>";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_USERPWD, 'root:htu@cc@951');
        curl_setopt($ch, CURLOPT_URL, $ftp);
        curl_setopt($ch, CURLOPT_PUT, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_INFILE, $fp);
        curl_setopt($ch, CURLOPT_INFILESIZE, filesize($localfile));
        $http_result = curl_exec($ch);
        $error = curl_error($ch);
        echo $error."<br>";
        $http_code = curl_getinfo($ch ,CURLINFO_HTTP_CODE);curl_close($ch);
        fclose($fp);
    }

    function dlfile($file_url, $save_to)

    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch,CURLOPT_URL,$file_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $file_content = curl_exec($ch);
        curl_close($ch);
        $downloaded_file = fopen($save_to, 'w');
        echo fwrite($downloaded_file, $file_content);echo "<br>";
        fclose($downloaded_file);
        //return $downloaded_file;
    }
    /**
     * 从网上下载图片保存到服务器
     * @param $path 图片网址
     * @param $image_name 保存到服务器的路径 './public/upload/users_avatar/'.time()
     */
    function dlfile1(){
        //$path = 'http://www.jb51.net/images/logo.gif';
        $path = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=gQGx8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAySGJCS2htOFJkTmgxMDAwME0wM2cAAgTct9JbAwQAAAAA";
        if(!preg_match('/\/([^\/]+\.[a-z]{3,4})$/i',$path,$matches))
            die('Use image please');
        $image_name = strToLower($matches[1]);
        $ch = curl_init ($path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    // https请求 不验证证书和hosts
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $img = curl_exec ($ch);
        curl_close ($ch);
//$image_name就是要保存到什么路径,默认只写文件名的话保存到根目录
        $fp = fopen($image_name,'w');//保存的文件名称用的是链接里面的名称
        fwrite($fp, $img);
        fclose($fp);
    }

    public function upload4(){
        $path = "http://www.jb51.net/images/logo.gif";
        $image_name = "./Uploads/";
        $save = $this->dlfile1($path,$image_name);
        echo $save;echo "<br>";
        if($save){
            echo "保存成功";
        }
        else {
            echo "保存失败";
        }
    }




    /*完成网页内容捕获功能*/
    function get_img_url($site_name){
        $site_fd=fopen($site_name,"r");
        $site_content="";
        while(!feof($site_fd)){
            $site_content.=fread($site_fd,1024);
        }
        /*利用正则表达式得到图片链接*/
        $reg_tag='//';
        $ret=preg_match_all($reg_tag,$site_content,$match_result);
        fclose($site_fd);
        return $match_result[1];
    }
    /* 对图片链接进行修正 */
    function revise_site($site_list,$base_site){
        foreach($site_list as $site_item){
            if(preg_match('/^http/',$site_item)){
                $return_list[]=$site_item;
            }else{
                $return_list[]=$base_site."/".$site_item;
            }
        }
        return $return_list;
    }
    /*得到图片名字，并将其保存在指定位置*/
    function get_pic_file($pic_url_array,$pos){
        $reg_tag='/.*\/(.*?)$/';
        $count=0;
        foreach($pic_url_array as $pic_item){
            $ret=preg_match_all($reg_tag,$pic_item,$t_pic_name);
            $pic_name=$pos.$t_pic_name[1][0];
            $pic_url=$pic_item;
            print("缓存图片 ".$pic_url." ");
            $img_read_fd=fopen($pic_url,"r");
            $img_write_fd=fopen($pic_name,"w");
            $img_content="";
            while(!feof($img_read_fd)){
                $img_content.=fread($img_read_fd,1024);
            }
            fwrite($img_write_fd,$img_content);
            fclose($img_read_fd);
            fclose($img_write_fd);
            print("[OK]
");
        }
        return 0;
    }
    function main(){
        /* 待抓取图片的网页地址 */
        $site_name="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=gQGx8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAySGJCS2htOFJkTmgxMDAwME0wM2cAAgTct9JbAwQAAAAA";
        $img_url=$this->get_img_url($site_name);
        $img_url_revised=$this->revise_site($img_url,$site_name);
        $img_url_unique=array_unique($img_url_revised);//uniquearray
        $this->get_pic_file($img_url_unique,"images/");
    }
    public function bb(){
        $row = $this->main();
        if($row){
            echo "成功";
        }
        else {
            echo "失败";
        }
    }


    public function cc(){
        $row = fopen("https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=gQGx8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAySGJCS2htOFJkTmgxMDAwME0wM2cAAgTct9JbAwQAAAAA","w");

    }
    function createPNG($im){
        header('Content-type:image/png');
        //$im = imageCreate(40,20);
        imageColorAllocate($im,0,0,0);
        $col_white = imageColorAllocate($im,255,255,255);
        imageString($im,5,2,2,'1234',$col_white);
        imagePNG($im);
        imageDestroy($im);
    }
    public function create(){
        $img =  '<img style="-webkit-user-select: none;cursor: zoom-in;" src="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=gQGx8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAySGJCS2htOFJkTmgxMDAwME0wM2cAAgTct9JbAwQAAAAA" width="386" height="386">';
        //$img = imagecreate(400, 400);
        //imagecolorallocate($img, 255, 255,255);
        $im = imagecreatefrompng($img);
        imagepng($im);
        echo $im;
    }
    function extract_attrib($tag) {
    preg_match_all('/(id|alt|title|src)=("[^"]*")/i', $tag, $matches);
    $ret = array();
    foreach($matches[1] as $i => $v) {
        $ret[$v] = $matches[2][$i];
    }
    return $ret;
}
    public function dd(){
        $tag = $this->create();
        $this->extract_attrib($tag);

        $img_tag = '<img id="logo" title="码农小兵logo" src="http://www.devdo.net/wp-content/uploads/2015/06/2015-06-02.jpg" alt="码农小兵" />';

        $atts = extract_attrib($img_tag);
        print_r($atts);
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
    //根据ticket获取二维码
    /**
     * @param int|string $content qrcode内容标识
     * @param [type] $file 存储为文件的地址，如果null直接输出
     * @param integer $type 类型
     * @param integer $expire 如果是临时，标识有效期
     * @return  [type]
     */

    public function getQRCode($file){
        //获取ticket
        $ticket = "gQGx8TwAAAAAAAAAAS5odHRwOi8vd2VpeGluLnFxLmNvbS9xLzAySGJCS2htOFJkTmgxMDAwME0wM2cAAgTct9JbAwQAAAAA";
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
    public function save(){
        $this->getQRCode("img/100001.png");
    }

}
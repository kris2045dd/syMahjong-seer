<?php
// 本类由系统自动生成，仅供测试用途
namespace Home\Controller;
use Think\Controller;
header("Content-type:text/html;charset=utf8");
// 指定允许其他域名访问
header('Access-Control-Allow-Origin:*');
// 响应类型
header('Access-Control-Allow-Methods:POST');
// 响应头设置
header('Access-Control-Allow-Headers:x-requested-with,content-type');
class IndexController extends Controller {
    //馆主后台登陆接口
    public function index1(){
        $phone = I('post.phone');//手机号码
        $pwd = I('post.code');//密码
        /*$phone = "13200231520";
        $pwd = "a";*/
        $row = M("user")->where("phone='$phone' and password='$pwd'")->select();
        //echo "<pre>";var_dump($row);
        if(empty($row)){
            $array['status']=-1;
            //$status = -1;
            //echo json_encode("-1");
        }
        else {
            $array['status']=1;
            //$status = 1;
            //echo json_encode("1");
        }
        echo json_encode($array);
    }
    public function index(){
        $phone = I('post.phone');//手机号码
        $pwd = I('post.code');//密码
        /*$phone = "13200231520";
        $pwd = "a";*/
        $row = M("user")->where("phone='$phone' and password='$pwd'")->select();
        //echo "<pre>";var_dump($row);
        if(empty($row)){
            $array['status']=-1;
            //$status = -1;
            //echo json_encode("-1");
        }
        else {
            $array['status']=1;
            //$status = 1;
            //echo json_encode("1");
        }
        echo json_encode($array);
    }

    //短信验证接口


    //得到手机号码是否在数据库中存在
    public function getphone(){
        $phone = I('post.phone');
        $row = M("user")->where("phone='$phone'")->select();
        if(empty($row)){
            $array['status']=-1;
        }
        else{
            $array['status']=1;
        }
        echo json_encode($array);
    }
    //手机号以及手机验证码是否输入正确
    /*
     * 第一:先判断手机号输入的是否存在在数据库中
     * 第二:再判断验证码与手机号收到的验证码是否对应
     * */
    public function phoneCode(){
        $phone = I('post.phone');//手机号码
        $code = I('post.code');//验证码
        $row = M("user")->where("phone='$phone'")->select();
        if(empty($row)){ //输入的手机号码在数据库中不存在
            $array['status']=0;
        }
        else{
            if($code=="123abc"){ //所有的输入正确
                $array['status']=1;
            }
            else{ //手机号码正确,但手机号码验证码输入不正确
                $array['status']=2;
            }
        }
        echo json_encode($array);
    }
    //查看原密码是否存在在数据库中
    public function pwd(){
        $phone = I('post.phone');
        $newpwd = I('post.newpwd');
        $row = M('user')->where("phone='$phone'")->select();
        $getpwd = $row[0]['password'];
        if($newpwd==$getpwd){ //新密码不能与原密码输入一样
            $array['status']=-1;
        }
        else { //新密码输入正确
            $data['password'] = $newpwd;
            M("user")->where("phone='$phone'")->save($data);
            $array['status']=1;
        }
        echo json_encode($array);
    }
    //检测输入的新密码与原密码不能重复
    //密码找回接口
    public function pwdRecovery(){

    }
    public function test1(){
        $aa = $_POST['aasdf'];
        if($aa==1){
            echo json_encode("1");
        }
        else {
            echo json_encode("-1");
        }
    }
}
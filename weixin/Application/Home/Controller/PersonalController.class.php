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
class PersonalController extends Controller {
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
    //馆主后台个人中心接口
    //获取用户真实姓名接口
    public function index(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $real_name = $row[0]['real_name'];
        echo $this->json_encode($real_name);
    }
    //获取游戏昵称接口
    public function index1(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $wx_name = $row[0]['wx_name'];
        echo $this->json_encode($wx_name);
    }
    //获取游戏uid接口
    public function index2(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        echo $this->json_encode($uid);
    }
    //获取茶馆名称接口
    public function index3(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $tea_house_name = $row[0]['tea_house_name'];
        echo $this->json_encode($tea_house_name);
    }

    //下面是展示银行卡信息的接口
    //真实姓名已经得到了,现在只需要得到银行卡姓名和银行卡卡号和银行信息【开户行地址】
    //银行卡姓名
    public function bankUsername(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $bank_username = $row[0]['bank_username'];
        echo $this->json_encode($bank_username);
    }
    //银行卡卡号
    public function bankCard (){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $bank_card = $row[0]['bank_card'];
        echo $this->json_encode($bank_card);
    }
    //得到开户行地址信息
    public function bankAddress(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $bank_address = $row[0]['bank_address'];
        echo $this->json_encode($bank_address);
    }
    //保存银行卡信息内容
    public function bankSave(){
        $phone = I('post.phone');
        $data['bank_card'] = I('post.bank_card');
        $data['bank_address'] = I('post.bank_address');
        $data['bank_username'] = I('post.bank_username');
        $data['cipher'] = I('post.cipher');
        /*$data['bank_card'] = "121313";
        $data['bank_address'] = "成都市青羊区";
        $data['bank_username'] = "银行啊";*/
        $row = M("user_list")->where("phone_number='13200231520'")->save($data);
        if($row=true){
            $array['status']=1;//保存成功
        }
        else {
            $array['status']=-1;//保存失败
        }
        echo json_encode($array);
    }
    //推广收益
    public function promotionIncome(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $rowss = M("profit_log")
                 ->field("SUM(self_profit+pre_profit+pre_pre_profit) as sum")
                 ->where("uid='$uid'")
                 ->select();
        $sum = $rowss[0]['sum'];
        if(empty($sum)){
            echo $this->json_encode("0.00");
        }
        else {
            echo $this->json_encode($sum);
        }
    }

    //查看该用户是否已经绑定过该银行卡
    public  function bind(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $bank_card = $row[0]['bank_card'];
        if(empty($bank_card)){
            $array['status']=0;//该用户没有绑定银行卡
        }
        else {
            $array['status']=1;//该用户已经绑定过银行卡
        }
        echo json_encode($array);
    }

}
<?php
// 我的推广员
namespace Home\Controller;
use Think\Controller;
header('Content-type:text/html;charset=UTF-8');
// 指定允许其他域名访问
header('Access-Control-Allow-Origin:*');
// 响应类型
header('Access-Control-Allow-Methods:POST');
// 响应头设置
header('Access-Control-Allow-Headers:x-requested-with,content-type');
class PayPwdController extends Controller {
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

    /*
     * 二位数组取最小值pv
     */
    function getArrayMax($arr,$field)
    {
        foreach ($arr as $k => $v) {
            $temp[] = $v[$field];
        }
        return min($temp);
    }
    //输入支付密码提现
    public function index(){
        $phone = I('post.phone');
        $pwdInput = I('post.pwdInput');
        //$pwdInput = "117111";
        //提现金额
        $tixainmoney = I('post.tixainmoney');
        //提现前的总额
        $totalFront = I('post.totalFront');
        //余额=提现前的总额-提现金额
        $balance = $totalFront-$tixainmoney;
        //银行卡类型
        $bank_card_type = I('post.bank_card_type');
        $row = M("user_list")->where("phone_number='13200231520'")->select(); //cipher
        $pwd = $row[0]['cipher'];
        if($pwdInput==$pwd){ //输入正确
            $data['uid'] = $row[0]['uid'];
            $data['bank_card_type'] = $bank_card_type;
            $data['card_number'] = $row[0]['bank_card'];
            $data['presentation_time'] = date("Y-m-d H:i:s");
            $data['cash_amount'] = $tixainmoney;
            $data['present_status'] = 0;
            $data['total'] = $totalFront;
            $data['balance'] = $balance;
            $rowadd = M("bank_katti")->add($data);
            $array['status']=1;
        }
        else { //输入的值不相等 totalFront accountBalance
            $array['status']=0;
        }
        echo json_encode($array);
    }
    //账户余额
    public function accountBalance(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $rows = M("bank_katti")->field("balance")->where("uid='$uid'")->select();
        //$balance = $rows[0]['balance'];
        $Maximum = $this->getArrayMax($rows,'balance');
        echo $this->json_encode($Maximum);
    }
    //全部提现
    public function fullPresentation(){

    }
}
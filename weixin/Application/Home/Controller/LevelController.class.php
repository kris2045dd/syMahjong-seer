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
class LevelController extends Controller {
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
    //茶馆名称
    public function teaname(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $teaname = $row[0]['tea_house_name'];
        echo $this->json_encode($teaname);
    }
    //等级
    public function level(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $level = $row[0]['proxy_lv'];
        if($level==0){
            echo $this->json_encode("普通玩家");
        }
        else if($level==1){
            echo $this->json_encode("普通玩家");
        }
        else if($level==2){
            echo $this->json_encode("金豆玩家");
        }
        else if($level==3){
            echo $this->json_encode("初级推广员");
        }
        else if($level==4){
            echo $this->json_encode("中级推广员");
        }
        else if($level==5){
            echo $this->json_encode("高级推广员");
        }
        //echo $this->json_encode($teaname);
    }
    //发展x名金贝玩家人数
    public function beans(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $count = M("user_list")->where("pre_uid='$uid' and (proxy_lv=2)")->count();
        echo $this->json_encode($count);
    }
    //发展x元收益
    public function profit(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $sum = M("user_list")->field("sum(profit+pre_profit+pre_pre_profit) as sum")->where("pre_uid='$uid'")->select();
        $sum = $sum[0]['sum'];
        echo $this->json_encode($sum);
    }
    //点击升级按钮
    /*
     * 1、修改user_list表中的proxy_lv字段
     * 2、查询状态字段status的值
     * */
    public function upgrade(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $proxy_lv = $row[0]['proxy_lv'];
        /*$data['proxy_lv'] = ;
        $rows = M("user_list")->where()->save($data);*/
        if($proxy_lv>=5){ //该玩家已经是最高等级的了,不能再向上升级了
            $array['status']=-1;
        }
        else {
            $data['proxy_lv'] = $proxy_lv+1;
            $rows = M("user_list")->where("uid='$uid'")->save($data);
            if($rows=true){ //升级成功
                $array['status']=1;
            }
            else { //升级失败
                $array['status']=0;
            }
        }
        echo json_encode($array);
    }
    //发展的正式推广员或馆主数量
    public function e(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $uid = $row[0]['uid'];
        $count = M("user_list")->where("pre_uid='$uid' and (proxy_lv=4 or proxy_lv=5)")->count();
        echo $this->json_encode($count);
    }
    //是否显示待审核按钮
    public function ee(){
        $phone = I('post.phone');
        $row = M("user_list")->where("phone_number='13200231520'")->select();
        $proxy_lv = $row[0]['proxy_lv'];
        echo $this->json_encode($proxy_lv);
    }
}
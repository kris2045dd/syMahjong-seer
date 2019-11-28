<?php
namespace Home\Model;
use Think\Model;
class Wx5Model extends Model{
    protected $connection = 'DB_CONFIG3';
    public function list1(){
        $list = M('user_list','','DB_CONFIG3');
        return $list;
    }
}
?>
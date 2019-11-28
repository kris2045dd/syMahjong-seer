<?php
namespace Home\Model;
use Think\Model;
class HeaderModel extends Model{
    protected $connection = 'DB_CONFIG2';
    public function header(){
        $list = M('header','t_','DB_CONFIG2');
        return $list;
    }
}
?>
<?php
namespace Home\Model;
use Think\Model;
class Header1Model extends Model{
    protected $connection = 'DB_CONFIG3';
    public function header(){
        $list = M('header','','DB_CONFIG3');
        return $list;
    }
}
?>
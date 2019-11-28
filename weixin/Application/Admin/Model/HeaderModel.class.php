<?php
namespace Admin\Model;
use Think\Model;
class HeaderModel extends Model{
    protected $connection = 'DB_CONFIG4';
    public function header(){
        $list = M('header','t_','DB_CONFIG4');
        return $list;
    }
}
?>
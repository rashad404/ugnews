<?php
namespace Models;
use Core\Model;
use Helpers\Cookie;

class CartModel extends Model{

    private static $tableName = 'cart';

    public function __construct(){

    }

    public static function add($id, $quantity){
        $unique_id = Cookie::get('uniqueId');
        $check = self::$db->selectOne("SELECT `id`,`count` FROM ".self::$tableName." WHERE `unique_id`=:unique_id AND `product_id`=:id", [':unique_id'=> $unique_id, ':id'=> $id]);
        if(!$check) {
            $sql_data = ['product_id' => $id, 'unique_id' => $unique_id, 'count' => $quantity];
            self::$db->insert(self::$tableName, $sql_data);
            echo 1;
        }else{
            $sql_data = ['count' => $check['count']+$quantity];
            $sql_where = ['product_id' => $id, 'unique_id' => $unique_id];
            self::$db->update(self::$tableName, $sql_data, $sql_where);
            echo  1;
        }
    }

    public static function update($id, $quantity){
        $unique_id = Cookie::get('uniqueId');
        $sql_data = ['count' => $quantity];
        $sql_where = ['product_id' => $id, 'unique_id' => $unique_id];
        self::$db->update(self::$tableName, $sql_data, $sql_where);
        echo  1;
    }

    public static function delete($id){
        $unique_id = Cookie::get('uniqueId');
        $sql_where = ['product_id' => $id, 'unique_id' => $unique_id];
        self::$db->delete(self::$tableName, $sql_where);
        echo  1;
    }

    public static function countItems(){
        $unique_id = Cookie::get('uniqueId');
        $count = self::$db->selectOne("SELECT SUM(`count`) as count_item FROM ".self::$tableName." WHERE `unique_id`=:unique_id", [':unique_id'=> $unique_id]);
        if(!empty($count['count_item'])) {
            return $count['count_item'];
        }else{
            return 0;
        }
    }

    public static function getList(){
        $unique_id = Cookie::get('uniqueId');
        $list = self::$db->select("SELECT c.`id`,c.`count`,c.`product_id`,p.`thumb`,p.`title_".self::$def_language."`,p.`text_".self::$def_language."`,p.`price` FROM ".self::$tableName." c INNER JOIN `products` p ON c.`product_id` = p.`id` WHERE c.`unique_id`=:unique_id", [':unique_id'=> $unique_id]);
        return $list;
    }
    public static function getTotalPrice(){
        $unique_id = Cookie::get('uniqueId');
        $list = self::$db->select("SELECT c.`count`, p.`price` FROM ".self::$tableName." c INNER JOIN `products` p ON c.`product_id` = p.`id` WHERE c.`unique_id`=:unique_id", [':unique_id'=> $unique_id]);
        $total = 0;
        foreach ($list as $item){
            $sub_total = $item['count'] * $item['price'];
            $total += $sub_total;
        }
        return $total;
    }

}

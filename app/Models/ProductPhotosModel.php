<?php
namespace Models;
use Core\Model;

class ProductPhotosModel extends Model{

    private static $tableName = 'product_photos';

    public static function getAll($product_id)
    {
        $rows = self::$db->select("SELECT * FROM `".self::$tableName."` WHERE `product_id` = :product_id ", [':product_id' => $product_id]);
        return $rows;
    }


}

<?php
namespace Models;
use Core\Model;

class TextsModel extends Model{

    public static $list = [];

    public function __construct(){
        self::$list = self::getList();
    }

    public static function getList(){
        $array = self::$db->select("SELECT `id`, `text_".self::$def_language."` FROM `texts`");
        $new_array = [];
        foreach ($array as $item) {
            $new_array[$item['id']] = html_entity_decode($item["text_".self::$def_language]);
        }
        return $new_array;
    }

    public static function getText($id, $title=""){
        if(array_key_exists($id, self::$list)){
            return self::$list[$id];
        }else{
            return 'Not found';
        }
    }
}

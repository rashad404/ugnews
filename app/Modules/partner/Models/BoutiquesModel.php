<?php

namespace Modules\partner\Models;

use Core\Model;
use Models\LanguagesModel;
use Helpers\Security;
use Helpers\Validator;

class BoutiquesModel extends Model{

    private $defLang;

    private static $rules = [
        'name' => ['min_length(3)','max_length(50)'],
        'source_url' => ['min_length(30)','max_length(250)'],
    ];
    private static function naming(){
        return include SMVC.'app/language/'.self::$def_language.'/naming.php';
    }

    protected static function getPost()
    {
        extract($_POST);
        $skip_list = ['csrf_token','submit'];
        foreach($_POST as $key=>$value){
            if (in_array($key, $skip_list)) continue;
            $array[$key] = Security::safe($_POST[$key]);
        }
        return $array;
    }

    public function __construct(){
        parent::__construct();
        $this->defLang = LanguagesModel::defaultLanguage('partner');
    }

    public static function getList($params){
        $row = self::$db->select("SELECT `id`,`name`,`status` FROM `".$params['name']."` ORDER BY `position`,`id` DESC");
        return $row;
    }

    public static function getItem($params, $id){
        $array = self::$db->selectOne("SELECT `id`,`name`,`source_url`,`status`,`position` FROM `".$params['name']."` WHERE `id`='".$id."' ORDER BY `id` DESC");
        return $array;
    }

    public static function create($params){
        $return = [];
        $post_data = self::getPost();
        $return['postData'] = $post_data;

        $validator = Validator::validate($post_data, self::$rules, self::naming());
        if ($validator->isSuccess()) {
            $return['errors'] = null;
            $mysql_data = $post_data;
            self::$db->insert( $params['name'], $mysql_data);
        }else{
            $return['errors'] = implode('<br/>',array_map("ucfirst", $validator->getErrors()));
        }
        return $return;
    }

    public static function update($params, $id){
        $return = [];
        $post_data = self::getPost();
        $return['postData'] = $post_data;

        $validator = Validator::validate($post_data, self::$rules, self::naming());
        if ($validator->isSuccess()) {
            $return['errors'] = null;
            $mysql_data = $post_data;
            $mysql_where = ['id'=>$id];
            self::$db->update( $params['name'], $mysql_data, $mysql_where);
        }else{
            $return['errors'] = implode('<br/>',array_map("ucfirst", $validator->getErrors()));
        }
        return $return;
    }

    public static function delete($params, $id){
        $mysql_where = ['id'=>$id];
        self::$db->delete( $params['name'], $mysql_where);

        $mysql_where_products = ['boutique_id'=>$id];
        self::$db->delete( 'products', $mysql_where_products, 100000);
    }

    public static function status($params, $id){

        $info = self::getItem($params, $id);
        $status = $info["status"]==1?0:1;

        $mysql_data = ['status'=>$status];
        $mysql_where = ['id'=>$id];
        self::$db->update( $params['name'], $mysql_data, $mysql_where);
    }

    public static function move($params, $id, $action){

        $info = self::getItem($params, $id);

        if($action=='up'){
            $new_position = $info['position']+1;
        }else{
            $new_position = $info['position']-1;
        }

        $mysql_data = ['position'=>$new_position];
        $mysql_where = ['id'=>$id];
        self::$db->update( $params['name'], $mysql_data, $mysql_where);
    }



    public static function searchList($params, $text, $columns){
        $where_sql = 'WHERE ';
        foreach($columns as $column){
            $where_sql .= "`".$column."` LIKE '%".$text."%' OR ";
        }
        $where_sql = substr($where_sql,0,-3);
        $row = self::$db->select("SELECT `id`,`name`,`status` FROM `".$params['name']."` ".$where_sql." ORDER BY `position` DESC");
        return $row;
    }
}
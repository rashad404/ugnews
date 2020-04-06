<?php

namespace Modules\admin\Models;

use Core\Model;
use Helpers\Security;
use Helpers\Validator;

class RoomsModel extends Model{

    public static $tableName = 'apt_rooms';
    private static $rules;

    public function __construct(){
        parent::__construct();
        self::$rules = [
            'name_'.self::$def_language => ['min_length(3)', 'max_length(15)'],
        ];
    }

    public static function rules()
    {
        return [
            'name_'.self::$def_language => ['required',],
        ];
    }



    public static function naming()
    {
        return [

        ];
    }

    protected static function getPost()
    {
        extract($_POST);
        $skip_list = ['csrf_token'];
        $array = [];
        foreach($_POST as $key=>$value){
            if (in_array($key, $skip_list)) continue;
            $array[$key] = Security::safe($_POST[$key]);
        }
        return $array;
    }


    public function add($apt_id){
        $return = [];
        $return['errors'] = null;
        $post_data = $this->getPost();
        $validator = Validator::validate($post_data, self::$rules, self::naming());
        if ($validator->isSuccess()) {
            $return['errors'] = null;
        }
        $insert_data = $post_data;
        $insert_data['apt_id'] = $apt_id;
        $insert_data['status'] = 1;
        $insert_id = self::$db->insert(self::$tableName,$insert_data);
        return $return;
    }

    public static function getList($apt_id){
        return self::$db->select("SELECT `id`,`status`,`name_".self::$def_language."` as `name` FROM ".self::$tableName." WHERE `apt_id`='".$apt_id."' ORDER BY `position` DESC,`id` ASC ");
    }
    public static function getName($id){
        $query = self::$db->selectOne("SELECT `name_".self::$def_language."` as `name` FROM ".self::$tableName." WHERE `id`='".$id."'");
        return $query['name'];
    }

    public static function delete($id_array = []){
        if(empty($id_array)) {
            $id_array = $_POST["row_check"];
        }
        $ids = Security::safe(implode(",", $id_array));
        self::$db->raw("DELETE FROM ".self::$tableName." where `id` in (".$ids.")");
    }
    public static function statusToogle($id){
        $query = self::$db->selectOne("SELECT `status` FROM ".self::$tableNameRooms." WHERE `id`='".$id."'");
        $old_status = $query['status'];
        if($old_status==0){
            $status = 1;
        }else{
            $status = 0;
        }

        self::$db->raw("UPDATE ".self::$tableName." SET `status`='".$status."' WHERE `id` ='".$id."'");
    }

    public static function status($status){
        $row_check = $_POST["row_check"];
        $ids = Security::safe(implode(",",$row_check));
        self::$db->raw("UPDATE ".self::$tableName." SET `status`='".$status."' WHERE `id` in (".$ids.")");
    }

    public static function getNameByBeds($beds){
        if($beds==1)$name = 'Private';
        if($beds==2)$name = 'Double';
        if($beds==4)$name = 'Quad';
        return $name;
    }
}

?>
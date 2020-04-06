<?php

namespace Modules\admin\Models;

use Core\Model;
use Helpers\Security;
use Helpers\Validator;
use Modules\admin\Models\TenantsModel;

class ParkingsModel extends Model{

    private static $tableName = 'apt_parkings';
    private static $tableNameApts = 'apartments';

    private static $rules;
    private static $params;

    public function __construct($params){
        parent::__construct();
        self::$rules = [
            'spot' => ['min_length(1)', 'max_length(30)'],
            'price' => ['min_length(2)', 'max_length(8)', 'positive'],
        ];
        self::$db->createTable(self::$tableName,self::getInputs());
        self::$params = $params;
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

    public static function getInputs($id=''){
        $array[] = ['type'=>'text',   'name'=>'Spot number', 'index'=>true,     'key'=>'spot',            'sql_type'=>'varchar(10)'];
//        $array[] = ['type'=>'text',         'name'=>'Name', 'index'=>true,       'key'=>'name',        'sql_type'=>'varchar(100)'];
        $array[] = ['type'=>'text',             'name'=>'Price', 'index'=>true,                 'key'=>'price',            'sql_type'=>'decimal(10,2)'];
        $array[] = ['type'=>'',             'name'=>'', 'index'=>false,                 'key'=>'status',            'sql_type'=>'tinyint(1)'];
        $array[] = ['type'=>'',             'name'=>'', 'index'=>false,                 'key'=>'position',          'sql_type'=>'int(3)'];
        $array[] = ['type'=>'',             'name'=>'Apartment', 'index'=>true,                 'key'=>'apt_id',            'sql_type'=>'int(5)'];
        $array[] = ['type'=>'text',   'name'=>'Gate Location', 'index'=>false,     'key'=>'location',            'sql_type'=>'varchar(100)'];
        $array[] = ['type'=>'text',   'name'=>'Remote Control', 'index'=>true,     'key'=>'remote',            'sql_type'=>'varchar(100)'];
        $array[] = ['type'=>'select2',   'name'=>'Tenant', 'index'=>true,     'key'=>'tenant_id',            'sql_type'=>'int(5)', 'data'=>self::getTenants()];
        return $array;
    }

    public static function getSqlFields(){
        $input_list = self::getInputs();
        $field_array = ['`id`'];
        foreach ($input_list as $value){
            $field_array[] = '`'.$value['key'].'`';
        }
        $fields = implode(',', $field_array);
        return $fields;
    }

    public static function getTenants(){
        $list = TenantsModel::getList('LIMIT 0,100000');
        $data[] = ['key'=>'','name'=>'---','disabled'=>''];
        foreach ($list as $item){
            $data[] = ['key'=>$item['id'], 'name'=>$item['first_name'].' '.$item['last_name'].' ['.$item['phone'].']','disabled'=>''];
        }
        return $data;
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


    public static function add($apt_id){
        $return = [];
        $return['errors'] = null;
        $post_data = self::getPost();
        $validator = Validator::validate($post_data, self::$rules, self::naming());
        if ($validator->isSuccess()) {
            $return['errors'] = null;
            $insert_data = $post_data;
            $insert_data['apt_id'] = $apt_id;
            $insert_data['status'] = 1;
            $insert_id = self::$db->insert(self::$tableName,$insert_data);
            if($insert_id>0){
                self::updatePosition($insert_id);
            }
        }else{
            $return['errors'] = implode('<br/>',array_map("ucfirst", $validator->getErrors()));
        }
        return $return;
    }

    public static function update($id){
        $return = [];
        $return['errors'] = null;
        $post_data = self::getPost();
        $validator = Validator::validate($post_data, self::$rules, self::naming());
        if ($validator->isSuccess()) {
            $return['errors'] = null;
            $update_data = $post_data;
            self::$db->update(self::$tableName,$update_data, ['id'=>$id]);
        }else{
            $return['errors'] = implode('<br/>',array_map("ucfirst", $validator->getErrors()));
        }

        return $return;
    }
    public static function getItem($id){
        return self::$db->selectOne("SELECT ".self::getSqlFields()." FROM ".self::$tableName." WHERE `id`='".$id."'");
    }


    public static function getList(){
        return self::$db->select("SELECT ".self::getSqlFields()." FROM ".self::$tableName." WHERE `status`=1 ORDER BY `position` DESC,`id` ASC ");
    }

    public static function getListByApt($id){
        return self::$db->select("SELECT ".self::getSqlFields()." FROM ".self::$tableName." WHERE `status`=1 AND `apt_id`='".$id."' ORDER BY `position` DESC,`id` ASC ");
    }

    public static function getRoomList($apt_id){
        return self::$db->select("SELECT `id`,`status`,`name_".self::$def_language."` FROM ".self::$tableNameRooms." WHERE `apt_id`='".$apt_id."' ORDER BY `position` DESC,`id` ASC ");
    }


    public static function search(){
        $postData = self::getPost();
        $text = $postData['search'];
        $values = self::$params['searchFields'];

        $sql_s = ''; // Stop errors when $words is empty
        if($values<=1){
            $sql_s = "`".$values."` LIKE '%".$text."%' ";
        } else {
            foreach($values as $value){
                $sql_s .= "`".$value."` LIKE '%".$text."%' OR ";
            }
            $sql_s = substr($sql_s,0,-3);
        }
        $list = self::$db->select("SELECT ".self::getSqlFields()." FROM `".self::$tableName."` WHERE ".$sql_s);
        return $list;
    }
    public static function delete($id_array = []){
        if(empty($id_array)) {
            $id_array = $_POST["row_check"];
        }
        $ids = Security::safe(implode(",", $id_array));
        self::$db->raw("DELETE FROM ".self::$tableName." where `id` in (".$ids.")");
    }
    public static function statusToggle($id){
        $query = self::$db->selectOne("SELECT `status` FROM ".self::$tableName." WHERE `id`='".$id."'");
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
    public static function move($id, $type){
        $query = self::$db->selectOne("SELECT `position` FROM ".self::$tableName." WHERE `id`='".$id."'");
        $old_position = $query['position'];
        if($type=='up'){
            $position = $old_position+1;
        }else{
            $position = $old_position-1;
        }
        self::$db->raw("UPDATE ".self::$tableName." SET `position`='".$position."' WHERE `id` ='".$id."'");
    }
    public static function updatePosition($id){
        $query = self::$db->selectOne("SELECT `position` FROM ".self::$tableName." ORDER BY `position` DESC");
        $max_position = $query['position'];
        $position = $max_position+1;
        self::$db->raw("UPDATE ".self::$tableName." SET `position`='".$position."' WHERE `id` ='".$id."'");
    }

}

?>
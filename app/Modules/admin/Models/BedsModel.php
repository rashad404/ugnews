<?php

namespace Modules\admin\Models;

use Core\Model;
use Helpers\Console;
use Helpers\Security;
use Helpers\Validator;
use Modules\admin\Models\RoomsModel;

class BedsModel extends Model{

    private static $tableName = 'apt_beds';
    private static $tableNameRooms = 'apt_rooms';
    private static $tableNameTenants = 'tenants';

    private static $rules;
    private static $params;

    public function __construct($params){
        parent::__construct();
        self::$rules = [
            'name_'.self::$def_language => ['min_length(1)', 'max_length(30)'],
            'price' => ['min_length(2)', 'max_length(8)', 'positive'],
            'room_id' => ['min_length(1)', 'max_length(4)', 'positive', 'no_zero'],
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
        $array[] = ['type'=>'text',         'name'=>'Name', 'index'=>true,       'key'=>'name_en',        'sql_type'=>'varchar(100)'];
        $array[] = ['type'=>'',         'name'=>'',         'index'=>false, 'key'=>'name_ru',         'sql_type'=>'varchar(100)'];
        $array[] = ['type'=>'',         'name'=>'',       'index'=>false, 'key'=>'name_az',       'sql_type'=>'varchar(100)'];
        $array[] = ['type'=>'text',             'name'=>'Price', 'index'=>true,                 'key'=>'price',            'sql_type'=>'decimal(10,2)'];
        $array[] = ['type'=>'',             'name'=>'', 'index'=>false,                 'key'=>'status',            'sql_type'=>'tinyint(1)'];
        $array[] = ['type'=>'',             'name'=>'', 'index'=>false,                 'key'=>'position',          'sql_type'=>'int(3)'];
        $array[] = ['type'=>'',             'name'=>'', 'index'=>false,                 'key'=>'apt_id',            'sql_type'=>'int(5)'];
        $array[] = ['type'=>'select2',             'name'=>'Room', 'index'=>true,                 'key'=>'room_id',           'sql_type'=>'int(5)','data'=>self::getRooms($id)];
        $array[] = ['type'=>'select2',   'name'=>'Tenant', 'index'=>true,     'key'=>'tenant_id',            'sql_type'=>'int(5)','data'=>self::getTenants()];
        $array[] = ['type'=>'date',         'name'=>'Bed availability date',    'index'=>false,    'key'=>'available_date',          'sql_type'=>'varchar(20)'];
        $array[] = ['type'=>'text',         'name'=>'Apply link',       'index'=>false, 'key'=>'apply_link',       'sql_type'=>'varchar(200)'];
        return $array;
    }

    public static function getRooms($apt_id){
        $room_list = RoomsModel::getList($apt_id);
        $new_list[] = ['key'=>0,'name'=>'Select Room','disabled'=>''];
        foreach ($room_list as $item){
            $new_list[] = ['key'=>$item['id'],'name'=>$item['name'],'disabled'=>''];
        }
        return $new_list;
    }

    public static function getTenants(){
        $room_list = TenantsModel::getList();
        $new_list[] = ['key'=>0,'name'=>'No Tenant','disabled'=>''];
        foreach ($room_list as $item){
            $new_list[] = ['key'=>$item['id'],'name'=>$item['first_name'].' '.$item['last_name'],'disabled'=>''];
        }
        return $new_list;
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


            $old_data = self::getItem($id);
            self::$db->update(self::$tableName,$update_data, ['id'=>$id]);

            //Update tenant table;
            if  ($old_data['tenant_id']!=$post_data['tenant_id']){
                self::$db->update(self::$tableNameTenants,['apt_id'=>0,'room_id'=>0,'bed_id'=>0], ['id'=>$old_data['tenant_id']]);
                self::$db->update(self::$tableNameTenants,['apt_id'=>$old_data['apt_id'], 'room_id'=>$old_data['room_id'], 'bed_id'=>$old_data['bed_id'], ], ['id'=>$post_data['tenant_id']]);
            }

        }else{
            $return['errors'] = implode('<br/>',array_map("ucfirst", $validator->getErrors()));
        }

        return $return;
    }
    public static function getItem($id){
        return self::$db->selectOne("SELECT `id`,`apt_id`,`room_id`,`tenant_id`,`available_date`,`name_".self::$def_language."`,`status`,`price`,`apply_link` FROM ".self::$tableName." WHERE `id`='".$id."'");
    }

    public static function getList($apt_id){
        return self::$db->select("SELECT a.`id`,a.`room_id`,a.`tenant_id`,a.`status`,a.`name_".self::$def_language."`,a.`price`, a.`available_date`, b.`name_".self::$def_language."` as `room_name` 
         FROM ".self::$tableName." as a INNER JOIN ".self::$tableNameRooms." as b ON a.`room_id`=b.`id` 
          WHERE a.`apt_id`='".$apt_id."' ORDER BY a.`position` ASC, a.`id` ASC ");
    }
    public static function getListNotice(){
        return self::$db->select("SELECT a.`id`,a.`room_id`,a.`apt_id`,a.`tenant_id`,a.`status`,a.`name_".self::$def_language."`,a.`price`, a.`available_date`, b.`name_".self::$def_language."` as `room_name` 
         FROM ".self::$tableName." as a INNER JOIN ".self::$tableNameRooms." as b ON a.`room_id`=b.`id` 
          WHERE  
           a.`status`=1 AND a.`tenant_id`>0 AND a.`available_date`>'".date('Y-m-d')."'
          ORDER BY a.`available_date` ASC, a.`id` ASC 
        
          ");
    }
    public static function getListVacant(){
        return self::$db->select("SELECT a.`id`,a.`room_id`,a.`apt_id`,a.`tenant_id`,a.`status`,a.`name_".self::$def_language."` as `name`,a.`price`, a.`available_date`, b.`name_".self::$def_language."` as `room_name` 
         FROM ".self::$tableName." as a INNER JOIN ".self::$tableNameRooms." as b ON a.`room_id`=b.`id` 
          WHERE  
           a.`status`=1 AND a.`tenant_id`=0 
          ORDER BY FIELD(b.`name_en`, 'Private','Double 1', 'Double 2', 'Double 3', 'Quad')
          ");
    }

    public static function getListByRoom($room_id){
        return self::$db->select("SELECT `id`,`name_".self::$def_language."` as `name` 
         FROM ".self::$tableName." WHERE `room_id`='".$room_id."' ORDER BY `position` DESC, `id` ASC ");
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

    public static function getName($id){
        $query = self::$db->selectOne("SELECT `name_".self::$def_language."` as `name` FROM ".self::$tableName." WHERE `id`='".$id."'");
        return $query['name'];
    }
}

?>
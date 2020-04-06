<?php

namespace Modules\admin\Models;

use Core\Model;
use Helpers\Console;
use Helpers\Security;
use Helpers\FileUploader;
use Helpers\Url;
use Helpers\File;
use Helpers\Validator;

class ApartmentsModel extends Model{


    public static $tableName = 'apartments';
    public static $tableNameFeatures = 'apt_features';
    public static $tableNameLocations = 'apt_locations';
    public static $tableNameCategories = 'apt_categories';
    public static $tableNameRooms = 'apt_rooms';
    public static $tableNameBeds = 'apt_beds';
    public static $tableNameModels = 'apt_models';
    public static $tableNameRoomTypes = 'apt_room_types';
    public static $tableNamePhotos = 'apt_album';
    public static $safeMode = false;
    public static $positionOrderBy = 'DESC';
    public static $positionEnable = true;
    public static $positionCondition = false;
    public static $fields = [

    ];


    private static $rulesRooms;

    public function __construct(){
        parent::__construct();
        self::$rulesRooms = [
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


    public static function getList(){
        return self::$db->select("SELECT `id`,`title_".self::$def_language."`, `name`,`rent`,`address` FROM ".self::$tableName);
    }
    public static function getActiveList(){
        return self::$db->select("SELECT `id`,`title_".self::$def_language."`, `name`,`rent`,`address`,`category` FROM ".self::$tableName." WHERE `status`=1");
    }

    public static function getItem($id){
        return self::$db->selectOne("SELECT `id`,`title_".self::$def_language."`, `name`,`address`, `rent`,`utility`,`profit` FROM ".self::$tableName." WHERE `id`='".$id."'");
    }

    public static function getName($id){
        $query = self::$db->selectOne("SELECT `name` FROM ".self::$tableName." WHERE `id`='".$id."'");
        return $query['name'];
    }
    public static function getAddress($id){
        $query = self::$db->selectOne("SELECT `address` FROM ".self::$tableName." WHERE `id`='".$id."'");
        return $query['address'];
    }
    public static function getGenderName($id){
        $query = self::$db->selectOne("SELECT `category` FROM ".self::$tableName." WHERE `id`='".$id."'");
        return $query['category'];
    }
    public static function getLocationName($id){
        $query = self::$db->selectOne("SELECT `title_".self::$def_language."` as `name` FROM ".self::$tableNameLocations." WHERE `id`='".$id."'");
        return $query['name'];
    }
    public static function getRoomTypeName($id){
        $query = self::$db->selectOne("SELECT `name` FROM ".self::$tableNameRoomTypes." WHERE `id`='".$id."'");
        return $query['name'];
    }
    public static function getModelName($id){
        $query = self::$db->selectOne("SELECT `name` FROM ".self::$tableNameRoomTypes." WHERE `id`='".$id."'");
        return $query['name'];
    }

    public static function getFeatures(){
        return self::$db->select("SELECT `id`,`title_".self::$def_language."` FROM ".self::$tableNameFeatures." WHERE `status`=1 ORDER BY `position` DESC ");
    }

    public static function getLocations(){
        return self::$db->select("SELECT `id`,`title_".self::$def_language."` as `name` FROM ".self::$tableNameLocations." WHERE `status`=1 ORDER BY `position` DESC ");
    }

    public static function getCategories(){
        return self::$db->select("SELECT `id`,`title_".self::$def_language."` FROM ".self::$tableNameCategories." WHERE `status`=1 ORDER BY `position` DESC ");
    }
    public static function getModels(){
        return self::$db->select("SELECT `id`,`name_".self::$def_language."` as `name` FROM ".self::$tableNameModels." WHERE `status`=1 ORDER BY `position` DESC ");
    }
    public static function getRoomTypes(){
        return self::$db->select("SELECT `id`,`name` FROM ".self::$tableNameRoomTypes." WHERE `status`=1 ORDER BY `position` DESC ");
    }
    public static function getAlbum($apt_id){
        return self::$db->select("SELECT `id`,`status` FROM ".self::$tableNamePhotos." WHERE `apt_id`='".$apt_id."' ORDER BY `position` DESC ");
    }


    public function addAlbumPhoto($apt_id){
        $return = [];
        $return['errors'] = null;

        $insert_data = ['apt_id'=>$apt_id,'status'=>1];
        $insert_id = self::$db->insert(self::$tableNamePhotos,$insert_data);
        if (!empty($_FILES['file']['name'])) {

            $upload_dir = self::$tableNamePhotos.'/'.$insert_id;
            $upload_model = new FileUploader();
            $upload = $upload_model->imageUpload($insert_id,$upload_dir,'file',80, 1200, 800);
            $uploadThumb = $upload_model->imageUpload($insert_id,$upload_dir.'/thumb','file',80, 600, 400);
            if($upload['success']==0){
                $return['errors'] = $upload['error'];
            }
        }
        return $return;
    }

    public static function deleteAlbumPhoto($id_array = []){
        if(empty($id_array)) {
            $id_array = $_POST["row_check"];
        }
        $ids = Security::safe(implode(",", $id_array));

        foreach ($id_array as $id) {
            if(is_dir(Url::uploadPath().self::$tableNamePhotos.'/'.$id)) {
                File::rmDir(Url::uploadPath().self::$tableNamePhotos.'/'.$id);
            }
        }
        self::$db->raw("DELETE FROM ".self::$tableNamePhotos." where `id` in (".$ids.")");

    }
    public static function statusAlbumPhoto($status){
        $row_check = $_POST["row_check"];
        $ids = Security::safe(implode(",",$row_check));

        self::$db->raw("UPDATE ".self::$tableNamePhotos." SET `status`='".$status."' WHERE `id` in (".$ids.")");

    }

    public function addRoom($apt_id){
        $return = [];
        $return['errors'] = null;
        $post_data = $this->getPost();
        $validator = Validator::validate($post_data, self::$rulesRooms, self::naming());
        if ($validator->isSuccess()) {
            $return['errors'] = null;
        }
        $insert_data = $post_data;
        $insert_data['apt_id'] = $apt_id;
        $insert_data['status'] = 1;
        $insert_id = self::$db->insert(self::$tableNameRooms,$insert_data);
        return $return;
    }


    public static function getRooms($apt_id){
        return self::$db->select("SELECT `id`,`status`,`name_".self::$def_language."` FROM ".self::$tableNameRooms." WHERE `apt_id`='".$apt_id."' ORDER BY `position` DESC ");
    }

    public static function deleteRoom($id_array = []){
        if(empty($id_array)) {
            $id_array = $_POST["row_check"];
        }
        $ids = Security::safe(implode(",", $id_array));
        self::$db->raw("DELETE FROM ".self::$tableNameRooms." where `id` in (".$ids.")");
    }
    public static function statusRoomToogle($id){
        $query = self::$db->selectOne("SELECT `status` FROM ".self::$tableNameRooms." WHERE `id`='".$id."'");
        $old_status = $query['status'];
        if($old_status==0){
            $status = 1;
        }else{
            $status = 0;
        }

        self::$db->raw("UPDATE ".self::$tableNameRooms." SET `status`='".$status."' WHERE `id` ='".$id."'");
    }

    public static function statusRoom($status){
        $row_check = $_POST["row_check"];
        $ids = Security::safe(implode(",",$row_check));
        self::$db->raw("UPDATE ".self::$tableNameRooms." SET `status`='".$status."' WHERE `id` in (".$ids.")");
    }


}

?>
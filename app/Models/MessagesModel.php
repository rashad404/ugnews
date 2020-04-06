<?php
namespace Models;
use Core\Model;
use Helpers\Console;
use Helpers\Database;
use Helpers\Security;
use Helpers\Session;
use Helpers\Validator;
use Core\Language;

class MessagesModel extends Model{

    private static $tableName = 'messages';
    private static $tableNameChats = 'message_chats';
    private static $tableNameUsers = 'users';
    
    private static $tableNameFeatures = 'apt_features';
    private static $tableNameLocations = 'apt_locations';
    private static $tableNameCategories = 'apt_categories';
    private static $tableNameModels = 'apt_models';
    private static $tableNameAlbum = 'apt_album';
    private static $tableNameRooms = 'apt_rooms';
    private static $tableNameBeds = 'apt_beds';
    public $lng;
    public function __construct(){
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
    }

    private static function naming(){
        return include SMVC.'app/language/'.LanguagesModel::defaultLanguage().'/naming.php';
    }

    private static $rules = [
        'text' => ['min_length(5)', 'max_length(5000)']
    ];

    public static function getPost()
    {
        extract($_POST);
        $array = [];
        $skip_list = ['csrf_token', 'filter'];
        foreach($_POST as $key=>$value){
            if (in_array($key, $skip_list)) continue;
            $array[$key] = Security::safeText($_POST[$key]);
        }
//        Console::varDump($array['text']);
        return $array;
    }

    public static function getAlbum($id){
        $array = self::$db->select("SELECT `id` FROM `".self::$tableNameAlbum."` WHERE `status`=1 AND `apt_id`='".$id."' ORDER BY `position` DESC");
        return $array;
    }

    public static function getFeatureList($limit=10){
        $array = self::$db->select("SELECT `id`,`title_".self::$def_language."` FROM `".self::$tableNameFeatures."` WHERE `status`=1 ORDER BY `position` DESC LIMIT $limit");
        return $array;
    }
    public static function getStarList(){
        $array = [5,4,3,2,1];
        return $array;
    }
    public static function getCategoryList($limit=10){
        $array = self::$db->select("SELECT `id`,`title_".self::$def_language."` FROM `".self::$tableNameCategories."` WHERE `status`=1 ORDER BY `position` DESC LIMIT $limit");
        return $array;
    }
    public static function getLocationList($limit=10){
        $array = self::$db->select("SELECT `id`,`title_".self::$def_language."` FROM `".self::$tableNameLocations."` WHERE `status`=1 ORDER BY `position` DESC LIMIT $limit");
        return $array;
    }
    public static function getFeatureName($id){
        $id = preg_replace('/f/','',$id);
        $return = self::$db->selectOne("SELECT `title_".self::$def_language."` FROM `".self::$tableNameFeatures."` WHERE `id`='".$id."'");
        return $return['title_'.self::$def_language];
    }
    public static function getModelName($id){
        $return = self::$db->selectOne("SELECT `name_".self::$def_language."` FROM `".self::$tableNameModels."` WHERE `id`='".$id."'");
        return $return['name_'.self::$def_language];
    }


    public static function getCategoryName($id){
        $return = self::$db->selectOne("SELECT `title_".self::$def_language."` FROM `".self::$tableNameCategories."` WHERE `id`='".$id."'");
        return $return['title_'.self::$def_language];
    }
    public static function getPopularList($limit=10){
        $array = self::$db->select("SELECT `id`,`time`,`title_".self::$def_language."`,`text_".self::$def_language."`,`thumb`,`image`,`rent` FROM `".self::$tableName."` WHERE `status`=1 ORDER BY `view` DESC LIMIT $limit");
        return $array;
    }
    public static function getSearchList($text,$limit=10){
        $array = self::$db->select("SELECT `id`,`time`,`view`,`title_".self::$def_language."`,`text_".self::$def_language."`,`thumb`,`image`,`rent` FROM `".self::$tableName."` 
        WHERE `status`=1 AND 
        (`title_".self::$def_language."` LIKE '%".$text."%' OR `text_".self::$def_language."` LIKE '%".$text."%')
        ORDER BY `id` DESC LIMIT $limit");
        return $array;
    }

    public static function navigate($id, $action){
        if($action=='next'){
            $action_symbol = '>';
        }else{
            $action_symbol = '<';
        }
        $array = self::$db->selectOne("SELECT `id`,`time`,`title_".self::$def_language."`,`text_".self::$def_language."`,`thumb`,`image`,`rent`,`features` FROM `".self::$tableName."` WHERE `id` ".$action_symbol." '".$id."' AND `status`=1 ORDER BY `id` DESC");
        return $array;
    }


    public static function getRooms($id){
        $array = self::$db->select("SELECT `id`,`name_".self::$def_language."` as `name` FROM `".self::$tableNameRooms."` WHERE `status`=1 AND `apt_id`='".$id."' ORDER BY `position` DESC");
        return $array;
    }
    public static function getBeds($id){
        $array = self::$db->select("SELECT `id`,`name_".self::$def_language."` as `name`,`tenant_id`,`apply_link` FROM `".self::$tableNameBeds."` WHERE `status`=1 AND `room_id`='".$id."' ORDER BY `position` DESC");
        return $array;
    }

    public static function getStateList(){
        $array = self::$db->select("SELECT `id`,`state_code` FROM `us_states` ORDER BY `id` ASC");
        return $array;
    }


    public static function getList($id){
        $userId = intval(Session::get("user_session_id"));
        $array = self::$db->select("SELECT a.*,b.`first_name`,b.`gender`,b.`birthday` FROM `".self::$tableName."` as a INNER JOIN `".self::$tableNameUsers."` as b ON a.`user_id`=b.`id` 
        WHERE (a.`user_id`='".$userId."' AND a.`to_id`='".$id."') OR (a.`to_id`='".$userId."' AND a.`user_id`='".$id."')
        ");

        $u_array = ['seen'=>1];
        $w_array = ['user_id'=>$id, 'to_id'=>$userId];
        self::$db->update(self::$tableName, $u_array, $w_array);
        return $array;
    }

    public static function getAllMessages($order){
        $userId = intval(Session::get("user_session_id"));
        if($order=='last'){
            $order = 'ORDER BY `time` DESC';
        }else{
            $order = 'ORDER BY `time` ASC';
        }
        $array = self::$db->select("SELECT * FROM `".self::$tableNameChats."` WHERE `id1`='".$userId."' OR `id2`='".$userId."' ".$order);
        return $array;
    }

    public static function countNewMessages(){
        $userId = intval(Session::get("user_session_id"));
        $count = self::$db->count("SELECT COUNT(`id`) FROM `".self::$tableName."` WHERE `to_id`='".$userId."' AND `seen`=0");
        return $count;
    }


    public static function countNewMessagesChat($id){
        $userId = intval(Session::get("user_session_id"));
        $count = self::$db->count("SELECT COUNT(`id`) FROM `".self::$tableName."` WHERE `user_id`='".$id."' AND `to_id`='".$userId."' AND `seen`=0");
        return $count;
    }


    public function send($id){
        $userId = intval(Session::get("user_session_id"));
        $return = [];
        $post_data = $this->getPost();

        $validator = Validator::validate($post_data, self::$rules, self::naming());
        if ($validator->isSuccess()) {
            $return['errors'] = null;

                //merging country_code and phone in array
                $remove_keys = ['number','csrf_token','return'];
                $mysql_data = array_diff_key($post_data,array_flip($remove_keys));
                $mysql_data['time'] = time();
                $mysql_data['user_id'] = $userId;
                $mysql_data['to_id'] = $id;

                $check = self::$db->selectOne("SELECT `id` FROM `".self::$tableNameChats."` WHERE 
                (`id1`='".$userId."' AND `id2`='".$id."') OR 
                (`id2`='".$userId."' AND `id1`='".$id."')");

                if(!$check){
                    $insert_data_chats = [
                        'id1'=>$userId,
                        'id2'=>$id,
                        'last_text'=>$post_data['text'],
                        'time'=>time(),
                    ];
                    self::$db->insert( self::$tableNameChats, $insert_data_chats);
                }else{
                    self::$db->raw( "UPDATE `".self::$tableNameChats."` SET `last_text`='".$post_data['text']."',time='".time()."' 
                    WHERE (`id1`='".$userId."' AND `id2`='".$id."') OR 
                    (`id2`='".$userId."' AND `id1`='".$id."')");
                }

            self::$db->insert( self::$tableName, $mysql_data);
        }else{
            $return['errors'] = implode('<br/>',array_map("ucfirst", $validator->getErrors()));
        }
        $return['postData'] = $post_data;
        return $return;
    }

}

<?php

namespace Modules\user\Models;

use Core\Model;
use Helpers\Security;
use Helpers\FileUploader;
use Helpers\Session;
use Helpers\Url;
use Helpers\File;
use Helpers\Validator;
use Helpers\Slim;
use Helpers\SimpleImage;

class NoticesModel extends Model{

    private static $tableName = 'notices';

    private static $rules;
    private static $params;
    private static $user_id;

    public function __construct($params){
        parent::__construct();
        self::$rules = [
            'text' => ['min_length(10)', 'max_length(1000)'],
            'category' => ['selectbox','positive'],
        ];
        self::$params = $params;
        self::$user_id = Session::get('user_session_id');
    }


    public static function getLocations(){
        $list[0] = ['key'=>0, 'name'=>'Not selected', 'disabled'=>''];
        $list[1] = ['key'=>1, 'name'=>'Bedroom', 'disabled'=>''];
        $list[2] = ['key'=>2, 'name'=>'Kitchen', 'disabled'=>''];
        $list[3] = ['key'=>3, 'name'=>'Living Room', 'disabled'=>''];
        $list[4] = ['key'=>4, 'name'=>'Entrance', 'disabled'=>''];
        $list[5] = ['key'=>5, 'name'=>'Bathroom', 'disabled'=>''];
        $list[6] = ['key'=>6, 'name'=>'Pation/Balcony', 'disabled'=>''];
        $list[7] = ['key'=>7, 'name'=>'Staircase', 'disabled'=>''];
        $list[8] = ['key'=>8, 'name'=>'Closet', 'disabled'=>''];
        $list[9] = ['key'=>9, 'name'=>'Garage', 'disabled'=>''];
        $list[10] = ['key'=>10, 'name'=>'Other', 'disabled'=>''];

        return $list;
    }


    public static function countList(){
        $user_id = Session::get('user_session_id');
        $count = self::$db->selectOne("SELECT count(`id`) as countList FROM ".self::$tableName." WHERE `user_id`='".$user_id."'");
        return $count['countList'];
    }
    public static function getNewNotices(){
        $user_id = Session::get('user_session_id');
        $count = self::$db->selectOne("SELECT count(`id`) as countList FROM ".self::$tableName." WHERE `user_id`='".$user_id."' AND `viewed`=0");
        return $count['countList'];
    }

    public static function getList($limit='LIMIT 0,10'){
        $user_id = Session::get('user_session_id');
        return self::$db->select("SELECT `id`,`notice_id`,`notice_title`,`notice_text`,`time`,`viewed` FROM ".self::$tableName." WHERE `user_id`='".$user_id."' ORDER BY `position` DESC,`id` ASC $limit");
    }
    public static function getItem($id){
        self::$db->update(self::$tableName,['viewed'=>1],["id" => $id]);
        return self::$db->selectOne("SELECT `id`,`notice_id`,`notice_title`,`notice_text`,`time`,`viewed` FROM ".self::$tableName." WHERE `id`='".$id."'");
    }

    protected static function getPost(){
        extract($_POST);
        $skip_list[] = 'csrf_token';
        $skip_list[] = 'image';

        $array = [];
        foreach($_POST as $key=>$value){
            if (in_array($key, $skip_list)) continue;
            $array[$key] = Security::safe($_POST[$key]);
        }
        return $array;
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
        $sql_s = "(".$sql_s.") AND `user_id`=".self::$user_id."";
        $list = self::$db->select("SELECT `id`,`notice_title`,`notice_text`,`time` FROM `".self::$tableName."` WHERE ".$sql_s);
        return $list;
    }

}

?>
<?php
namespace Models;
use Core\Model;
use Core\Language;
use Helpers\Security;
use Helpers\Session;
use Helpers\Validator;

class ForumModel extends Model{

    private static $tableName = 'forum';
    private static $tableNameAnswers = 'forum_answers';
    private static $tableNameUsers = 'users';
    private static $tableNameCategories = 'forum_categories';
    public $lng;
    public $userId;
    public function __construct(){
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
        $this->userId = intval(Session::get("user_session_id"));
    }
    private static $rules = [
        'text' => ['min_length(10)', 'max_length(10000)'],
    ];
    private static function naming(){
        return include SMVC.'app/language/'.LanguagesModel::defaultLanguage().'/naming.php';
    }

    protected static function getPost()
    {
        extract($_POST);
        $skip_list = ['csrf_token','files'];
        foreach($_POST as $key=>$value){
            if (in_array($key, $skip_list)) continue;
            $array[$key] = Security::safe($_POST[$key]);
        }
        return $array;
    }

    public function ask(){
        $postData = self::getPost();
        if($this->userId<1){
            $return['errors'] = $this->lng->get('You must be logged in to ask a question');
        }else {
            $validator = Validator::validate($postData, self::$rules, self::naming());
            if ($validator->isSuccess()) {
                $check = self::$db->selectOne("SELECT `id` FROM `" . self::$tableName . "` WHERE `user_id`=:user_id AND `title`=:title", [':user_id' => $this->userId, ':title' => $postData['title']]);
                if ($check) {
                    $return['errors'] = $this->lng->get("This question has been already added.");
                } else {
                    $postData['user_id'] = $this->userId;
                    $postData['time'] = time();
                    $postData['status'] = 1;
                    self::$db->insert(self::$tableName, $postData);
                }
            } else {
                $return['errors'] = implode('<br/>', $validator->getErrors());
            }
        }
        $return['postData'] = $postData;
        return $return;
    }

    public function answer($id){
        $postData = self::getPost();
        if($this->userId<1){
            $return['errors'] = $this->lng->get('You must be logged in to answer');
        }else {
            $validator = Validator::validate($postData, self::$rules, self::naming());
            if ($validator->isSuccess()) {
                $check = self::$db->selectOne("SELECT `id` FROM `" . self::$tableNameAnswers . "` WHERE `user_id`=:user_id AND `text`=:text", [':user_id' => $this->userId, ':text' => $postData['text']]);
                if ($check) {
                    $return['errors'] = $this->lng->get("This answer has been already added. No need to add again");
                } else {
                    $postData['forum_id'] = $id;
                    $postData['user_id'] = $this->userId;
                    $postData['time'] = time();
                    $postData['status'] = 1;
                    self::$db->insert(self::$tableNameAnswers, $postData);
                    self::$db->raw("UPDATE `".self::$tableName."` SET `answers`=`answers`+1 WHERE `id`=".$id);
                }
            } else {
                $return['errors'] = implode('<br/>', $validator->getErrors());
            }
        }
        $return['postData'] = $postData;
        return $return;
    }





    public static function getList($cat='', $limit=10){
        if($cat>0){
            $cat_mysql = ' AND f.`cat`='.$cat;
        }else{
            $cat_mysql = '';
        }
        $array = self::$db->select("SELECT f.`id`,f.`time`,f.`answers`,f.`view`,f.`title`,f.`text`,f.`thumb`,f.`image`,
        u.`first_name`,u.`last_name` 
        FROM `".self::$tableName."` as f INNER JOIN `".self::$tableNameUsers."` as u ON f.`user_id` = u.`id` WHERE f.`status`=1 ".$cat_mysql." ORDER BY f.`id` DESC LIMIT $limit");
        return $array;
    }

    public static function getAnswerList($id, $limit=10){
        $array = self::$db->select("SELECT f.`id`,f.`time`,f.`text`,f.`user_id`,u.`first_name`,u.`last_name` 
        FROM `".self::$tableNameAnswers."` as f INNER JOIN `".self::$tableNameUsers."` as u ON f.`user_id` = u.`id` WHERE f.`status`=1 AND f.`forum_id`='".$id."' ORDER BY f.`id` DESC LIMIT $limit");
        return $array;
    }

    public static function getCategoryList($limit=10){
        $array = self::$db->select("SELECT `id`,`title_".self::$def_language."` FROM `".self::$tableNameCategories."` WHERE `status`=1 ORDER BY `position` DESC LIMIT $limit");
        return $array;
    }

    public static function getItem($id){
        $array = self::$db->selectOne("
            SELECT f.`id`,f.`time`,f.`title`,f.`text`,f.`cat`,f.`tags`,f.`thumb`,f.`image`,f.`answers`,f.`view`,f.`user_id`,
            u.`first_name`,u.`last_name` 
            FROM `".self::$tableName."` as f INNER JOIN `".self::$tableNameUsers."` as u ON f.`user_id` = u.`id` WHERE f.`id`='".$id."' AND f.`status`=1 ORDER BY f.`id` DESC");
        if($array){
            $array['view'] = $array['view']+1;
            self::$db->update(self::$tableName,['view'=> $array['view']], ['id'=>$id, 'status'=>1]);
            $cat_array = self::$db->selectOne("SELECT `title_".self::$def_language."` FROM ".self::$tableNameCategories." WHERE `id`=".$array['cat']);
            $array['cat_name'] = $cat_array['title_'.self::$def_language];
        }
        return $array;
    }
}

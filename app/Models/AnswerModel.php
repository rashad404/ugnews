<?php
namespace Models;
use Core\Model;
use Helpers\Cookie;
use Helpers\Security;
use Helpers\Validator;
use Core\Language;

class AnswerModel extends Model{

    private static $tableName = 'answers';
    private static $tableNameUsers = 'users';
    private static $tableNameLogsScore = 'logs_score';
    public $lng;
    public function __construct(){
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
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
        $array = [];
        $skip_list = ['csrf_token'];
        foreach($_POST as $key=>$value){
            if (in_array($key, $skip_list)) continue;
            $array[$key] = Security::safe($_POST[$key]);
        }
        return $array;
    }

    public function add($search_id,$user_id){

        $postData = self::getPost();
        $validator = Validator::validate($postData, self::$rules, self::naming());
        if ($validator->isSuccess()) {
            $check = self::$db->selectOne("SELECT `id` FROM `".self::$tableName."` WHERE `user_id`=:user_id AND `search_id`=:search_id",[':user_id'=>$user_id,':search_id'=>$search_id]);
            if($check){
                $return['errors'] = $this->lng->get("You've already added an answer for this search. You can add only 1 answer");
            }else{
                self::$db->insert(self::$tableName, ['text'=>$postData['text'],'search_id'=>$search_id,'user_id'=>$user_id,'time'=>time()]);
            }
        }else{
            $return['errors'] = implode('<br/>',$validator->getErrors());
        }
        $return['postData'] = $postData;
        return $return;
    }

    public static function getAnswers($search_id, $limit=10){
        $array = self::$db->select("SELECT answers.`id`,users.`login`,answers.`text`,answers.`score` FROM `".self::$tableName."` INNER JOIN `".self::$tableNameUsers."` ON answers.user_id=users.id WHERE answers.`search_id`=:search_id ORDER BY answers.`score` DESC LIMIT ".$limit,[':search_id'=>$search_id]);
        return $array;
    }

    public static function getAnswerInfo($id){
        $array = self::$db->selectOne("SELECT answers.`id`, answers.`search_id`,users.`login`,answers.`text`,answers.`score` FROM `".self::$tableName."` INNER JOIN `".self::$tableNameUsers."` ON answers.user_id=users.id WHERE answers.`id`=:id",[':id'=>$id]);
        return $array;
    }

    public static function updateScore($id, $action){
        if ($action != 'plus' && $action != 'minus') {
            return false;
        }
        $ip = Security::getIp();
        $browser =Security::getBrowser();
        $cookie = Cookie::get('uniqueId');

        $answer_info = self::getAnswerInfo($id);
        if($answer_info){
            $search_id = $answer_info['search_id'];

            $check = self::$db->selectOne("SELECT `id`,`action` FROM `".self::$tableNameLogsScore."` 
            WHERE (`cookie`=:cookie OR (`ip`=:ip AND `browser`=:browser)) AND `answer_id`=:answer_id", ['cookie'=>Cookie::get('uniqueId'), 'ip'=>$ip, 'browser'=>$browser, 'answer_id'=>$id]);
            if($check){
                $check_action = $check['action'];
                if($action=='plus')$new_action = $check_action+1;
                if($action=='minus')$new_action = $check_action-1;
                if($new_action<-1 or $new_action>1){
                    return false;
                }
                self::$db->update(self::$tableNameLogsScore, ['action'=>$new_action],['id'=>$check['id']]);
            }else{
                if($action=='plus')$new_action = 1;
                if($action=='minus')$new_action = -1;
                $data = [
                    'cookie' => $cookie,
                    'answer_id' => $id,
                    'search_id' => $search_id,
                    'ip' => $ip,
                    'browser' => $browser,
                    'action' => $new_action,
                    'time' => time()
                ];
                self::$db->insert(self::$tableNameLogsScore, $data);
            }
            if ($action == 'plus') {
                self::$db->raw("UPDATE `" . self::$tableName . "` SET `score`=`score`+1 WHERE `id`='" . $id . "'");
            }
            if ($action == 'minus') {
                self::$db->raw("UPDATE `" . self::$tableName . "` SET `score`=`score`-1 WHERE `id`='" . $id . "'");
            }

        }

    }
}

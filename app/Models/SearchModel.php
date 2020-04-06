<?php
namespace Models;
use Core\Model;
use Helpers\Security;
use Helpers\Validator;

class SearchModel extends Model{

    private $defLang;
    private static $tableName = 'search';
    private static $tableNameCategories = 'categories';
    public function __construct(){
        parent::__construct();
        $this->defLang = LanguagesModel::defaultLanguage();
    }
    private static $rules = [
        'query' => ['min_length(1)', 'max_length(100)'],
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

    public function trackSearch($query){
        $check = self::$db->selectOne("SELECT `id`,`view` FROM `".self::$tableName."` WHERE `query`=:query",[':query'=>$query]);
        if($check){
            $view_plus = $check['view']+1;
            $search_id = $check['id'];
            self::$db->update(self::$tableName, ['view'=>$view_plus,'time'=>time()], ['id'=>$check['id']]);
        }else{
            $search_id = self::$db->insert(self::$tableName, ['query'=>$query,'view'=>1,'time'=>time()]);
        }
        return $search_id;
    }
    public function search($query=''){
        $postData = self::getPost();
        $search_id = 0;
        if(!empty($query)) {
            $postData['query'] = urldecode($query);
        }
        $validator = Validator::validate($postData, self::$rules, self::naming());
        if ($validator->isSuccess()) {
            $search_id = $this->trackSearch($postData['query']);
        }else{
            $return['errors'] = implode('<br/>',$validator->getErrors());
        }
        $return['postData'] = $postData;
        $return['search_id'] = $search_id;
        return $return;
    }

    public static function getPopularSearches($limit=10){
        $array = self::$db->select("SELECT `id`,`query`,`view` FROM `".self::$tableName."` ORDER BY `view` DESC LIMIT ".$limit);
        return $array;
    }
    public static function getSearchesForAnswer($limit=100){
        $array = self::$db->select("SELECT `id`,`query`,`view` FROM `".self::$tableName."` ORDER BY `view` DESC LIMIT ".$limit);
        return $array;
    }

    public static function getPopularCategories($limit=10){
        $array = self::$db->select("SELECT `id`,`name` FROM `".self::$tableNameCategories."` ORDER BY `position` DESC LIMIT ".$limit);
        return $array;
    }

    public static function getSearchInfo($id){
        $array = self::$db->selectOne("SELECT `id`,`query` FROM `".self::$tableName."` WHERE `id`=:id",[":id"=>$id]);
        return $array;
    }

}

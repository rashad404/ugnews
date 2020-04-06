<?php
namespace Models;
use Core\Model;
use Helpers\Database;
use Helpers\Session;
use Helpers\Validator;
use Core\Language;
use Models\LocationModel;

class RoommatesModel extends Model{

    private static $tableName = 'apartments';
    private static $tableNameRoommates = 'roommate_ads';
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
        'budget' => ['min(100)','max(5000)'],
        'budget_period' => ['selectbox'],
        'movein_month' => ['selectbox'],
        'movein_day' => ['selectbox'],
        'movein_year' => ['selectbox'],
        'stay_min' => ['selectbox'],
        'stay_max' => ['selectbox'],
        'profession' => ['selectbox'],
        'smoking' => ['integer'],
        'animals' => ['integer'],
        'language' => ['integer'],
        'nationality' => ['integer'],
        'pr_gender' => ['integer'],
        'pr_age_min' => ['integer'],
        'pr_age_max' => ['integer'],
        'pr_profession' => ['integer'],
        'pr_smoking' => ['integer'],
        'pr_animals' => ['integer'],
        'state_id' => ['selectbox'],
    ];

    public static function getPost()
    {
        extract($_POST);
        $array = [];
        $skip_list = ['csrf_token', 'filter'];
        foreach($_POST as $key=>$value){
            if (in_array($key, $skip_list)) continue;
            $array[$key] = $_POST[$key];
        }
        return $array;
    }

    public static function getList($order){

        $f_where_array = [];
        $where = '';



        if(Session::check('filters'.'roommates')) {
            $filters = FilterModel::getFilters('roommates');

//            echo '<pre>';
//            print_r($filters);
//            echo '</pre>';

            $move_in = $filters['movein_year'].'-'.$filters['movein_month'].'-'.$filters['movein_day'];
//            $move_in = '2020-02-20';
            foreach ($filters as $key=>$value) {
                if(preg_match('/features/', $key)  && $value!='-') {
                    $f_exp = explode(',', $value);
                    foreach ($f_exp as $f_value){
                        $f_where_array[] = " a.`features` LIKE '%".trim($f_value)."%' ";
                    }
                }
            }

            if(count($f_where_array)>0) {$where .= ' AND ('.implode($f_where_array, ' AND ').')';}

            if($filters['budget_period']==1) {
                $budget_min = $filters['budget_min'];
                $budget_max = $filters['budget_max'];
            }elseif($filters['budget_period']==2) {
                $budget_min = $filters['budget_min']/7;
                $budget_max = $filters['budget_max']/7;
            }elseif($filters['budget_period']==3) {
                $budget_min = $filters['budget_min']/30;
                $budget_max = $filters['budget_max']/30;
            }

            if(isset($filters['budget_min']) && $filters['budget_min']>0)$where .= " AND a.`budget_day`>='".$budget_min."'";
            if(isset($filters['budget_max']) && $filters['budget_max']>0)$where .= " AND a.`budget_day`<='".$budget_max."'";
            if(isset($filters['stay_min']) && $filters['stay_min']>0)$where .= " AND a.`stay_min`>='".$filters['stay_min']."'";
            if(isset($filters['stay_max']) && $filters['stay_max']>0)$where .= " AND a.`stay_max`<='".$filters['stay_max']."'";

            if(strlen($move_in)>5)$where .= " AND a.`movein_date`<='".$move_in."'";

            if(isset($filters['profession']))$where .= " AND a.`profession` IN (".$filters['profession'].")";
            if(isset($filters['gender']))$where .= " AND a.`gender` IN (".$filters['gender'].")";
            if(isset($filters['smoking']) && $filters['smoking']>0)$where .= " AND a.`smoking` = '".$filters['smoking']."'";
            if(isset($filters['animals']) && $filters['animals']>0)$where .= " AND a.`animals` = '".$filters['animals']."'";
            if(isset($filters['language']) && $filters['language']>0)$where .= " AND a.`language` = '".$filters['language']."'";
            if(isset($filters['nationality']) && $filters['nationality']>0)$where .= " AND a.`nationality` = '".$filters['nationality']."'";

            if(isset($filters['location']) && strlen($filters['location'])>3) {
                $location_array = LocationModel::getParamsFromText($filters['location']);
                foreach ($location_array as $key=>$val) {
                    $where .= " AND `".$key."` = '" . $val . "'";
                }
            }
        }


        if($order=='low_price'){
            $mysql_order = 'a.`budget` ASC';
        }elseif($order=='high_price'){
            $mysql_order = 'a.`budget` DESC';
        }else{
            $mysql_order = 'a.`id` DESC';
        }
//            echo '<hr/>'.$where;
        $array = self::$db->select("SELECT 
        a.*,b.`first_name`,b.`gender`,b.`birthday` ,b.`time` FROM `".self::$tableNameRoommates."` as a INNER JOIN `".self::$tableNameUsers."` as b ON a.`user_id`=b.`id` WHERE a.`status`=1 $where ORDER BY $mysql_order LIMIT 0,1000");

        return $array;
    }

    public static function getItem($id){
        $array = self::$db->selectOne("SELECT a.*,b.`first_name`,b.`gender`,b.`birthday` FROM `".self::$tableNameRoommates."` as a INNER JOIN `".self::$tableNameUsers."` as b ON a.`user_id`=b.`id` WHERE a.`id`='".$id."'");
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

    public function add(){
        $userId = intval(Session::get("user_session_id"));
        $user_info = self::$db->selectOne("SELECT `id`,`gender` FROM `".self::$tableNameUsers."` WHERE `id`='".$userId."'");
        $return = [];
        $post_data = $this->getPost();

        $validator = Validator::validate($post_data, self::$rules, self::naming());
        if ($validator->isSuccess()) {
            $return['errors'] = null;

                //merging country_code and phone in array
                $remove_keys = ['number','csrf_token','return','movein_day','movein_month','movein_year','features'];
                $mysql_data = array_diff_key($post_data,array_flip($remove_keys));
                $mysql_data['movein_date'] = $post_data['movein_year'].'-'.$post_data['movein_month'].'-'.$post_data['movein_day'];

                if(!empty($post_data['features'])) {
                    $features_array = [];
                    foreach ($post_data['features'] as $key => $val) {
                        $features_array[] = 'f' . $key;
                    }
                    $mysql_data['features'] = implode(',', $features_array);
                }


                if($post_data['budget_period']==1) {
                    $mysql_data['budget_day'] = $post_data['budget'];
                }elseif($post_data['budget_period']==2) {
                    $mysql_data['budget_day'] = $post_data['budget'] /7;
                }elseif($post_data['budget_period']==3) {
                    $mysql_data['budget_day'] = $post_data['budget'] /30;
                }

                $mysql_data['reg_time'] = time();
                $mysql_data['status'] = 1;
                $mysql_data['user_id'] = $userId;
                $mysql_data['gender'] = $user_info['gender'];

                Database::get()->insert( self::$tableNameRoommates, $mysql_data);
        }else{
            $return['errors'] = implode('<br/>',array_map("ucfirst", $validator->getErrors()));
        }
        $return['postData'] = $post_data;
        return $return;
    }


    public static function getLocationName($state_id, $county_id, $city_id){

        $state_array = self::$db->selectOne("SELECT `id`, `state_code`, `state_name` FROM `us_states` WHERE `id`='".$state_id."'");
        $state_name = $state_array['state_name'];
        $city_name = $county_name = '';

        if($city_id>0) {
            $array = self::$db->selectOne("SELECT `city_name`, `county_name` FROM `us_cities` WHERE `id`='" . $city_id . "'");
            $city_name = $array['city_name'].', ';
            $county_name = $array['county_name'].' ';
            $state_name = $state_array['state_code'];
        }elseif($county_id>0){
            $array = self::$db->selectOne("SELECT `county_name` FROM `us_counties` WHERE `id`='" . $county_id . "'");
            $state_name = $state_array['state_code'];
            $county_name = $array['county_name'].' ';
        }
        return $city_name.$county_name.$state_name;
    }

    public static function setCountyId(){
        $array = self::$db->select("SELECT `id`,`county_name` FROM `us_counties` ORDER BY `id` ASC");
        foreach ($array as $list){
            self::$db->raw( "UPDATE `us_cities` SET `county_id`='".$list['id']."' WHERE `county_name`='".$list['county_name']."'");
        }
    }

    public static function setStateName(){
        $array = self::$db->select("SELECT `id`,`state_code`,`state_name` FROM `us_states` ORDER BY `id` ASC");
        foreach ($array as $list){
            self::$db->raw( "UPDATE `us_cities` SET `state_code`='".$list['state_code']."', `state_name`='".$list['state_name']."' WHERE `state_id`='".$list['id']."'");
        }
    }
}

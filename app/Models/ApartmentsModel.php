<?php
namespace Models;
use Core\Model;
use Helpers\Console;
use Helpers\Cookie;
use Helpers\Database;
use Helpers\Security;
use Helpers\Session;
use Helpers\Validator;
use Core\Language;
use Modules\partner\Models\RoomsModel;

class ApartmentsModel extends Model{

    private static $tableName = 'apartments';
    private static $tableNameApplications = 'apt_applications';
    private static $tableNameShowings = 'showings';
    private static $tableNameUsers = 'users';
    private static $tableNameFeatures = 'apt_features';
    private static $tableNameLocations = 'apt_locations';
    private static $tableNameCategories = 'apt_categories';
    private static $tableNameModels = 'apt_models';
    private static $tableNameAlbum = 'apt_album';
    private static $tableNameRooms = 'apt_rooms';
    private static $tableNameBeds = 'apt_beds';
    private static $tableNameRoomTypes = 'apt_room_types';
    public $lng;
    public function __construct(){
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
    }

    private static $rules = [

        'movein_month' => ['required', 'selectbox'],
        'movein_day' => ['required', 'selectbox'],
        'movein_year' => ['required', 'selectbox'],
        'ssn' => ['required', 'integer', 'exact_length(9)'],
        'dl' => ['min_length(4)', 'max_length(30)'],
        'dl_state' => ['selectbox'],

        'current_address' => ['required', 'min_length(5)', 'max_length(100)'],
        'current_city' => ['required', 'min_length(3)', 'max_length(100)'],
        'current_zip' => ['required', 'exact_length(5)'],
        'current_state' => ['required', 'selectbox'],
        'current_country' => ['required', 'selectbox'],
        'current_rent' => ['required', 'min(100)','max(5000)'],
        'current_month_from' => ['required', 'selectbox','min(1)','max(12)'],
        'current_year_from' => ['required', 'selectbox','min(1950)','max(2050)'],
        'current_month_to' => ['required', 'selectbox','min(1)','max(12)'],
        'current_year_to' => ['required', 'selectbox','min(1950)','max(2050)'],
        'current_landlord_name' => ['min_length(5)', 'max_length(100)'],
        'current_landlord_phone' => ['phone'],
        'current_landlord_email' => ['email'],
        'current_reason' => ['min_length(5)', 'max_length(500)'],


        'previous_address' => ['min_length(5)', 'max_length(100)'],
        'previous_city' => ['min_length(3)', 'max_length(100)'],
        'previous_zip' => ['exact_length(5)'],
        'previous_state' => ['selectbox'],
        'previous_country' => ['selectbox'],
        'previous_rent' => ['min(100)','max(5000)'],
        'previous_month_from' => ['selectbox','min(1)','max(12)'],
        'previous_year_from' => ['selectbox','min(1950)','max(2050)'],
        'previous_month_to' => ['selectbox','min(1)','max(12)'],
        'previous_year_to' => ['selectbox','min(1950)','max(2050)'],
        'previous_landlord_name' => ['min_length(5)', 'max_length(100)'],
        'previous_landlord_phone' => ['phone'],
        'previous_landlord_email' => ['email'],
        'previous_reason' => ['min_length(5)', 'max_length(500)'],

        'employer_address' => ['min_length(5)', 'max_length(150)'],
        'employer_city' => ['min_length(3)', 'max_length(100)'],
        'employer_zip' => ['exact_length(5)'],
        'employer_state' => ['selectbox'],
        'employer_country' => ['selectbox'],
        'salary' => ['min(100)','max(500000)'],
        'position' => ['min_length(3)', 'max_length(100)'],
        'worked_month_from' => ['selectbox','min(1)','max(12)'],
        'worked_year_from' => ['selectbox','min(1950)','max(2050)'],
        'worked_month_to' => ['selectbox','min(1)','max(12)'],
        'worked_year_to' => ['selectbox','min(1950)','max(2050)'],
        'employer_name' => ['min_length(5)', 'max_length(100)'],
        'employer_phone' => ['phone'],
        'employer_email' => ['email'],
        'extra_income' => ['min(100)','max(500000)'],

        'smoking' => ['integer'],
        'animals' => ['integer'],
        'note' => ['min_length(3)', 'max_length(500)'],
    ];




    private static $rulesShowing = [

        'date' => ['required', 'selectbox'],
        'movein_date' => ['required', 'selectbox'],
        'animals' => ['integer'],
        'note' => ['min_length(3)', 'max_length(500)'],
    ];

    private static function naming(){
        return include SMVC.'app/language/'.LanguagesModel::defaultLanguage().'/naming.php';
    }


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

    public static function getList($limit=10, $order){

        $f_where_array = [];
        $where = '';

        if(isset($_POST['filter'])) {
            FilterModel::setFilters();
        }
        if(isset($_GET['reset_filter'])) {
            FilterModel::resetFilters();
        }
        if(Session::check('filters')) {
            $filters = json_decode(Session::get('filters'), true);
//                        print_r($filters);
//                        echo '<br/>';
            foreach ($filters as $key=>$value) {
                if(preg_match('/features/', $key)  && $value!='-') {
                    $f_exp = explode(',', $value);
                    foreach ($f_exp as $f_value){
                        $f_where_array[] = " a.`features` LIKE 'f%".trim($f_value)."%' ";
                    }
                }
            }

            if(count($f_where_array)>0) {$where .= ' AND ('.implode($f_where_array, ' AND ').')';}
            if(isset($filters['price_min']) && $filters['price_min']>0)$where .= " AND a.`rent`>='".$filters['price_min']."'";
            if(isset($filters['price_max']) && $filters['price_max']>0)$where .= " AND a.`rent`<='".$filters['price_max']."'";
            if(isset($filters['countries']))$where .= " AND a.`location` IN (".$filters['countries'].")";
            if(isset($filters['categories']))$where .= " AND a.`category` IN (".$filters['categories'].")";
            if(isset($filters['stars']))$where .= " AND a.`star` IN (".$filters['stars'].")";
        }


        if($order=='low_price'){
            $mysql_order = 'a.`rent` ASC';
        }elseif($order=='high_price'){
            $mysql_order = 'a.`rent` DESC';
        }else{
            $mysql_order = 'a.`id` DESC';
        }
        //    echo '<hr/>'.$where;
        $array = self::$db->select("SELECT 
        a.`id`,a.`size`,a.`address`,a.`start_time`,a.`time`,a.`view`,a.`star`,a.`title_".self::$def_language."`,a.`text_".self::$def_language."`,a.`thumb`,a.`image`,a.`rent`,a.`features`,
        a.`category`, a.`apt_model`, 
        b.`title_".self::$def_language."` as location_name
        FROM `".self::$tableName."` as a INNER JOIN `".self::$tableNameLocations."` as b ON a.`location`=b.`id` WHERE a.`status`=1 $where ORDER BY $mysql_order LIMIT $limit");
        return $array;
    }

    public static function getListByBeds($limit=10, $order, $partner_id){

        $f_where_array = [];
        $where = $where_bed = '';

        if(Session::check('filters')) {
            $filters = json_decode(Session::get('filters'), true);
//                        print_r($filters);
//                        echo '<br/>';
            foreach ($filters as $key=>$value) {
                if(preg_match('/features/', $key)  && $value!='-') {
                    $f_exp = explode(',', $value);
                    foreach ($f_exp as $f_value){
                        $f_where_array[] = " a.`features` LIKE 'f%".trim($f_value)."%' ";
                    }
                }
            }

            if(count($f_where_array)>0) {$where .= ' AND ('.implode($f_where_array, ' AND ').')';}
            if(isset($filters['price_min']) && $filters['price_min']>0)$where_bed .= " AND `price`>='".$filters['price_min']."'";
            if(isset($filters['price_max']) && $filters['price_max']>0)$where_bed .= " AND `price`<='".$filters['price_max']."'";
            if(isset($filters['room_types']))$where_bed .= " AND `room_type` IN (".$filters['room_types'].")";
            if(isset($filters['countries']))$where .= " AND a.`location` IN (".$filters['countries'].")";
            if(isset($filters['categories']))$where .= " AND a.`category` IN (".$filters['categories'].")";
            if(isset($filters['stars']))$where .= " AND a.`star` IN (".$filters['stars'].")";
        }

        if($partner_id>0){
            $where .= " AND a.`partner_id` =".$partner_id."";
        }

        if($order=='low_price'){
            $mysql_order = 'a.`rent` ASC';
        }elseif($order=='high_price'){
            $mysql_order = 'a.`rent` DESC';
        }else{
            $mysql_order = 'a.`id` DESC';
        }
        //    echo '<hr/>'.$where;
        $bed_array = self::$db->select("SELECT `id`,`apt_id`,`price`,`apply_link`,`available_date`,`tenant_id`,`room_id`,`room_type` FROM ".self::$tableNameBeds." WHERE (`tenant_id`=0 OR `available_date`>".date('Y-m-d').") ".$where_bed." ORDER BY `position` DESC");
        $return = [];
        foreach ($bed_array as $bed) {
            $apt_array = self::$db->selectOne("SELECT 
            a.`id`,a.`size`,a.`address`,a.`start_time`,a.`time`,a.`view`,a.`star`,a.`title_" . self::$def_language . "`,a.`text_" . self::$def_language . "`,a.`thumb`,a.`image`,a.`rent`,a.`features`,
            a.`category`, a.`apt_model`, 
            b.`title_" . self::$def_language . "` as location_name
            FROM `" . self::$tableName . "` as a INNER JOIN `" . self::$tableNameLocations . "` as b ON a.`location`=b.`id` WHERE a.`id`=".$bed['apt_id']." AND a.`status`=1 $where ORDER BY $mysql_order LIMIT $limit");

            if($apt_array) {
                $apt_array['bed_id'] = $bed['id'];
                $apt_array['apt_id'] = $bed['apt_id'];
                $apt_array['room_id'] = $bed['room_id'];
                $room_name = RoomsModel::getName($bed['room_id']);
                if(preg_match('/ /',$room_name)){
                    $room_name = strtok($room_name, ' ');
                }
                $apt_array['room_name'] = $room_name;
                $apt_array['price'] = $bed['price'];
                $apt_array['apply_link'] = $bed['apply_link'];
                $apt_array['available_date'] = $bed['available_date'];
                $apt_array['tenant_id'] = $bed['tenant_id'];
                $return[] = $apt_array;
            }
        }
        return $return;
    }



    public static function getItem($id){
        $array = self::$db->selectOne("SELECT 
        a.`id`,a.`size`,a.`address`,a.`start_time`,a.`time`,a.`view`,a.`star`,a.`title_".self::$def_language."`,a.`text_".self::$def_language."`,a.`thumb`,a.`image`,a.`features`,
        a.`category`, a.`apt_model`, a.`features`, a.`map_address`, a.`partner_id`, 
        b.`title_".self::$def_language."` as location_name
        FROM `".self::$tableName."` as a INNER JOIN `".self::$tableNameLocations."` as b ON a.`location`=b.`id` 
        WHERE a.`id`='".$id."' AND a.`status`=1 ORDER BY `id` DESC");
        if($array){
            $array['view'] = $array['view'] + 1;
            self::$db->update(self::$tableName,['view'=> $array['view']], ['id'=>$id, 'status'=>1]);
        }
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

    public static function getRoomTypes(){
        $array = self::$db->select("SELECT `id`,`name` FROM `".self::$tableNameRoomTypes."` WHERE `status`=1 ORDER BY `position` DESC");
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

    public static function getLocationName($id){
        $return = self::$db->selectOne("SELECT `title_".self::$def_language."` FROM `".self::$tableNameLocations."` WHERE `id`='".$id."'");
        return $return['title_'.self::$def_language];
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
        $array = self::$db->select("SELECT `id`,`name_".self::$def_language."` as `name`,`tenant_id`,`apply_link` FROM `".self::$tableNameBeds."` WHERE `status`=1 AND `room_id`='".$id."' ORDER BY `position` ASC");
        return $array;
    }

    public static function getRoomName($id){
        $array = self::$db->selectOne("SELECT `name_".self::$def_language."` as `name` FROM `".self::$tableNameRooms."` WHERE `id`='".$id."'");
        return $array['name'];
    }



    public static function getStateList(){
        $array = self::$db->select("SELECT `id`,`state_code` FROM `us_states` ORDER BY `id` ASC");
        return $array;
    }

    public function apply(){
        $userId = intval(Session::get("user_session_id"));
        $user_info = self::$db->selectOne("SELECT * FROM `".self::$tableNameUsers."` WHERE `id`='".$userId."'");
        $partner_id = $user_info['partner_id'];
        $return = [];
        $post_data = $this->getPost();

        $validator = Validator::validate($post_data, self::$rules, self::naming());
        if ($validator->isSuccess()) {
            $return['errors'] = null;

            //merging country_code and phone in array
            $remove_keys = ['csrf_token','return','movein_day','movein_month','movein_year'];
            $mysql_data = array_diff_key($post_data,array_flip($remove_keys));
            $mysql_data['movein_date'] = $post_data['movein_year'].'-'.$post_data['movein_month'].'-'.$post_data['movein_day'];

            $mysql_data['time'] = time();
            $mysql_data['user_id'] = $userId;
            $mysql_data['partner_id'] = $partner_id;
            $mysql_data['gender'] = $user_info['gender'];
            $mysql_data['first_name'] = $user_info['first_name'];
            $mysql_data['last_name'] = $user_info['last_name'];

            $bed_info = self::$db->selectOne("SELECT `price` FROM `".self::$tableNameBeds."` WHERE `id`='".$post_data['bed_id']."'");
            $mysql_data['price'] = $bed_info['price'];

            Database::get()->insert( self::$tableNameApplications, $mysql_data);
        }else{
            $return['errors'] = implode('<br/>',array_map("ucfirst", $validator->getErrors()));
        }
        $return['postData'] = $post_data;
        return $return;
    }

    public function showing(){
        $userId = intval(Session::get("user_session_id"));
        $user_info = self::$db->selectOne("SELECT * FROM `".self::$tableNameUsers."` WHERE `id`='".$userId."'");
        $partner_id = $user_info['partner_id'];

        $return = [];
        $post_data = $this->getPost();

        $validator = Validator::validate($post_data, self::$rulesShowing, self::naming());
        if ($validator->isSuccess()) {
            $return['errors'] = null;

            //merging country_code and phone in array
            $remove_keys = ['csrf_token','return'];
            $mysql_data = array_diff_key($post_data,array_flip($remove_keys));

            $mysql_data['time'] = time();
            $mysql_data['user_id'] = $userId;
            $mysql_data['partner_id'] = $partner_id;
            $mysql_data['type'] = 1;

            Database::get()->insert( self::$tableNameShowings, $mysql_data);
        }else{
            $return['errors'] = implode('<br/>',array_map("ucfirst", $validator->getErrors()));
        }
        $return['postData'] = $post_data;
        return $return;
    }


}

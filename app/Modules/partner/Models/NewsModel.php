<?php

namespace Modules\partner\Models;

use Core\Model;
use Helpers\Security;
use Helpers\FileUploader;
use Helpers\Session;
use Helpers\Url;
use Helpers\File;
use Helpers\Validator;
use Helpers\Slim;
use Helpers\SimpleImage;

class NewsModel extends Model{

    private static $tableName = 'news';
    private static $tableNameCategories = 'categories';
    private static $tableNameCountries = 'countries';
    private static $tableNameLanguages = 'languages';
    private static $tableNameChannels = 'channels';

    private static $rules;
    private static $params;
    private static $partner_id;

    public function __construct($params=''){
        parent::__construct();
        self::$rules = [
            'title' => ['required','min_length(5)', 'max_length(100)'],
            'text' => ['required','min_length(50)', 'max_length(100000)'],
        ];
        self::$db->createTable(self::$tableName,self::getInputs());
        self::$params = $params;
        self::$partner_id = Session::get('user_session_id');
    }

    public static function naming(){
        return [];
    }

    /*
     *If type is empty, will not appear on the page
     *If sql_type is empty, will not create field on sql table
     */
    public static function getInputs(){
        $array[] = ['type'=>'text',         'name'=>'Title',       'key'=>'title',        'sql_type'=>'varchar(100)'];
        $array[] = ['type'=>'',             'name'=>'',                 'key'=>'image',             'sql_type'=>'varchar(200)'];
        $array[] = ['type'=>'',             'name'=>'',                 'key'=>'thumb',             'sql_type'=>'varchar(200)'];
        $array[] = ['type'=>'',             'name'=>'',                 'key'=>'position',            'sql_type'=>'int(11)'];
        $array[] = ['type'=>'select2',      'name'=>'Select category',  'key'=>'cat',            'sql_type'=>'int(5)', 'data' => self::getCategories()];
        $array[] = ['type'=>'tags',         'name'=>'Tags',             'key'=>'tags',            'sql_type'=>'varchar(255)'];
        $array[] = ['type'=>'select2',      'name'=>'Select Channel',   'key'=>'channel',            'sql_type'=>'varchar(2)', 'data' => self::getChannels()];
        $array[] = ['type'=>'',             'name'=>'',                 'key'=>'status',          'sql_type'=>'tinyint(2)'];
        $array[] = ['type'=>'',             'name'=>'',                 'key'=>'time',            'sql_type'=>'int(11)'];
        $array[] = ['type'=>'',             'name'=>'',                 'key'=>'view',           'sql_type'=>'int(11)'];
        $array[] = ['type'=>'',             'name'=>'',                 'key'=>'partner_id',           'sql_type'=>'int(11)'];

        $array[] = ['type'=>'textarea',      'name'=>'Text',           'key'=>'text',            'sql_type'=>'text'];
//        $array[] = ['type'=>'date',         'name'=>'Notice Date',    'key'=>'notice_date',          'sql_type'=>'varchar(20)'];
        return $array;
    }


    public static function getCategories(){
        $list = [];
        $array = self::$db->select("SELECT `id`,`name` FROM ".self::$tableNameCategories." WHERE `status`=1 ORDER BY `position`");
        foreach ($array as $item){
            $list[] = ['key'=>$item['id'], 'name'=>$item['name'], 'disabled'=>''];
        }
        return $list;
    }

    public static function getCountries(){
        new SettingsModel();
        $defaults = SettingsModel::getItem();
        $def_country = $defaults['country'];

        $list = [];
        $array = self::$db->select("SELECT `id`,`name` FROM ".self::$tableNameCountries);
        foreach ($array as $item){
            $list[] = ['key'=>$item['id'], 'name'=>$item['name'], 'disabled'=>'', 'default'=>($def_country==$item['id'])?'true':''];
        }
        return $list;
    }
    public static function getLanguages(){
        new SettingsModel();
        $defaults = SettingsModel::getItem();
        $def_language = $defaults['language'];

        $list = [];
        $array = self::$db->select("SELECT `id`,`name` FROM ".self::$tableNameLanguages." WHERE `status`=1 ORDER BY `id` DESC");
        foreach ($array as $item){
            $list[] = ['key'=>$item['id'], 'name'=>$item['name'], 'disabled'=>'', 'default'=>($def_language==$item['id'])?'true':''];
        }
        return $list;
    }
    public static function getChannels(){
        new SettingsModel();
        $defaults = SettingsModel::getItem();
        $default = $defaults['channel'];

        $list = [];
        $array = self::$db->select("SELECT `id`,`name` FROM ".self::$tableNameChannels." WHERE `status`=1 AND `partner_id`='".self::$partner_id."' ORDER BY `id` DESC");
        foreach ($array as $item){
            $list[] = ['key'=>$item['id'], 'name'=>$item['name'], 'disabled'=>'', 'default'=>($default==$item['id'])?'true':''];
        }
        return $list;
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
        $skip_list = ['csrf_token','image'];
        $array = [];
        foreach($_POST as $key=>$value){
            if (in_array($key, $skip_list)) continue;
            $array[$key] = Security::safe($_POST[$key]);
        }
        return $array;
    }


    public static function getName($id){
        $array = self::$db->selectOne("SELECT `first_name`,`last_name` FROM ".self::$tableName." WHERE `id`='".$id."'");
        return $array['first_name'].' '.$array['last_name'];
    }
    public static function getGenderName($id){
        $array = self::$db->selectOne("SELECT `gender` FROM ".self::$tableName." WHERE `id`='".$id."'");
        return $array['gender'];
    }
    public static function getBreakPredict($id){
        $array = self::$db->selectOne("SELECT `break_predict` FROM ".self::$tableName." WHERE `id`='".$id."'");
        return $array['break_predict'];
    }

    public static function getList($limit='LIMIT 0,10'){
        return self::$db->select("SELECT ".self::getSqlFields()." FROM ".self::$tableName." WHERE `partner_id`='".self::$partner_id."' ORDER BY `id` DESC,`id` DESC $limit");
    }
    public static function getListActive($limit='LIMIT 0,10'){
        return self::$db->select("SELECT ".self::getSqlFields()." FROM ".self::$tableName." WHERE `bed_id`>0 AND `partner_id`='".self::$partner_id."' ORDER BY `id` DESC,`id` DESC $limit");
    }


    public static function getBreakPredictList(){
        return self::$db->select("SELECT ".self::getSqlFields().",`apt_id` FROM ".self::$tableName." WHERE `partner_id`='".self::$partner_id."' AND  `break_predict`>0 ORDER BY `position` DESC,`id` ASC");
    }

    public static function countList(){
        $count = self::$db->selectOne("SELECT count(`id`) as countList FROM ".self::$tableName." WHERE `partner_id`='".self::$partner_id."'");
        return $count['countList'];
    }

    public static function countListActive(){
        $count = self::$db->selectOne("SELECT count(`id`) as countList FROM ".self::$tableName." WHERE `bed_id`>0 AND `partner_id`='".self::$partner_id."'");
        return $count['countList'];
    }


    public static function countUsers($gender){
        $count = self::$db->selectOne("SELECT count(`id`) as c FROM ".self::$tableName." WHERE `bed_id`>0 AND  `partner_id`='".self::$partner_id."' AND `gender`='".$gender."'");
        return $count['c'];
    }


    public static function getListByApt($id){
        return self::$db->select("SELECT a.`id`,a.`first_name`,a.`last_name`,b.`name_".self::$def_language."` as `bed_name` FROM ".self::$tableName." as a
        INNER JOIN ".self::$tableNameBeds." as b ON a.`bed_id`=b.`id` 
        WHERE a.`apt_id`='".$id."' ORDER BY b.`position` DESC, b.`id` ASC ");
    }

    public static function getItem($id){
        return self::$db->selectOne("SELECT ".self::getSqlFields()." FROM ".self::$tableName." WHERE `id`='".$id."'");
    }

    public static function getPass($id){
        $query = self::$db->selectOne("SELECT `password_hash` FROM ".self::$tableName." WHERE `id`='".$id."'");
        return $query['password_hash'];
    }


    public static function add(){
        $return = [];
        $return['errors'] = null;
        $post_data = self::getPost();
        $validator = Validator::validate($post_data, self::$rules, self::naming());
        if ($validator->isSuccess()) {
            $return['errors'] = null;

            $insert_data = $post_data;
            $insert_data['partner_id'] = self::$partner_id;
            $insert_data['time'] = time();

            if($post_data['channel']>0){
                $channel_info = ChannelsModel::getItem($post_data['channel']);
                $insert_data['country'] = $channel_info['country'];
                $insert_data['language'] = $channel_info['language'];
            }

            $insert_id = self::$db->insert(self::$tableName,$insert_data);
            if($insert_id>0){
                self::updatePosition($insert_id);

                $images = Slim::getImages('image');
                if($images){
                    $image = $images[0];
                    if(!empty($image)){
                        self::imageUpload($image, $insert_id);
                    }
                }
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
            if($post_data['channel']>0){
                $channel_info = ChannelsModel::getItem($post_data['channel']);
                $update_data['country'] = $channel_info['country'];
                $update_data['language'] = $channel_info['language'];
            }

            self::$db->update(self::$tableName, $update_data, ['id'=>$id]);

            $images = Slim::getImages('image');
            if($images){
                $image = $images[0];
                if(!empty($image)){
                    self::imageUpload($image, $id);
                }
            }
        }else{
            $return['errors'] = implode('<br/>',array_map("ucfirst", $validator->getErrors()));
        }

        return $return;
    }

    protected static function imageUpload($image, $id)
    {
        $params = self::$params;
//        Console::varDump(self::$params);

        $new_dir = Url::uploadPath().$params['name'].'/'.$id;
        $new_thumb_dir = Url::uploadPath().$params['name'].'/'.$id.'/thumbs';
        $file_name = $id.'_0.jpg';

        File::makeDir($new_dir);
        File::makeDir($new_thumb_dir);

        $new = Slim::saveFile($image['output']['data'], $file_name, $new_dir, false);

        $destination = $new_thumb_dir."/" . $file_name;

        try {
            $img = new SimpleImage();
            $img->load($new['path'])->resize($params['imageSizeX'], $params['imageSizeY'])->save($destination);
        } catch (\Exception $e) {
        }


        $sql_img = self::$tableName.'/'.$id.'/' . $file_name;
        $sql_thumb_img = self::$tableName.'/'.$id.'/thumbs/' . $file_name;
        self::$db->update(self::$tableName, ['image' => $sql_img, 'thumb' => $sql_thumb_img], ['id' => $id]);

        //Optimize images
        FileUploader::imageResizeProportional($new_dir.'/'.$file_name, $new_dir.'/'.$file_name, 90, $params['imageSizeX'], $params['imageSizeY']);
        FileUploader::imageResize($new_thumb_dir.'/'.$file_name, $new_thumb_dir.'/'.$file_name, 90, $params['thumbSizeX'], $params['thumbSizeY']);
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


    public static function searchActive(){
        $postData = self::getPost();
        $text = $postData['search'];
        $values = self::$params['searchFields'];

        $sql_s = '`bed_id`>0 ';
        $sql_s_extra = '';

        if($values<=1){
            $sql_s_extra = "`".$values."` LIKE '%".$text."%' ";
        } else {
            foreach($values as $value){
                $sql_s_extra .= "`".$value."` LIKE '%".$text."%' OR ";
            }
            $sql_s_extra = '('.substr($sql_s,0,-3).')';
        }

        if(!empty($sql_s_extra)){
            $sql_s .= ' AND '.$sql_s_extra;
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
        foreach ($id_array as $id){
            self::deleteImage($id);
        }
    }
    public static function deleteImage($id){
        if(is_dir(Url::uploadPath().self::$params['name'].'/'.$id)) {
            File::rmDir(Url::uploadPath().self::$params['name'].'/'.$id);
        }
        return true;
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

    public static function getListByType($type){
        $where = '';

        if($type==1){
            $where = '';
        }elseif($type==2){
            $where = 'AND `gender`=2';
        }elseif($type==3){
            $where = 'AND `gender`=1';
        }elseif($type==4){
            $where = 'AND `balance`>0';
        }
        return self::$db->select("SELECT `id`,`first_name`,`phone`,`email`,`gender` FROM ".self::$tableName." WHERE `status`=1 AND `bed_id`>0 AND `partner_id`='".self::$partner_id."' ".$where." ORDER BY `id` DESC");
    }

    public static function setPasswords(){
        $partner_id = 338;

        $array = self::$db->select("SELECT `id`,`first_name`,`phone`,`email`,`gender`,`password`,`password_hash` FROM ".self::$tableName." WHERE `password`='' and `status`=1 AND `bed_id`>0 AND `partner_id`='".$partner_id."' ORDER BY `id` DESC");
        $c = 1;
        foreach ($array as $item){

            $new_password = Security::generatePassword(6);
            $new_password_hash = Security::password_hash($new_password);

            echo $c.'<br/>';
            echo $item['id'].'<br/>';
            echo $item['first_name'].'<br/>';
            echo $item['password'].'<br/>';
            echo $item['password_hash'].'<br/>';
            echo $new_password.'<br/>';
            echo $new_password_hash.'<br/>';
            echo '<hr/>';
            $c++;
            self::$db->raw("UPDATE ".self::$tableName." SET `password`='".$new_password."',  `password_hash`='".$new_password_hash."' WHERE `id` ='".$item['id']."'");
        }

    }
    public static function showPass(){
        $partner_id = 338;

        $array = self::$db->select("SELECT `id`,`email`,`first_name`,`last_name`,`phone`,`email`,`gender`,`password`,`password_hash` FROM ".self::$tableName." WHERE `status`=1 AND `bed_id`>0 AND `partner_id`='".$partner_id."' ORDER BY `id` DESC");
        $c = 1;
        foreach ($array as $item){


            echo $c.'<br/>';
            echo $item['id'].'<br/>';
            echo $item['email'].'<br/>';
            echo 'New Tenant Portal is ready | Coronavirus<br/>';
            echo $item['first_name'].'<br/>';
            echo $item['last_name'].'<br/>';
            echo 'Username: '.$item['email'].'<br/>';
            echo 'Password: '.$item['password'].'<br/>';
            echo '<hr/>';
            $c++;
        }

    }

    public static function activatePortalAll(){
        $partner_id = 338;

        $array = self::$db->select("SELECT `id` FROM ".self::$tableName." WHERE `status`=1 AND `bed_id`>0 AND `partner_id`='".$partner_id."' ORDER BY `id` DESC");
        $c = 1;
        foreach ($array as $item){

            self::$db->raw("UPDATE ".self::$tableName." SET `tenant_portal`='1' WHERE `id` ='".$item['id']."'");
        }

    }
}

?>
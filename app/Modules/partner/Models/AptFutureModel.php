<?php

namespace Modules\partner\Models;

use Core\Model;
use Helpers\Console;
use Helpers\Curl;
use Helpers\Security;
use Helpers\Validator;

class AptFutureModel extends Model{

    private static $tableName = 'apt_future';

    private static $rules;
    private static $params;

    public function __construct($params){
        parent::__construct();
        self::$rules = [
            'name' => ['min_length(1)', 'max_length(30)'],
            'rent' => ['min_length(2)', 'max_length(8)', 'positive'],
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
        $array[] = ['type'=>'text',   'name'=>'Name', 'index'=>true,     'key'=>'name',            'sql_type'=>'varchar(255)'];
        $array[] = ['type'=>'text',   'name'=>'Management', 'index'=>true,     'key'=>'management',            'sql_type'=>'varchar(255)'];
        $array[] = ['type'=>'text',   'name'=>'Location', 'index'=>true,     'key'=>'location',            'sql_type'=>'varchar(255)'];
        $array[] = ['type'=>'text',   'name'=>'Address', 'index'=>true,     'key'=>'address',            'sql_type'=>'varchar(255)'];
        $array[] = ['type'=>'text',   'name'=>'Phone', 'index'=>true,     'key'=>'phone',            'sql_type'=>'varchar(255)'];
        $array[] = ['type'=>'text',   'name'=>'Beds', 'index'=>true,     'key'=>'beds',            'sql_type'=>'int(3)'];
        $array[] = ['type'=>'text',   'name'=>'Baths', 'index'=>true,     'key'=>'baths',            'sql_type'=>'int(3)'];
        $array[] = ['type'=>'text',   'name'=>'Size', 'index'=>false,     'key'=>'size',            'sql_type'=>'varchar(20)'];
        $array[] = ['type'=>'text',   'name'=>'Rent', 'index'=>true,     'key'=>'rent',            'sql_type'=>'decimal(10,2)'];
        $array[] = ['type'=>'text',   'name'=>'Availability', 'index'=>true,     'key'=>'availability',            'sql_type'=>'varchar(100)'];
        $array[] = ['type'=>'text',   'name'=>'Added Time', 'index'=>false,     'key'=>'added_time',            'sql_type'=>'varchar(100)'];
        $array[] = ['type'=>'textarea',   'name'=>'Desc', 'index'=>false,     'key'=>'text',            'sql_type'=>'longtext'];
        $array[] = ['type'=>'',             'name'=>'', 'index'=>false,                 'key'=>'status',            'sql_type'=>'tinyint(1)'];
        $array[] = ['type'=>'',             'name'=>'', 'index'=>false,                 'key'=>'position',          'sql_type'=>'int(3)'];
        $array[] = ['type'=>'',             'name'=>'', 'index'=>false,                 'key'=>'image',             'sql_type'=>'varchar(200)'];
        $array[] = ['type'=>'',             'name'=>'', 'index'=>false,                 'key'=>'thumb',             'sql_type'=>'varchar(200)'];
        $array[] = ['type'=>'',             'name'=>'', 'index'=>false,                 'key'=>'img_link',             'sql_type'=>'varchar(255)'];

        return $array;
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
            self::$db->update(self::$tableName,$update_data, ['id'=>$id]);
        }else{
            $return['errors'] = implode('<br/>',array_map("ucfirst", $validator->getErrors()));
        }

        return $return;
    }
    public static function getItem($id){
        return self::$db->selectOne("SELECT ".self::getSqlFields()." FROM ".self::$tableName." WHERE `id`='".$id."'");
    }


    public static function getList(){
        return self::$db->select("SELECT ".self::getSqlFields()." FROM ".self::$tableName." ORDER BY `position` DESC,`id` ASC ");
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


    public static function parseApartmentsCom(){
        $start_page =1;
        $end_page = 28;
        $location = 'Koreatown';
        $baths = 2;
        include 'simple_html_dom.php';
        for($i=$start_page;$i<=$end_page;$i++) {
            $url = 'https://www.apartments.com/koreatown-los-angeles-ca/2-bedrooms/'.$i;
            echo $url.'<br/>';
            $html = file_get_html($url);
            //        $html = file_get_html('test/parse/apt_com.txt');
            foreach ($html->find('.placardContainer') as $element) {
                $data = $element->outertext;
                $html_data = str_get_html($data);
                $c = 0;
                foreach ($html_data->find('li') as $element_li) {

                    $li_text = $element_li->outertext;
                    $li_html = str_get_html($li_text);
                    foreach ($li_html->find('a.placardTitle') as $element) {
                        if (!empty($element->plaintext)) {
                            $apt_data[$c]['name'] = $element->plaintext;
                            $apt_data[$c]['location'] = $location;
                            $apt_data[$c]['baths'] = $baths;
                        }
                    }
                    foreach ($li_html->find('img.propertyLogo') as $element) {
                        if (!empty($element->src)) {
                            $apt_data[$c]['img_link'] = $element->src;
                            $apt_data[$c]['management'] = $element->alt;
                        }
                    }
                    foreach ($li_html->find('div.location') as $element) {
                        if (!empty($element->plaintext)) {
                            $apt_data[$c]['address'] = $element->plaintext;
                        }
                    }
                    foreach ($li_html->find('span.listingFreshness') as $element) {
                        if (!empty($element->plaintext)) {
                            $apt_data[$c]['added_time'] = $element->plaintext;
                        }
                    }
                    foreach ($li_html->find('span.altRentDisplay') as $element) {
                        if (!empty($element->plaintext)) {
                            $rent = $element->plaintext;
                            $rent = strtok($rent, ' ');
                            $rent = preg_replace('/[$,\,]/','',$rent);
                            $apt_data[$c]['rent'] = $rent;
                        }
                    }
                    foreach ($li_html->find('span.unitLabel') as $element) {
                        if (!empty($element->plaintext)) {
                            $apt_data[$c]['beds'] = $element->plaintext;
                        }
                    }
                    foreach ($li_html->find('span.availabilityDisplay') as $element) {
                        if (!empty($element->plaintext)) {
                            $apt_data[$c]['availability'] = $element->plaintext;
                        }
                    }
                    foreach ($li_html->find('div.phone') as $element) {
                        if (!empty($element->plaintext)) {
                            $apt_data[$c]['phone'] = $element->plaintext;
                        }
                    }
                    $c++;
                }

            }
            foreach ($apt_data as $mysql_data) {
                $check = self::$db->selectOne('SELECT `id` FROM `' . self::$tableName . '` WHERE `address`="' . $mysql_data['address'] . '"');
                if (!$check) {
                    $insert = self::$db->insert(self::$tableName, $mysql_data);
                    echo 'inserted<br/>';
                } else {
                    echo 'duplicate<br/>';
                }
            }
//            Console::varDump($apt_data);
        }
    }

}

?>
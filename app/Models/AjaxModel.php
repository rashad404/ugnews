<?php
namespace Models;
use Core\Model;
use Core\Language;

class AjaxModel extends Model{

    private static $tableName = 'apartments';
    public $lng;
    public function __construct(){
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
    }



    public static function countyListByState($id){
        $data = '<option value="0">Select County</option>';
        $array = self::$db->select("SELECT `id`,`county_name` FROM `us_counties` WHERE `state_id`= '".$id."'ORDER BY `ID` ASC");
        foreach ($array as $item) {
            $data .= '<option value="'.$item['id'].'">'.$item['county_name'].'</option>';
        }
        return $data;
    }

    public static function cityListByCounty($id){
        $data = '<option value="0">Select City</option>';
        $array = self::$db->select("SELECT `id`,`city_name` FROM `us_cities` WHERE `county_id`= '".$id."'ORDER BY `ID` ASC");
        foreach ($array as $item) {
            $data .= '<option value="'.$item['id'].'">'.$item['city_name'].'</option>';
        }
        return $data;
    }
    public static function locationSearchList($text){
        $data = "<script>
            $('.locationDropLi').click(function () {
                var name = $(this).text();
                var category = $(this).attr('category');
    
                if(name.length>=3) {
                    $('#locationDropDown').hide();
                    $('#search_location_input').val(name);
                    $('#search_location_input').attr('category',category);
                }
            });
        </script>";
        $data .= '<ul>';

        $array = self::$db->select("SELECT `id`,`state_code`,`state_name` FROM `us_states` WHERE `state_name` LIKE '%".$text."%'ORDER BY `id` ASC LIMIT 10");
        foreach ($array as $item) {
            $data .= '<li class="locationDropLi" category="state">'.$item['state_name'].' ('.$item['state_code'].')</li>';
        }
        $array = self::$db->select("SELECT `id`,`city_name`,`state_code`,`county_name` FROM `us_cities` WHERE `city_name` LIKE '%".$text."%'ORDER BY `id` ASC LIMIT 10");
        foreach ($array as $item) {
            if($item['city_name']==$item['county_name']){
                $county_name = '';
            }else{
                $county_name = ', '.$item['county_name'].' county';
            }
            $data .= '<li class="locationDropLi" category="city">'.$item['city_name'].''.$county_name.', '.$item['state_code'].'</li>';
        }
        $data .= '</ul>';
        return $data;
    }

}

<?php
namespace Models;
use Core\Model;
use Core\Language;

class LocationModel extends Model{

    private static $tableNameStates = 'us_states';
    private static $tableNameCities = 'us_cities';

    public $lng;
    public function __construct(){
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
    }




    public static function getParamsFromText($text){
        $array = [];
        if(isset($text) && strlen($text)>3) {
            if (preg_match('/([a-zA-Z]+) \(([A-Z]{2})\)/', $text, $matches)) {
                $state_name = $matches[1];
                $state_code = $matches[2];
                $state_q = self::$db->selectOne("SELECT `id` FROM `" . self::$tableNameStates . "` WHERE `state_code`='" . $state_code . "'");
                $array['state_id'] = $state_q['id'];
            } elseif (preg_match('/([a-zA-Z\s]+), ([a-zA-Z\s]+) county, ([A-Z]{2})/', $text, $matches)) {
                $city_name = $matches[1];
                $county_name = $matches[2];
                $state_code = $matches[3];
                $city_q = self::$db->selectOne("SELECT `id` FROM `" . self::$tableNameCities . "` WHERE `city_name`='" . $city_name . "' AND `county_name`='" . $county_name . "' AND `state_code`='" . $state_code . "'");
                $array['city_id'] = $city_q['id'];
            } elseif (preg_match('/([a-zA-Z\s]+), ([A-Z]{2})/', $text, $matches)) {
                $city_name = $matches[1];
                $county_name = $city_name;
                $state_code = $matches[2];
                $city_q = self::$db->selectOne("SELECT `id`,`county_id`,`state_id` FROM `" . self::$tableNameCities . "` WHERE `city_name`='" . $city_name . "' AND `county_name`='" . $county_name . "' AND `state_code`='" . $state_code . "'");
                $array['county_id'] = $city_q['county_id'];
                $array['state_id'] = $city_q['state_id'];
            }
        }
        return $array;
    }

}

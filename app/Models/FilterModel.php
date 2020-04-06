<?php
namespace Models;
use Core\Model;
use Helpers\Database;
use Helpers\Security;
use Helpers\Session;
use Helpers\Url;

class FilterModel extends Model{
    protected static function getPost()
    {
        extract($_POST);
        $array = [];
        $skip_list = ['csrf_token', 'filter'];
        foreach($_POST as $key=>$value){
            if (in_array($key, $skip_list)) continue;
            if(is_array($_POST[$key])){
                $new_array = [];
                foreach($_POST[$key] as $key2 => $value2) {
                    $new_array[]  = $key2;
                }
                $array[$key] = implode($new_array,", ");
            }else {
                $array[$key] = $_POST[$key];
            }
        }
        return $array;
    }


    public static function setFilters($category='', $data = ''){

        if(empty($data)) {
            $data = self::getPost();
        }
        Session::set('filters'.$category,json_encode($data));
    }
    public static function resetFilters($category=''){

        Session::destroy('filters'.$category);
    }

    public static function getFilters($category='')
    {
        if(Session::check('filters'.$category)) {
            $session_array = json_decode(Session::get('filters'.$category), true);
        }else{
            $session_array = [];
        }
        return $session_array;
    }
}

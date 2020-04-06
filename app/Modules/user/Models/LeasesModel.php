<?php

namespace Modules\user\Models;

use Core\Model;
use Helpers\Console;
use Helpers\Date;
use Helpers\Format;
use Helpers\Security;
use Helpers\Session;

class LeasesModel extends Model
{

    private static $tableName = 'leases';
    private static $tableNamePages = 'lease_pages';

    private static $rules;
    private static $rulesUpdate;
    private static $params;
    private static $user_id;
    private static $partner_id;

    public function __construct($params = '')
    {
        parent::__construct();
        self::$rules = [
            'first_name' => ['min_length(2)', 'max_length(30)'],
            'last_name' => ['min_length(2)', 'max_length(30)'],
        ];

        self::$rulesUpdate = [
            'rent' => ['amount'],
            'deposit' => ['amount'],
            'app_fee' => ['amount'],
            'prorated_rent' => ['amount'],
            'bed_id' => ['integer'],
            'start_date' => ['min_length(2)', 'max_length(30)'],
            'end_date' => ['min_length(2)', 'max_length(30)'],
        ];
        self::$params = $params;
        self::$user_id = Session::get('user_session_id');
        $user_info = UserModel::getItem(self::$user_id);
        self::$partner_id = $user_info['partner_id'];
    }

    public static function naming()
    {
        return [];
    }


    protected static function getPost()
    {
        extract($_POST);
        $skip_list = ['csrf_token', 'image'];
        $array = [];
        foreach ($_POST as $key => $value) {
            if (in_array($key, $skip_list)) continue;
            $array[$key] = Security::safe($_POST[$key]);
        }
        return $array;
    }


    public static function sign($page_id){
        $return = [];
        $return['errors'] = null;
        $update_data = [
            'user_sign'=>1,
            'user_ip'=>Security::getIp(),
            'user_ua'=>Security::getBrowser(),
            'user_sign_time'=>time(),
            ];

        $where = [
            'id'=>$page_id,
            'user_id'=>self::$user_id,
            'partner_id'=>self::$partner_id,
        ];
        $update = self::$db->update(self::$tableNamePages, $update_data, $where);
        if($update){
            $return['errors'] = null;
        }else{
            $return['errors'] = 'Error, Please contact to landlord';
        }
        return $return;
    }
    public static function signFinal($lease_id){
        $return = [];
        $return['errors'] = null;

        $array = self::$db->selectOne("SELECT `id` FROM ".self::$tableNamePages." WHERE `user_sign`=0 AND `user_id`='" . self::$user_id . "' AND `partner_id`='" . self::$partner_id . "' AND `lease_id`=".$lease_id);
        if($array){
            $return['errors'] = 'Error: Please sign previous pages';
            return $return;
        }

        $update_data = [
            'user_sign'=>1,
            'user_ip'=>Security::getIp(),
            'user_ua'=>Security::getBrowser(),
            'user_sign_time'=>time(),
            ];

        $where = [
            'id'=>$lease_id,
            'user_id'=>self::$user_id,
            'partner_id'=>self::$partner_id,
        ];
        $update = self::$db->update(self::$tableName, $update_data, $where);
        if($update){
            $return['errors'] = null;
        }else{
            $return['errors'] = 'Error, Please contact to landlord';
        }
        return $return;
    }

    public static function getInitials($lease_id){
        $lease_id = intval($lease_id);
        $array = self::$db->selectOne("SELECT `id`,`user_first_name`,`user_last_name` FROM ".self::$tableName." WHERE `user_id`='" . self::$user_id . "' AND `partner_id`='" . self::$partner_id . "' AND `id`=".$lease_id);
        $first_name = $array['user_first_name'];
        $last_name = $array['user_last_name'];
        $initial1 = substr($first_name, 0, 1);
        $initial2 = substr($last_name, 0, 1);
        return $initial1.' '.$initial2;
    }

    public static function getSign($lease_id){
        $lease_id = intval($lease_id);
        $array = self::$db->selectOne("SELECT `id`,`user_first_name`,`user_last_name` FROM ".self::$tableName." WHERE `user_id`='" . self::$user_id . "' AND `partner_id`='" . self::$partner_id . "' AND `id`=".$lease_id);
        $first_name = $array['user_first_name'];
        $last_name = $array['user_last_name'];
        return $first_name.' '.$last_name;
    }

    public static function getNextPage($page_id){
        $page_id = intval($page_id);
        $array = self::$db->selectOne("SELECT `id` FROM ".self::$tableNamePages." WHERE `user_id`='" . self::$user_id . "' AND `partner_id`='" . self::$partner_id . "' AND `id`>".$page_id);
        return $array['id'];
    }

    public static function getPreviousPage($page_id){
        $page_id = intval($page_id);
        $array = self::$db->selectOne("SELECT `id` FROM ".self::$tableNamePages." WHERE `user_id`='" . self::$user_id . "' AND `partner_id`='" . self::$partner_id . "' AND `id`<".$page_id);
        return $array['id'];
    }

    public static function countList(){
        $count = self::$db->selectOne("SELECT count(`id`) as countList FROM ".self::$tableName);
        return $count['countList'];
    }

    public static function getList($limit = 'LIMIT 0,10')
    {
        return self::$db->select("SELECT * FROM " . self::$tableName . " WHERE `user_id`='" . self::$user_id . "' AND `partner_id`='" . self::$partner_id . "' ORDER BY `id` DESC $limit");
    }

    public static function getPages($lease_id)
    {
        return self::$db->select("SELECT * FROM " . self::$tableNamePages . " WHERE `lease_id`='" . $lease_id . "' ORDER BY `id` ASC");
    }

    public static function getItem($id)
    {
        return self::$db->selectOne("SELECT * FROM " . self::$tableName . " WHERE `id`='" . $id . "'");
    }

    public static function getPage($lease_id, $id)
    {
        if ($id == 0) {
            return self::$db->selectOne("SELECT `id`,`title`,`text`,`user_sign` FROM " . self::$tableNamePages . " WHERE `lease_id`='" . $lease_id . "' ORDER BY `id` ASC");
        } else {
            return self::$db->selectOne("SELECT `id`,`title`,`text`,`user_sign` FROM " . self::$tableNamePages . " WHERE `id`='" . $id . "'");
        }
    }


    public static function replaceVariables($text, $lease_id)
    {
        $lease_info = self::getItem($lease_id);
        $bed_info = BedsModel::getItem($lease_info['bed_id']);

        $bed_name = BedsModel::getName($bed_info['id']);

        $apt_id = $bed_info['apt_id'];
        $apt_name = ApartmentsModel::getAddress($apt_id);

        $room_id = $bed_info['room_id'];
        $room_name = RoomsModel::getName($room_id);

        $apt_address = $apt_name . ', ' . $room_name . ' ' . $bed_name;

        $user_name = $lease_info['user_first_name'] . ' ' . $lease_info['user_middle_name'] . ' ' . $lease_info['user_last_name'];

        $rent_amount = $lease_info['rent'];
        $prorated_rent = $lease_info['prorated_rent'];

        $text = preg_replace('/\$tenant_name/', '<span style="font-weight: bold">' . $user_name . '</span>', $text);
        $text = preg_replace('/\$lease_start_date/', '<span style="font-weight: bold">' . Date::toInputFormat($lease_info['start_date']) . '</span>', $text);
        $text = preg_replace('/\$lease_end_date/', '<span style="font-weight: bold">' . Date::toInputFormat($lease_info['end_date']) . '</span>', $text);
        $text = preg_replace('/\$apt_address/', '<span style="font-weight: bold">' . $apt_address . '</span>', $text);
        $text = preg_replace('/\$room_name/', '<span style="font-weight: bold">' . $room_name . '</span>', $text);
        $text = preg_replace('/\$rent_amount/', '<span style="font-weight: bold">' . $rent_amount . '</span>', $text);
        $text = preg_replace('/\$prorated_rent/', '<span style="font-weight: bold">' . $prorated_rent . '</span>', $text);
        return $text;
    }



    public static function getBedOptions()
    {
        $list = [];
        $list[] = ['key' => 0, 'name' => '---', 'disabled' => ''];
        new ApartmentsModel();
        $apt_array = ApartmentsModel::getList();
        foreach ($apt_array as $apt) {
            $room_array = RoomsModel::getList($apt['id']);
            foreach ($room_array as $room) {
                $name = $apt['name'];
                $list[] = ['key' => '', 'name' => $name, 'disabled' => 'disabled'];
                $bed_array = BedsModel::getListByRoom($room['id']);
                foreach ($bed_array as $bed) {
                    $list[] = ['key' => $bed['id'], 'name' => $name . ', ' . $room['name'] . ' ' . $bed['name'], 'disabled' => ''];
                }
            }
        }
        return $list;
    }
}

?>
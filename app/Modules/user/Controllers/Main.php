<?php
namespace Modules\user\Controllers;

use Helpers\Database;
use Helpers\Session;
use Core\View;
use Helpers\Url;
use Helpers\SimpleImage;
use Modules\partner\Models\ApartmentsModel;
use Modules\partner\Models\BedsModel;
use Modules\partner\Models\RoomsModel;
use Modules\user\Models\NoticesModel;
use Modules\user\Models\UserModel;

class Main extends MyController
{

    public static $imageFolder = 'resmenu';
    public static $user_id;

    public function __construct()
    {
        parent::__construct();
        self::$user_id = Session::get('user_session_id');
        new UserModel();
    }

    public function index()
    {
    	$user_data = UserModel::getItem(self::$user_id);
        $user_data['apt_name'] = '';
        $user_data['room_name'] = '';
        $user_data['bed_name'] = '';
        $user_data['apt_address'] = '';
        if($user_data['apt_id']>0)$user_data['apt_name'] = ApartmentsModel::getName($user_data['apt_id']);
        if($user_data['apt_id']>0)$user_data['apt_address'] = ApartmentsModel::getAddress($user_data['apt_id']);
        if($user_data['room_id']>0)$user_data['room_name'] = RoomsModel::getName($user_data['room_id']);
        if($user_data['bed_id']>0)$user_data['bed_name'] = BedsModel::getName($user_data['bed_id']);

        $new_notices = NoticesModel::getNewNotices();
        $data = [
            'user_data'=>$user_data,
            'user_id'=>self::$user_id,
            'new_notices'=>$new_notices
        ];
    	View::renderUser('main/index', $data);
    }

    public function resmenu(){
        if($_GET){
            $new_dir = Url::uploadPath() . self::$imageFolder;
            $file_arr = explode('.', $_FILES['resmenu']['name']);
            $ext = end($file_arr);
            $destination_original = $new_dir . "/restoran_menu." . $ext;
            $img = new SimpleImage();
            $img->uploadFile('resmenu',$destination_original);
            $sql_img = self::$imageFolder . "/restoran_menu." . $ext;
            Database::get()->update('contacts', ['resmenu' => $sql_img], ['id' => 1]);
            Url::redirect('admin/main/index');
        }
    }

    public function saveBlob(){
        if($_FILES["file_1"]["tmp_name"]){
            $new_dir = Url::uploadPath().self::$imageFolder;
            $file_arr = explode('.', $_FILES['file_1']['name']);
            $ext = end($file_arr);
            $destination_original = $new_dir . "/restoran_menu." . $ext;
            move_uploaded_file($_FILES["file_1"]["tmp_name"], $destination_original);
            $sql_img = self::$imageFolder . "/restoran_menu." . $ext;
            Database::get()->update('contacts', ['resmenu' => $sql_img], ['id' => 1]);
            $rm = Database::get()->selectOne("SELECT `resmenu` FROM `contacts` WHERE `id`=1 ")['resmenu'];
            echo json_encode(\Helpers\Url::filePath().$rm);
        }
    }

    public function logout()
    {
        Session::destroy('',true);
        return Url::redirect("login");

    }
    public function profile()
    {
        return Url::redirect("user_panel/profile");

    }
}
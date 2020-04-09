<?php
namespace Modules\partner\Controllers;

use Helpers\Database;
use Helpers\Session;
use Core\View;
use Core\Router;
use Helpers\Url;
use Modules\partner\Models\GalleryModel;
use Modules\partner\Models\SliderModel;
use Modules\partner\Models\NewsModel;
use Modules\partner\Models\MenusModel;
use Modules\partner\Models\ProjectsModel;
use Helpers\File;
use Helpers\SimpleImage;
use Modules\partner\Models\VideoModel;

class Main extends MyController
{

    public static $imageFolder = 'resmenu';

    public function __construct()
    {

        parent::__construct();
    }

    public function index()
    {
    	$data = [];
        Url::redirect(MODULE_PARTNER.'/news/index');
    	View::renderPartner('main/index', $data);
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
            Url::redirect(MODULE_PARTNER.'/main/index');
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
}
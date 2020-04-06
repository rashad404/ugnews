<?php
/**
 * Site controller
 *
 * @author David Carr - dave@daveismyname.com
 * @version 2.2
 * @date June 27, 2014
 * @date updated Sept 19, 2015
 */

namespace Modules\partner\Models;

use Core\Logger;
use Core\Model;
use Helpers\Database;
use Helpers\File;
use Helpers\Session;
use Helpers\SimpleImage;
use Helpers\Url;


/**
 * Sample controller showing a construct and 2 methods and their typical usage.
 */

class PhotoUploaderModel extends Model
{
    /**
     * Call the parent construct
     */
    public static $tableName = 'product_photos';
    public static $upload_path = 'product_photos';
    public static $images_folder = '/images/';
    public static $thumbs_folder = '/thumbs/';

    public static $thumbWidth = 500;//340
    public static $thumbHeight = 400;//270

    public static $imageWidth = 500;//340
    public static $imageHeight = 400;//270

    /**
     * Define Index page title and load template files
     */

    public static function addImage($product_id)
    {
        $return_arr = [];

        // function for re arraying files
        function reArrayFiles(&$file_post)
        {

            $file_ary = array();
            $file_count = count($file_post['name']);
            $file_keys = array_keys($file_post);

            for ($i = 0; $i < $file_count; $i++) {
                foreach ($file_keys as $key) {
                    $file_ary[$i][$key] = $file_post[$key][$i];
                }
            }

            return $file_ary;
        }

        // create table folder if not exists
        if(!is_dir(Url::uploadPath().self::$upload_path)) {
            File::makeDir(Url::uploadPath().self::$upload_path);
        }

        if (isset($_FILES['files'])) {


            $new_dir = Url::uploadPath().self::$upload_path.self::$images_folder;
            $new_thumb_dir = Url::uploadPath().self::$upload_path.self::$thumbs_folder;

            if(File::makeDir($new_dir) and File::makeDir($new_thumb_dir)){
                $file_ary = reArrayFiles($_FILES['files']);
                $elements = array();
                foreach ($file_ary as $file) {
                    $file_tmp = $file['tmp_name'];

                    $file_name = $file['name'];
                    $file_type = $file['type'];
                    $file_size = $file['size'];
                    $uni_id = Session::get('uni_id');

                    //print_r(getimagesize($file_tmp)); exit;
                    //creating thumbnail*******************
                    $rand_name_1 = rand(1000000, 9999999);
                    $rand_name_2 = rand(1000000, 9999999);
                    $rand_name = $rand_name_1.$rand_name_2;
                    $photo_type = 'jpeg';//butun shekilleri jpeg edeceyik
                    $image1 = new SimpleImage($file_tmp);
                    $image1->resize(self::$imageWidth,self::$imageHeight); //thumbnail idi
                    //$image1->text(SITE_NAME, 'app/templates/main/fonts/Chunkfive.otf', 35, '#EEEEEE', 'center', 0.3, 0, 0);
                    $image1->save($new_dir . $rand_name . '.' . $photo_type);

                    $image2 = new SimpleImage($new_dir . $rand_name . '.' . $photo_type);
                    $image2->resize(self::$thumbWidth,self::$thumbHeight); //adaptive size idi
                    //$image2->text(SITE_NAME, 'app/templates/main/fonts/Chunkfive.otf', 10, '#EEEEEE', 'bottom right', 0.8, 0, -2);
                    $image2->save($new_thumb_dir . $rand_name . '.' . $photo_type);


                    $image_name = $rand_name . '.' . $photo_type;

                    $elements[] = $image_name;

                    $insert_data = ['product_id'=>$product_id, 'create_time' => time(),
                        'image' => self::$upload_path.self::$images_folder.$image_name,
                        'thumb' => self::$upload_path.self::$thumbs_folder.$image_name,
                        'status' => 1
                    ];

                    $insert = Database::get()->insert(self::$tableName, $insert_data);

                    $return_arr[] = ['id' => $insert, 'image_name' => $image_name];
                }

                $files = implode('~~~', $elements);
                echo json_encode($return_arr);
                //Session::setFlash('success','Şəkil əlavə olundu');

            }else{
                echo '~~~problem';
            }

        } else {
            echo 'upload file';
//            header('Location: index.php');
        }
    }

    public static function deleteImage($product_id)
    {
        $photo_id = $_POST['thumbImageId'];
        //$uni_id = Session::get('uni_id');
        $find = Database::get()->selectOne("SELECT `id`, `thumb`, `image` FROM `".self::$tableName."` WHERE `id` = :id AND `product_id`= :product_id", [':id' => $photo_id, 'product_id'=> $product_id]);
        if($find) {
            @unlink(Url::uploadPath().$find['image']);
            @unlink(Url::uploadPath().$find['thumb']);
            Database::get()->raw("DELETE FROM `".self::$tableName."` WHERE `id` = '".$photo_id."' AND `product_id`= '".$product_id."'");

        }


    }

    public static function rotateImage($product_id)
    {
//        $image = $_POST['thumbImage'];
        $photo_id = $_POST['thumbImageId'];
        $action = $_POST['action'];
        $degrees = 0;
        if($action=='left'){
            $degrees = -90;
        }elseif ($action=='right'){
            $degrees = 90;
        }

        $find = Database::get()->selectOne("SELECT `id`, `thumb`, `image` FROM `".self::$tableName."` WHERE `id` = :id AND `product_id`= :product_id ", [':id' => $photo_id, 'product_id'=> $product_id]);
        if($find) {
            $image1 = new SimpleImage();
            $image2 = new SimpleImage();

            $image1->load(Url::uploadPath().$find['image']);
            $image1->rotate($degrees);
            $image1->save(Url::uploadPath().$find['image']);

            $image2->load(Url::uploadPath().$find['thumb']);
            $image2->rotate($degrees);
            $image2->save(Url::uploadPath().$find['thumb']);
            echo 'rotated';
        } else {
            echo 'not found';
        }

    }

    public static function getAll($product_id)
    {
        $rows = Database::get()->select("SELECT * FROM `".self::$tableName."` WHERE `product_id` = :product_id ", [':product_id' => $product_id]);
        return $rows;
    }

}

<?php

namespace Helpers;
use Core\Language;
use Helpers\File;

class FileUploader
{
    private static $minSize = 5;
    private static $maxSize = 10000;
    public static $lng;
    public function __construct() {
        self::$lng = new Language();
        self::$lng->load('app');
    }

    public static function imageResizeProportional($file, $destination, $quality=80, $max_width=200, $max_height=200) {

        $image_info = getimagesize($file);
        $width_orig = $image_info[0];
        $height_orig = $image_info[1];
        $image_type = $image_info[2];

        $ratio_orig = $width_orig/$height_orig;

        if ($max_width/$max_height > $ratio_orig) {
            $width = $max_height*$ratio_orig;
            $height = $max_height;
        } else {
            $width = $max_width;
            $height = $max_width/$ratio_orig;
        }

        $image_p = imagecreatetruecolor($width, $height);
        if( $image_type == IMAGETYPE_GIF ) {
            $image = imagecreatefromgif($file);
        }elseif( $image_type == IMAGETYPE_PNG ) {
            $image = imagecreatefrompng($file);
        }else{
            $image = imagecreatefromjpeg($file);
        }
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

        imagejpeg($image_p, $destination, $quality);
        imagedestroy($image_p);

        return true;
    }
    public static function imageResize($file, $destination, $quality=80, $width=150, $height=150) {

        $image_info = getimagesize($file);
        $width_orig = $image_info[0];
        $height_orig = $image_info[1];
        $image_type = $image_info[2];

        $ratio_orig = $width_orig/$height_orig;

        $image_p = imagecreatetruecolor($width, $height);
        if( $image_type == IMAGETYPE_GIF ) {
            $image = imagecreatefromgif($file);
        }elseif( $image_type == IMAGETYPE_PNG ) {
            $image = imagecreatefrompng($file);
        }else{
           $image = @imagecreatefromjpeg($file);
        }
        imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);

        imagejpeg($image_p, $destination, $quality);
        imagedestroy($image_p);

        return true;
    }


    public static function imageUpload($name='', $dir, $input = "file", $quality=80, $width=500, $height=500)
    {
        $error = '';
        $imageFileType = strtolower(pathinfo($_FILES[$input]["name"],PATHINFO_EXTENSION));

        File::makeDir(Url::uploadPath().$dir);

        if(empty($name)){
            $name = Session::get("user_session_id");
        }
        $target_file = Url::uploadPath() . $dir.'/'.$name.'.jpg';

        $check = getimagesize($_FILES[$input]["tmp_name"]);
        if($check === false) {
            $error .= self::$lng->get("Your file is not an image.")."<br/>";
        }

        if ($_FILES[$input]["size"] < self::$minSize*1024) {
            $error .= self::$lng->get("Your file is too small. Min.:")." ".self::$minSize.self::$lng->get("kb")."<br/>";
        }
        if ($_FILES[$input]["size"] > self::$maxSize*1024) {
            $error .= self::$lng->get("Your file is too large. Max.:")." ".self::$maxSize.self::$lng->get("kb")."<br/>";
        }

        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            $error .=  self::$lng->get("Only JPG, JPEG, PNG & GIF files are allowed.")."<br/>";
        }


        if(empty($error)) {
//            if (move_uploaded_file($_FILES[$input]["tmp_name"], $target_file)) {
            if (self::imageResizeProportional($_FILES[$input]["tmp_name"], $target_file, $quality, $width, $height)) {
                return ['success'=>1,'error'=>''];
            } else {
                $error .= self::$lng->get("Sorry, there was an error uploading your file.")."<br/>";
            }
        }

        return ['success'=>0,'error'=>$error];
    }
}

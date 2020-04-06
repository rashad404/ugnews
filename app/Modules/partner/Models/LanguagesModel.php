<?php

namespace Modules\partner\Models;

use Core\Model;
use Helpers\Database;
use Helpers\File;

class LanguagesModel extends Model{


    public static $tableName = 'languages';


    public function __construct(){
        parent::__construct();
    }


    public static function addlanguages($new_lang)
    {
        if(empty($new_lang)){
            return false;
        }

        $directory = "app/Modules/partner/Models/";

        $db = Database::get();
        $defaultLang = \Models\LanguagesModel::getDefaultLanguage();
        $queryAll = '';
        $files = glob($directory . "*Model.{php}",GLOB_BRACE);
        foreach($files  as $file){
            $exp = explode("/",$file);
            $fileName = end($exp);
            $fileName = substr($fileName,0,-4);
            if(property_exists('\Modules\partner\Models\\'.$fileName,'fields')) {
                $class='\Modules\partner\Models\\'.$fileName;
                $fields = $class::$fields;
                $query = '';
                $i = 1;
                foreach($fields as $field){
                    if($i>1) $query.=",";
                    $after = $field["field_name"]."_".$defaultLang;
                    $query .= " ADD ".$field["field_name"]."_".$new_lang." ".$field["field_type"]." CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL after ".$after;
                    $i++;
                }

                if(!empty($query)){
                    $tableName = $class::$tableName;
                    $queryAll .=  "ALTER TABLE ".$tableName." ".$query.";";
                }
            }
        }

        $defaultLangPath = './app/language/'.$defaultLang;
        $newLangPath = './app/language/'.$new_lang;
        File::recurse_copy($defaultLangPath,$newLangPath);

        $db->query($queryAll);
        return true;
    }

    public static function deleteLanguages($lang_name)
    {
        $directory = "app/Modules/partner/Models/";

        $db = Database::get();
        $defaultLang = \Models\LanguagesModel::getDefaultLanguage();
        $queryAll = '';
        $files = glob($directory . "*Model.{php}",GLOB_BRACE);
        foreach($files  as $file){
            $exp = explode("/",$file);
            $fileName = end($exp);
            $fileName = substr($fileName,0,-4);
            if(property_exists('\Modules\partner\Models\\'.$fileName,'fields')) {
                $class='\Modules\partner\Models\\'.$fileName;
                $fields = $class::$fields;
                $query = '';
                $i = 1;
                foreach($fields as $field){
                    if($i>1) $query.=",";

                    $query .= " DROP ".$field["field_name"]."_".$lang_name;
                    $i++;
                }

                if(!empty($query)){
                    $tableName = $class::$tableName;
                    $queryAll .=  "ALTER TABLE ".$tableName." ".$query.";";
                }
            }
        }

        $langPath ='./app/language/'.$lang_name;
        File::rmDir($langPath);

        $db->query($queryAll);
        return true;
    }


}

?>
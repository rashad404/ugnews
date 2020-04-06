<?php
namespace Modules\admin\Controllers;

use Helpers\Cache;
use Helpers\Csrf;
use Helpers\Database;
use Helpers\File;
use Helpers\Operation;
use Helpers\Slim;
use Helpers\Security;
use Helpers\Session;
use Helpers\SimpleImage;
use Helpers\Validator;
use Models\LanguagesModel;
use Core\View;
use Helpers\Url;

class About extends MyController
{

    public static $safeMode = false;  // silinmemeli olan rowlarin mudafieso
    public static $safeModeFields = ["safe_mode"]; // siline bilmeyen rowlarin nezere alinmali fieldi

    public static $positionEnable=false;     // Siralama aktiv, deaktiv
    public static $positionOrderBy  = 'ASC'; // Siralama ucun order
    public static $positionCondition = true;    // siralanma zamani nezere alinacaq fieldlerin olub olmamasi
    public static $positionConditionField = ['parent_id']; // siralanma zamani nezere alinacaq fieldler

    public static $statusMode = true; // Melumatlarin aktiv deaktiv edile bilmesi (status) field
    public static $crudMode = true; // emeliyyatlar bolmesinin gorsenib gorsenmemesi (operations) fields

    public static $issetImage = true;
    public static $requiredImage = true;
    public static $imageFolder = 'about';

    public static $issetAlbum = false;

    public $operation;
    public static $rules;

    public $dataParams = [
    ];
    public static function naming(){
        return [];
    }

    public function getDataParams()
    {
        $this->dataParams = [
            "cName" => "about",
            "cModelName" => "AboutModel",
            "cTitle" => "Haqqımızda",
            "cStatusMode" => self::$statusMode,
            "cPositionEnable" => self::$positionEnable,
            "cCrudMode" => self::$crudMode,

        ];
        return $this->dataParams;
    }

    public function __construct()
    {

        $this->getDataParams();
        $this->operation = new Operation();
        $this->operation->tableName = $this->dataParams["cName"];
        self::$rules =  ['text_'.$this->defaultLanguage() => ['required']];
        parent::__construct();

        if(parent::accessControl(['create', 'update', 'delete'], $this->dataParams["cName"]) == true) {
            return Url::redirect(MODULE_ADMIN);

        }
    }

    public function update($id)
    {
        $model = $this->operation->findModel($id);
        $defaultLang = $this->defaultLanguage();

        if(isset($_POST["submit"]) && Csrf::isTokenValid() ){

            $postArray = $this->getPost('update');
            $model = $postArray;
            $validator = Validator::validate($postArray,self::$rules, self::naming());
            if($validator->isSuccess()){

                $update =  Database::get()->update($this->dataParams["cName"],$postArray,["id" => $id]);
                $images = Slim::getImages('image');

                if($update || $images){
                    $image = $images[0];
                    if(!empty($image)){
                        $this->imageUpload($image, $id);
                    }

                    Session::setFlash('success','Məlumatlar yadda saxlanıldı');
                    return Url::redirect(MODULE_ADMIN."/".$this->dataParams["cName"]."/update/1");
                }else{
                    Session::setFlash('error','Heç bir məlumat dəyişdirilməyib (DB)');
                }
            }else {
                $msg = '';
                foreach ($validator->getErrors() as $error) {
                    $msg .= $error;
                }
                Session::setFlash('error', $msg);

            }
        }


        View::renderModule($this->dataParams["cName"].'/update',[
            'dataParams' => $this->getDataParams(),
            'model' => $model,
            'defaultLang' => $defaultLang
        ]);
    }


    // for slim cropper
    protected function imageUpload($image, $id)
    {
        $new_dir = Url::uploadPath().self::$imageFolder.'/'.$id;
        $new_thumb_dir = Url::uploadPath().self::$imageFolder.'/'.$id.'/thumbs';

        File::makeDir($new_dir);
        File::makeDir($new_thumb_dir);

        $new = Slim::saveFile($image['output']['data'], $id.'_0.png', $new_dir, false);

        $file_arr = explode('.', $new['name']);
        $ext = end($file_arr);
        $destination = $new_thumb_dir."/" . $id."_0.".$ext;

        $img = new SimpleImage();

        $img->load($new['path'])->resize(390, 300)->save($destination);

        $sql_img = self::$imageFolder.'/'.$id.'/' . $id."_0.".$ext;
        $sql_thumb_img = self::$imageFolder.'/'.$id.'/thumbs/' . $id."_0.".$ext;
        Database::get()->update($this->dataParams["cName"], ['image' => $sql_img, 'thumb' => $sql_thumb_img], ['id' => $id]);

    }

    protected function getPost($action = 'create')
    {
        $languages = LanguagesModel::getLanguages();
        extract($_POST);
        $array = [];
        foreach($languages as $lang){
            $title = "text_".$lang["name"];
            $array[$title] = Security::safe($$title);

            if($array[$title]=="&lt;p&gt;&lt;br&gt;&lt;/p&gt;"){
                $array[$title]="";
            }
        }

        return $array;
    }


}
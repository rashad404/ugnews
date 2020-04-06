<?php
namespace Modules\partner\Controllers;

use Core\Language;
use Helpers\Cache;
use Helpers\Console;
use Helpers\Csrf;
use Helpers\Database;
use Helpers\File;
use Helpers\FileCache;
use Helpers\FileUploader;
use Helpers\Operation;
use Helpers\Pagination;
use Helpers\Slim;
use Helpers\Security;
use Helpers\Session;
use Helpers\SimpleImage;
use Helpers\Validator;
use Modules\partner\Models\ApartmentsModel;
use Modules\partner\Models\GalleryModel;
use Models\LanguagesModel;
use Core\View;
use Helpers\Url;

class Apartments extends MyController
{
    public static $controllerName = 'apartments';
    public static $controllerTitle = 'Apartments';

    public static $imageSizeX = 1200;
    public static $imageSizeY = 800;
    public static $thumbSizeX = 600;
    public static $thumbSizeY = 400;
    public static $imageOpt = 80;

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

    public static $issetAlbum = false;
    public static $lng;
    public static $partner_id;
    public $operation;

    public static $rules;
//'title_'.self::$def_language => ['required',],
    public $dataParams = [
    ];

    public function getDataParams()
    {
        $this->dataParams = [
            "cName" => self::$controllerName,
            "cModelName" => ucfirst(self::$controllerName)."Model",
            "cTitle" => self::$controllerTitle,
            "cStatusMode" => self::$statusMode,
            "cPositionEnable" => self::$positionEnable,
            "cCrudMode" => self::$crudMode,

        ];
        return $this->dataParams;
    }

    public function __construct()
    {
        new ApartmentsModel();
        $this->getDataParams();
        $this->operation = new Operation();
        $this->operation->tableName = $this->dataParams["cName"];
        self::$def_language = LanguagesModel::getDefaultLanguage('partner');
        self::$lng = new Language();
        self::$lng->load('partner');
        self::$rules = ['title_'.self::$def_language.'' => ['required']];
        parent::__construct();

        if(parent::accessControl(['create', 'update', 'delete', 'album'], $this->dataParams["cName"]) == true) {
            return Url::redirect('partner');
        }
        self::$partner_id = Session::get('user_session_id');
    }

    public function index()
    {
        $values = ["id"=> '', "title"=> '',"unvani"=> '', "status"=>'', "page"=>"index"];
        $rows = Database::get()->select("SELECT * FROM {$this->dataParams['cName']} WHERE `partner_id`='".self::$partner_id."'");
        View::renderPartner($this->dataParams["cName"].'/index',[
            'dataParams' => $this->getDataParams(),
            'rows' => $rows,
            'values' => $values,
        ]);
    }

    public function search(){
        if($_POST){
            $word = $_POST['word'];
            $rows = $this->searchLikeFor($this->dataParams["cName"],['id','title_az','text_az'],$word);
        } else {
            $rows = Database::get()->select("SELECT * FROM `".$this->dataParams["cName"]."` WHERE `status`=1 ");
        }
        $pagination = new Pagination();
        View::renderPartner($this->dataParams["cName"].'/index',[
            'dataParams' => $this->getDataParams(),
            'rows' => $rows,
            'pagination' => $pagination,
            'page' => 'search'
        ]);
    }

    public function searchLikeFor($table, $values, $search_word){

        $sql_s = ''; // Stop errors when $words is empty
        if($values<=1){
            $sql_s = "`".$values."` LIKE '%".$search_word."%' ";
        } else {
            foreach($values as $value){
                $sql_s .= "`".$value."` LIKE '%".$search_word."%' OR ";
            }
            $sql_s = substr($sql_s,0,-3);
        }
        $sql = Database::get()->select("SELECT * FROM `".$table."` WHERE ".$sql_s);
        return $sql;

    }

    public function create()
    {
        $model = false;
        $defaultLang = $this->defaultLanguage();
        if(isset($_POST["submit"]) && Csrf::isTokenValid() ){
            $postArray = $this->getPost('create');
            $model = $postArray;
            $rules = self::$rules;
            $validator = Validator::validate($postArray,$rules,ApartmentsModel::naming());

            if($validator->isSuccess()){
                $postArray['partner_id'] = intval(Session::get("user_session_id"));
                $insert = Database::get()->insert($this->dataParams["cName"],$postArray);

                if($insert){
                    if(self::$issetImage) {
                        $images = Slim::getImages('image');
                        $image = $images[0];

                        $this->imageUpload($image, $insert);
                    }
                    Session::setFlash('success','Məlumatlar yadda saxlanıldı');
                    return Url::redirect("partner/".$this->dataParams["cName"]);
                }else{
                    Session::setFlash('error','Xəta baş verdi(DB)');
                }
            }else {
                $msg = '';
                foreach ($validator->getErrors() as $error) {
                    $msg .= $error . '<br>';
                }
                Session::setFlash('error', $msg);

            }

        }

        View::renderPartner($this->dataParams["cName"].'/create',[
            'dataParams' => $this->getDataParams(),
            'model' => $model,
            'defaultLang' => $defaultLang,
            'lng' => self::$lng,
            'feature_list' => ApartmentsModel::getFeatures(),
            'locations' => ApartmentsModel::getLocations(),
            'categories' => ApartmentsModel::getCategories(),
            'apt_models' => ApartmentsModel::getModels(),
        ]);

    }


    public function update($id)
    {
        $model = $this->operation->findModel($id);
        $defaultLang = $this->defaultLanguage();

        if(isset($_POST["submit"]) && Csrf::isTokenValid() ){
            $postArray = $this->getPost('update');
            $model = $postArray;
            $rules = self::$rules;

            $validator = Validator::validate($postArray,self::$rules, ApartmentsModel::naming());
            if($validator->isSuccess()){
                $update =  Database::get()->update($this->dataParams["cName"],$postArray,["id" => $id]);
                $images = Slim::getImages('image');
                if($update || $images){
                    $image = $images[0];
                    if(!empty($image)){
                        $this->imageUpload($image, $id);
                    }

                    Session::setFlash('success','Məlumatlar yadda saxlanıldı');
                    return Url::redirect("partner/".$this->dataParams["cName"]);
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

        View::renderPartner($this->dataParams["cName"].'/update',[
            'dataParams' => $this->getDataParams(),
            'model' => $model,
            'defaultLang' => $defaultLang,
            'lng' => self::$lng,
            'feature_list' => ApartmentsModel::getFeatures(),
            'locations' => ApartmentsModel::getLocations(),
            'categories' => ApartmentsModel::getCategories(),
            'apt_models' => ApartmentsModel::getModels(),
        ]);
    }

    public function view($id)
    {
        $model = $this->operation->findModel($id);
        $photos = [];
        if(self::$issetAlbum){
            $photos = GalleryModel::getPhotos($this->dataParams["cName"],$id);
        }
        View::renderPartner($this->dataParams["cName"].'/view', [
            'dataParams' => $this->getDataParams(),
            'result' => $model,
            'defaultLang' => $this->defaultLanguage(),
            'photos' => $photos,
            'issetAlbum' => self::$issetAlbum
        ]);
    }

    // for slim cropper
    protected function imageUpload($image, $id)
    {
        $new_dir = Url::uploadPath().self::$controllerName.'/'.$id;
        $new_thumb_dir = Url::uploadPath().self::$controllerName.'/'.$id.'/thumbs';
        $file_name = $id.'_0.jpg';

        File::makeDir($new_dir);
        File::makeDir($new_thumb_dir);

        $new = Slim::saveFile($image['output']['data'], $file_name, $new_dir, false);

        $file_arr = explode('.', $new['name']);
//        $ext = end($file_arr);
        $destination = $new_thumb_dir."/" . $file_name;

        try {
            $img = new SimpleImage();
            $img->load($new['path'])->resize(self::$imageSizeX, self::$imageSizeY)->save($destination);
        } catch (\Exception $e) {
            Session::setFlash('error','Photo resize error');
        }

        $sql_img = self::$controllerName.'/'.$id.'/' . $file_name;
        $sql_thumb_img = self::$controllerName.'/'.$id.'/thumbs/' . $file_name;
        Database::get()->update($this->dataParams["cName"], ['image' => $sql_img, 'thumb' => $sql_thumb_img], ['id' => $id]);

        //Optimize images
        FileUploader::imageResizeProportional($new_dir.'/'.$file_name, $new_dir.'/'.$file_name, self::$imageOpt, self::$imageSizeX, self::$imageSizeY);
        FileUploader::imageResizeProportional($new_thumb_dir.'/'.$file_name, $new_thumb_dir.'/'.$file_name, self::$imageOpt, self::$thumbSizeX, self::$thumbSizeY);
    }

    protected static function getPost()
    {
        extract($_POST);
        $array = [];
        $array["start_time"] = strtotime($_POST["start_date"]);
        $skip_list = ['csrf_token','submit','start_date'];
        foreach($_POST as $key=>$value){
            if (in_array($key, $skip_list)) continue;
            $array[$key] = Security::safe($_POST[$key]);
        }
        if(is_array($_POST['features'])) {
            $array["features"] = implode($_POST['features'], ',');
        }
        return $array;
    }

    protected function getSearchParams(){
        $array = [];
        if(isset($_GET["submit"])){
            $defaultLang = $this->defaultLanguage();
            $search_array = " WHERE ";
            $search_execute = [];
            $values = ["id"=> '', "title"=> '',"text"=> '', "status"=>'', "page"=>"search"];
            if (isset($_GET['id']) && intval($_GET['id'])>0){
                $search_array.="`id`=:id AND ";
                $search_execute[':id']= $_GET['id'];
                $values["id"] = $_GET['id'];
            }
            if (!empty($_GET['title_'.$defaultLang])){
                $search_array.="`title_{$defaultLang}`=:title_{$defaultLang} AND ";
                $search_execute[':title_'.$defaultLang]= $_GET['title_'.$defaultLang];
                $values['title_'.$defaultLang] = $_GET['title_'.$defaultLang];
            }
            if (isset($_GET['parent_id']) && intval($_GET['parent_id'])>0) {
                $search_array .= "`parent_id`=:parent_id AND ";
                $search_execute[':parent_id'] = $_GET['parent_id'];
                $values["parent_id"] = $_GET['parent_id'];
            }
            if (isset($_GET['status'])){
                $search_array.="status=:status";
                $search_execute[':status']=$_GET['status'];
                $values["status"] = $_GET['status'];
            }
            else{
                $search_array.="status=:status";
                $search_execute[':status']= 0;
                $values["status"] = '0';
            }
            $values["page"]="search";
            $array = array("SQL"=>$search_array,"Data"=>$search_execute,"Values"=>$values);
        }
        return $array;
    }

    public function delete($id)
    {
        $model = $this->operation->findModel($id);
        $this->operation->deleteModel([$id]);

        if(self::$issetImage) $this->deleteImage($id);
        if(self::$issetAlbum) Photos::deletePhotos($this->dataParams['cName'],$id);

        return Url::previous("partner/".$this->dataParams["cName"]);
    }


    public function deleteImage($id)
    {
        if(is_dir(Url::uploadPath().self::$controllerName.'/'.$id)) {
            File::rmDir(Url::uploadPath().self::$controllerName.'/'.$id);
        }

        return true;
    }

    public function up($id)
    {
        $this->operation->move($id,'up');
        return Url::previous("partner/".$this->dataParams["cName"]);
    }

    public function down($id)
    {
        $this->operation->move($id,'down');
        return Url::previous("partner/".$this->dataParams["cName"]);
    }

    public function status($id)
    {
        $model = $this->operation->findModel($id);
        $status = $model["status"]==1?0:1;
        $this->operation->statusModel([$id],$status);
        return Url::previous("partner/".$this->dataParams["cName"]);
    }

    public function operation()
    {
        if(isset($_POST["row_check"])){
            if(isset($_POST["delete"])){
                $row_check = $_POST["row_check"];
                foreach ($row_check as $ids) {
                    if (self::$issetImage) {
                        $this->deleteImage($ids);
                    }
                    if (self::$issetAlbum) {
                        Photos::deletePhotos($this->dataParams['cName'], $ids);
                    }
                }
                $this->operation->deleteModel($row_check);
            }elseif(isset($_POST["active"])){
                $row_check = $_POST["row_check"];
                $this->operation->statusModel($row_check,1);
            }elseif(isset($_POST["deactive"])){
                $row_check = $_POST["row_check"];
                $this->operation->statusModel($row_check,0);
            }
        }else{
            Session::setFlash('error','Seçim edilməyib');

        }


        return Url::previous("partner/".$this->dataParams["cName"]);

    }

    public function album($apt_id)
    {
        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $model = new ApartmentsModel();
            $modelArray = $model->addAlbumPhoto($apt_id);
            if(empty($modelArray['errors'])){
                Session::setFlash('success',self::$lng->get('Photo has been added successfully'));
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }
        View::renderPartner($this->dataParams["cName"].'/'.__FUNCTION__,[
            'dataParams' => $this->getDataParams(),
            'list' => ApartmentsModel::getAlbum($apt_id),
            'lng' => self::$lng,
        ]);
    }


    public function album_operation(){
        if(isset($_POST["row_check"])){
            if(isset($_POST["delete"])){
                ApartmentsModel::deleteAlbumPhoto();
            }elseif(isset($_POST["active"])){
                ApartmentsModel::statusAlbumPhoto(1);
            }elseif(isset($_POST["deactive"])){
                ApartmentsModel::statusAlbumPhoto(0);
            }
        }else{
            Session::setFlash('error','Please choose an action');
        }
        return Url::previous("partner/".$this->dataParams["cName"].'/album');
    }

    public function delete_album_photo($id){
        ApartmentsModel::deleteAlbumPhoto([$id]);
        return Url::previous("partner/".$this->dataParams["cName"]);
    }

    public function rooms($apt_id){
        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $model = new ApartmentsModel();
            $modelArray = $model->addRoom($apt_id);
            if(empty($modelArray['errors'])){
                Session::setFlash('success',self::$lng->get('Room has been added successfully'));
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }
        View::renderPartner($this->dataParams["cName"].'/'.__FUNCTION__,[
            'dataParams' => $this->getDataParams(),
            'list' => ApartmentsModel::getRooms($apt_id),
            'def_language' => self::$def_language,
            'lng' => self::$lng,
        ]);
    }
    public function status_room($id)
    {
        ApartmentsModel::statusRoomToogle($id);
//        return Url::previous("partner/".$this->dataParams["cName"]);
    }
    public function delete_room($id){
        ApartmentsModel::deleteRoom([$id]);
        return Url::previous("partner/".$this->dataParams["cName"]);
    }

    public function room_operation(){
        if(isset($_POST["row_check"])){
            if(isset($_POST["delete"])){
                ApartmentsModel::deleteRoom();
            }elseif(isset($_POST["active"])){
                ApartmentsModel::statusRoom(1);
            }elseif(isset($_POST["deactive"])){
                ApartmentsModel::statusRoom(0);
            }
        }else{
            Session::setFlash('error','Please choose an action');
        }
        return Url::previous("partner/".$this->dataParams["cName"].'/album');
    }


}
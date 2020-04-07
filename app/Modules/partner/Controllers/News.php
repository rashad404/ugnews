<?php
namespace Modules\partner\Controllers;

use Core\Language;
use Helpers\Cache;
use Helpers\Csrf;
use Helpers\Database;
use Helpers\File;
use Helpers\FileCache;
use Helpers\Operation;
use Helpers\Pagination;
use Helpers\Recursive;
use Helpers\Slim;
use Helpers\Security;
use Helpers\Session;
use Helpers\SimpleImage;
use Helpers\Validator;
use Modules\partner\Models\GalleryModel;
use Modules\partner\Models\NewsModel as TableModel;
use Models\LanguagesModel;
use Core\View;
use Helpers\Url;

class News extends MyController
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
    public static $imageFolder = 'news';

    public static $issetAlbum = false;

    public $operation;
    public static $lng;
    public static $partner_id;

    public static $rules;
//'title_'.self::$def_language => ['required',],
    public $dataParams = [
    ];

    public function getDataParams()
    {
        $this->dataParams = [
            "cName" => "news",
            "cModelName" => "NewsModel",
            "cTitle" => "Xəbərlər",
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
        self::$def_language = LanguagesModel::getDefaultLanguage('partner');
        self::$rules = ['title' => ['required']];
        parent::__construct();

        self::$lng = new Language();
        self::$lng->load('partner');
        self::$partner_id = Session::get('user_session_id');
    }

    public function index()
    {
        $values = ["id"=> '', "title"=> '',"unvani"=> '', "status"=>'', "page"=>"index"];
        $rows = Database::get()->select("SELECT * FROM {$this->dataParams['cName']}");
        View::renderPartner($this->dataParams["cName"].'/index',[
            'dataParams' => $this->getDataParams(),
            'rows' => $rows,
            'values' => $values,
        ]);
    }

    public function search(){
        if($_POST){
            $word = $_POST['word'];
            $rows = $this->searchLikeFor($this->dataParams["cName"],['id','title','text'],$word);
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
            $postArray['partner_id'] = self::$partner_id;
            $model = $postArray;

            $rules = self::$rules;
            $validator = Validator::validate($postArray,$rules,TableModel::naming());

            if($validator->isSuccess()){
                $insert = Database::get()->insert($this->dataParams["cName"],$postArray);

                if($insert){
                    if(self::$issetImage) {
                        $images = Slim::getImages('image');
                        $image = $images[0];

                        $this->imageUpload($image, $insert);
                    }
                    Session::setFlash('success','Məlumatlar yadda saxlanıldı');
                    return Url::redirect(MODULE_PARTNER."/".$this->dataParams["cName"]);
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

            $validator = Validator::validate($postArray,self::$rules, TableModel::naming());
            if($validator->isSuccess()){
                $update =  Database::get()->update($this->dataParams["cName"],$postArray,["id" => $id]);
                $images = Slim::getImages('image');
                if($update || $images){
                    $image = $images[0];
                    if(!empty($image)){
                        $this->imageUpload($image, $id);
                    }

                    Session::setFlash('success','Məlumatlar yadda saxlanıldı');
                    return Url::redirect(MODULE_PARTNER."/".$this->dataParams["cName"]);
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
        $new_dir = Url::uploadPath().self::$imageFolder.'/'.$id;
        $new_thumb_dir = Url::uploadPath().self::$imageFolder.'/'.$id.'/thumbs';

        File::makeDir($new_dir);
        File::makeDir($new_thumb_dir);

        $new = Slim::saveFile($image['output']['data'], $id.'_0.png', $new_dir, false);

        $file_arr = explode('.', $new['name']);
        $ext = end($file_arr);
        $destination = $new_thumb_dir."/" . $id."_0.".$ext;

        $img = new SimpleImage();

        $img->load($new['path'])->resize(250, 200)->save($destination);

        $sql_img = self::$imageFolder.'/'.$id.'/' . $id."_0.".$ext;
        $sql_thumb_img = self::$imageFolder.'/'.$id.'/thumbs/' . $id."_0.".$ext;
        Database::get()->update($this->dataParams["cName"], ['image' => $sql_img, 'thumb' => $sql_thumb_img], ['id' => $id]);

    }


    protected static function getPost()
    {
        extract($_POST);
        $skip_list = ['csrf_token','submit'];
        $array = [];
        foreach($_POST as $key=>$value){
            if (in_array($key, $skip_list)) continue;
            $array[$key] = Security::safe($_POST[$key]);
        }

        $array["time"] = time();
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
            if (!empty($_GET['title'])){
                $search_array.="`title`=:title AND ";
                $search_execute[':title']= $_GET['title'];
                $values['title'] = $_GET['title'];
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

        return Url::previous(MODULE_PARTNER."/".$this->dataParams["cName"]);
    }


    public function deleteImage($id)
    {
        if(is_dir(Url::uploadPath().self::$imageFolder.'/'.$id)) {
            File::rmDir(Url::uploadPath().self::$imageFolder.'/'.$id);
        }

        return true;
    }

    public function up($id)
    {
        $this->operation->move($id,'up');
        return Url::previous(MODULE_PARTNER."/".$this->dataParams["cName"]);
    }

    public function down($id)
    {
        $this->operation->move($id,'down');
        return Url::previous(MODULE_PARTNER."/".$this->dataParams["cName"]);
    }

    public function status($id)
    {
        $model = $this->operation->findModel($id);
        $status = $model["status"]==1?0:1;
        $this->operation->statusModel([$id],$status);
        return Url::previous(MODULE_PARTNER."/".$this->dataParams["cName"]);
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


        return Url::previous(MODULE_PARTNER."/".$this->dataParams["cName"]);

    }

}
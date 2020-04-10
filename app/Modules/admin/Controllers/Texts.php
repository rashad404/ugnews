<?php
namespace Modules\admin\Controllers;

use Helpers\Csrf;
use Helpers\Database;
use Helpers\File;
use Helpers\Operation;
use Helpers\Pagination;
use Helpers\Recursive;
use Helpers\Security;
use Helpers\Session;
use Helpers\Validator;
use Modules\admin\Models\GalleryModel;
use Modules\admin\Models\TextsModel as TableModel;
use Models\LanguagesModel;
use Core\View;
use Helpers\Url;

class Texts extends MyController
{

    public static $safeMode = false;  // silinmemeli olan rowlarin mudafieso
    public static $safeModeFields = ["safe_mode"]; // siline bilmeyen rowlarin nezere alinmali fieldi

    public static $positionEnable=true;     // Siralama aktiv, deaktiv

    public static $statusMode = true; // Melumatlarin aktiv deaktiv edile bilmesi (status) field
    public static $crudMode = true; // emeliyyatlar bolmesinin gorsenib gorsenmemesi (operations) fields

    public static $issetImage = false;
    public static $requiredImage = false;
    public static $imageFolder = 'texts';

    public static $issetAlbum = false;

    //set place of menu true or false: up and down
    public static $posUp = true;
    public static $posDown = true;

    // static page url
    public static $staticUrl = 'page';

    public $operation;


    public $dataParams = [
    ];

    public function getDataParams()
    {
        $this->dataParams = [
            "cName" => "texts",
            "cModelName" => "TextsModel",
            "cTitle" => "Yazılar",
            "cStatusMode" => self::$statusMode,
            "cPositionEnable" => self::$positionEnable,
            "cCrudMode" => self::$crudMode,
            "posUp" => self::$posUp,
            "posDown" => self::$posDown,
            "staticUrl" => self::$staticUrl

        ];
        return $this->dataParams;
    }

    public function __construct()
    {
        $this->getDataParams();
        $this->operation = new Operation();
        $this->operation->tableName = $this->dataParams["cName"];
        parent::__construct();

        if(parent::accessControl(['create', 'update'], $this->dataParams["cName"]) == true) {
            return Url::redirect(MODULE_ADMIN);

        }
    }

    public function index()
    {
        
        $db = Database::get();
        $defaultLang = $this->defaultLanguage();
	    $rows = Database::get()->select("SELECT * FROM `".$this->dataParams["cName"]."` ORDER BY `position` ASC");
        View::renderModule($this->dataParams["cName"].'/index',[
            'dataParams' => $this->getDataParams(),
	        'rows' => $rows
        ]);
    }

    public function search(){
        if($_POST){
            $word = $_POST['word'];
            $rows = $this->searchLikeFor($this->dataParams["cName"],['id','text_az','text_ru','text_en'],$word);
        } else {
            $rows = Database::get()->select("SELECT * FROM `".$this->dataParams["cName"]."` WHERE `status`=1 ");
        }
        $pagination = new Pagination();
        View::renderModule($this->dataParams["cName"].'/index',[
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

            $rules = TableModel::rules();
            $validator = Validator::validate($postArray,$rules,TableModel::naming());

            if($validator->isSuccess()){
                $insert = Database::get()->insert($this->dataParams["cName"],$postArray);
                if($insert){

                    if(self::$positionEnable)
                        $position = $this->operation->getPositionForNew($insert,'up',true);

                    Session::setFlash('success','Məlumatlar yadda saxlanıldı');
                    return Url::redirect(MODULE_ADMIN."/".$this->dataParams["cName"]);
                }else{
                    Session::setFlash('error','Xəta baş verdi(DB)');
                }
            }else{
                $msg = '';
                foreach($validator->getErrors() as $error){
                    $msg .= $error.'<br>';
                }
                Session::setFlash('error',$msg);

            }

        }


        View::renderModule($this->dataParams["cName"].'/create',[
            'dataParams' => $this->getDataParams(),
            'model' => $model,
            'defaultLang' => $defaultLang
        ]);

    }

    public function update($id)
    {
        $model = $this->operation->findModel($id);
        $defaultLang = $this->defaultLanguage();

        if(isset($_POST["submit"]) && Csrf::isTokenValid() ){
            $postArray = $this->getPost('update');
            $model = $postArray;
            $rules = TableModel::rules();

            $validator = Validator::validate($postArray,$rules,TableModel::naming());
            if($validator->isSuccess()){
                $update =  Database::get()->update($this->dataParams["cName"],$postArray,["id" => $id]);
                if($update){
                    Session::setFlash('success','Məlumatlar yadda saxlanıldı');
                    return Url::redirect(MODULE_ADMIN."/".$this->dataParams["cName"]);
                }else{
                    Session::setFlash('error','Heç bir məlumat dəyişdirilməyib (DB)');
                }
            }else{
                $msg = '';
                foreach($validator->getErrors() as $error){
                    $msg .= $error.'<br>';
                }
                Session::setFlash('error',$msg);

            }
        }
        View::renderModule($this->dataParams["cName"].'/update',[
            'dataParams' => $this->getDataParams(),
            'model' => $model,
            'defaultLang' => $defaultLang
        ]);
    }

    public function view($id)
    {
        $model = $this->operation->findModel($id);

        View::renderModule($this->dataParams["cName"].'/view', [
            'dataParams' => $this->getDataParams(),
            'result' => $model,
            'defaultLang' => $this->defaultLanguage(),
        ]);
    }

    protected function getPost($action = 'create')
    {
        $languages = LanguagesModel::getLanguages();
        $defaultLang = $this->defaultLanguage();
        extract($_POST);
        $array = [];
        $array["title"] = Security::safe($_POST['title']);
        foreach($languages as $lang){
            $text = "text_".$lang["code"];
            $array[$text] = Security::safe($$text);
        }


        $array["status"] = Security::safe($status);

        return $array;
    }

    protected function getSearchParams(){
        $array = [];
        if(isset($_GET["submit"])){
            $defaultLang = $this->defaultLanguage();
            $search_array = " WHERE ";
            $search_execute = [];
            $values = ["id"=> '', "text_".$defaultLang=> '', "status"=>'', "page"=>"search"];
            if (isset($_GET['id']) && intval($_GET['id'])>0){
                $search_array.="`id`=:id AND ";
                $search_execute[':id']= $_GET['id'];
                $values["id"] = $_GET['id'];
            }
            if (!empty($_GET['text_'.$defaultLang])){
                $search_array.="`text_{$defaultLang}`=:text_{$defaultLang} AND ";
                $search_execute[':text_'.$defaultLang]= $_GET['text_'.$defaultLang];
                $values['text_'.$defaultLang] = $_GET['text_'.$defaultLang];
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

//    public function delete($id)
//    {
//        $model = $this->operation->findModel($id);
//        $this->operation->deleteModel([$id]);
//
//        return Url::previous(MODULE_ADMIN."/".$this->dataParams["cName"]);
//    }


    public function up($id)
    {
        $this->operation->move($id,'up');
        return Url::previous(MODULE_ADMIN."/".$this->dataParams["cName"]);
    }

    public function down($id)
    {
        $this->operation->move($id,'down');
        return Url::previous(MODULE_ADMIN."/".$this->dataParams["cName"]);
    }

    public function status($id)
    {
        $model = $this->operation->findModel($id);
        $status = $model["status"]==1?0:1;
        $this->operation->statusModel([$id],$status);
        return Url::previous(MODULE_ADMIN."/".$this->dataParams["cName"]);
    }

    public function operation()
    {
        if(isset($_POST["row_check"])){
            if(isset($_POST["delete"])){
                $row_check = $_POST["row_check"];
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


        return Url::previous(MODULE_ADMIN."/".$this->dataParams["cName"]);

    }

}
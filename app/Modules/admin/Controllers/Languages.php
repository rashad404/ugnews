<?php
namespace Modules\admin\Controllers;

use Helpers\Cookie;
use Helpers\Csrf;
use Helpers\Data;
use Helpers\Database;
use Helpers\Operation;
use Helpers\Pagination;
use Helpers\Security;
use Helpers\Session;
use Models\CategoriesModel;
use Core\View;
use Core\Router;
use Helpers\Url;
use Modules\admin\Models\LanguagesModel;

class Languages extends MyController
{

    public static $safeMode = true;  // silinmemeli olan rowlarin mudafieso
    public static $safeModeFields = ["default"]; // siline bilmeyen rowlarin nezere alinmali fieldi

    public static $positionEnable=true;     // Siralama aktiv, deaktiv
    public static $positionOrderBy  = 'ASC'; // Siralama ucun order
    public static $positionCondition = false;    // siralanma zamani nezere alinacaq fieldlerin olub olmamasi
    public static $positionConditionField = ['parent_id']; // siralanma zamani nezere alinacaq fieldler

    public static $statusMode = true; // Melumatlarin aktiv deaktiv edile bilmesi (status) field
    public static $crudMode = true; // Melumatlarin aktiv deaktiv edile bilmesi (status) field

    public $operation;

    public $dataParams = [
    ];

    public function getDataParams()
    {
        $this->dataParams = [
            "cName" => "languages",
            "cTitle" => "Dillər",
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
        parent::__construct();

        if(parent::accessControl(['create', 'update', 'delete'], $this->dataParams["cName"]) == true) {
            return Url::redirect(MODULE_ADMIN);

        }
    }

    public function index()
    {
        $countRows = Database::get()->count("SELECT count(id) FROM ".$this->dataParams["cName"]);

        $pagination = new Pagination();
        $limitSql = $pagination->getLimitSql($countRows);
        $orderBy = $this->operation->getOrderBy();
        $rows = Database::get()->select("SELECT * FROM ".$this->dataParams["cName"]." ORDER BY ".$orderBy.$limitSql);

 		View::renderModule($this->dataParams["cName"].'/index',[
            'dataParams' => $this->getDataParams(),
            'rows' => $rows,
            'pagination' => $pagination,
        ]);
    }

    public function create()
    {
        $model = false;
        if(isset($_POST["submit"]) && Csrf::isTokenValid() ){
            $postArray = $this->getPost('create');

            $model = $postArray;
            if($postArray["fullname"]!="" && $postArray["flag"]){
                $insert = Database::get()->insert($this->dataParams["cName"],$postArray);
                if($insert){
                    LanguagesModel::addlanguages($postArray['name']);
                    Session::setFlash('success','Məlumatlar yadda saxlanıldı');
                    return Url::redirect(MODULE_ADMIN."/".$this->dataParams["cName"]);
                }else{
                    Session::setFlash('error','Xəta baş verdi(DB)');
                }
            }else{
                Session::setFlash('error','Vacib xanaları doldurun');
            }

        }
        $flags = $this->getFlags();

        View::renderModule($this->dataParams["cName"].'/create',[
            'dataParams' => $this->getDataParams(),
            'flags' => $flags,
            'model' => $model
        ]);

    }

    public function update($id)
    {
        if(isset($_POST["submit"]) && Csrf::isTokenValid() ){
            $postArray = $this->getPost('update');
            $model = $postArray;
            if($postArray["fullname"]!="" && $postArray["flag"]!=''){
                $update =  Database::get()->update($this->dataParams["cName"],$postArray,["id" => $id]);
                if($update){
                    Session::setFlash('success','Məlumatlar yadda saxlanıldı');
                    return Url::redirect(MODULE_ADMIN."/".$this->dataParams["cName"]);
                }else{
                    Session::setFlash('error','Heç bir məlumat dəyişdirilməyib (DB)');
                }
            }else{
                Session::setFlash('error','Vacib xanaları doldurun');
            }

        }

        $flags = $this->getFlags();

        $model = $this->operation->findModel($id);
        View::renderModule($this->dataParams["cName"].'/update',[
            'dataParams' => $this->getDataParams(),
            'model' => $model,
            'flags' => $flags
        ]);
    }

    public function setDefaultLanguage($id)
    {
        $model = $this->operation->findModel($id);
        Database::get()->raw('UPDATE `'.$this->dataParams["cName"].'` SET `default`=1,`status`=1 WHERE id='.$id);
        Database::get()->raw('UPDATE `'.$this->dataParams["cName"].'` SET `default`=0 WHERE id!='.$id);
        Session::setFlash('success','Əsas dil dəyişdirildi');

        Session::destroy('defaultLanguage');

        return Url::redirect(MODULE_ADMIN."/".$this->dataParams["cName"]);
    }

    protected function getPost($action = 'create')
    {
        extract($_POST);
        $array = [];
        $array["fullname"] = Security::safe($fullname);
        $flag = Security::safe($flag);  $flag = explode("/",$flag);  $flag = end($flag);
        $array["flag"] = $flag;
        $array["name"] = mb_strtolower(mb_substr(Security::safe($fullname),0,2,'UTF-8'),'UTF-8');
        $array["status"] = 1;

        return $array;
    }

    public function getFlags()
    {
        $folder = Url::serverPath().Url::templateModulePath()."images/flags/";
        $langFolder = opendir($folder);
        while($file=readdir($langFolder))
        {
            if(is_file($folder.$file)) $flags[]=$file;
        }
        sort($flags);

        return $flags;
    }

    public function delete($id)
    {
        $model = $this->operation->findModel($id);
        LanguagesModel::deleteLanguages($model['name']);
        $this->operation->deleteModel([$id]);
        return Url::previous(MODULE_ADMIN."/".$this->dataParams["cName"]);
    }

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
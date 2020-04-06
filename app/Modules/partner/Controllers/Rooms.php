<?php
namespace Modules\partner\Controllers;

use Core\Language;
use Helpers\Csrf;
use Helpers\Operation;
use Helpers\Session;
use Modules\partner\Models\RoomsModel;
use Models\LanguagesModel;
use Core\View;
use Helpers\Url;

class Rooms extends MyController
{

    public static $controllerName = 'rooms';
    public static $controllerTitle = 'Rooms';

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

    public $operation;

    public static $rules;
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
        new RoomsModel();
        $this->getDataParams();
        $this->operation = new Operation();
        $this->operation->tableName = $this->dataParams["cName"];
        self::$def_language = LanguagesModel::getDefaultLanguage('partner');
        self::$lng = new Language();
        self::$lng->load('partner');
        self::$rules = ['name_'.self::$def_language.'' => ['required']];
        parent::__construct();
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




    public function index($apt_id){
        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $model = new RoomsModel();
            $modelArray = $model->add($apt_id);
            if(empty($modelArray['errors'])){
                Session::setFlash('success',self::$lng->get('Row has been added successfully'));
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }
        View::renderPartner($this->dataParams["cName"].'/'.__FUNCTION__,[
            'dataParams' => $this->getDataParams(),
            'list' => RoomsModel::getList($apt_id),
            'def_language' => self::$def_language,
            'apt_id' => $apt_id,
            'lng' => self::$lng,
        ]);
    }

    public function status($id)
    {
        RoomsModel::statusToogle($id);
        return Url::previous(MODULE_PARTNER."/".$this->dataParams["cName"]);
    }
    public function delete($id){
        RoomsModel::delete([$id]);
        return Url::previous(MODULE_PARTNER."/".$this->dataParams["cName"]);
    }

    public function operation(){
        if(isset($_POST["row_check"])){
            if(isset($_POST["delete"])){
                RoomsModel::delete();
            }elseif(isset($_POST["active"])){
                RoomsModel::status(1);
            }elseif(isset($_POST["deactive"])){
                RoomsModel::status(0);
            }
        }else{
            Session::setFlash('error','Please choose an action');
        }
        return Url::previous(MODULE_PARTNER."/".$this->dataParams["cName"].'/album');
    }


}
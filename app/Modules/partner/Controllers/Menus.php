<?php
namespace Modules\partner\Controllers;

use Helpers\Csrf;
use Helpers\Operation;
use Helpers\OperationButtons;
use Helpers\Session;
use Modules\partner\Models\GeneralModel;
use Core\View;
use Helpers\Url;
use Modules\partner\Models\MenusModel;

class Menus extends MyController
{
	public $menusModel;
	public $dataParams;
	public $operation;

    public function __construct()
    {
        parent::__construct();
	    $this->language->load('partner');

	    $this->menusModel = new MenusModel();
	    $this->dataParams = $this->menusModel->getDataParams();

	    $this->operation = new Operation();
	    $this->operation->tableName = $this->dataParams["cName"];

        if(GeneralModel::accessControl(['create', 'update', 'delete'], $this->dataParams["cName"]) == true) {
            Url::redirect(MODULE_PARTNER);
        }
    }

    public function index()
    {
	    $data = [
		    'dataParams' => $this->dataParams,
		    'menusModel' => $this->menusModel,
		    'operationButtons' => new OperationButtons(),
		    'rows' => $this->menusModel->getMenus(),
	    ];
		View::renderPartner($this->dataParams["cName"].'/index',$data);
    }

	public function update($id)
	{
		$modelArray =[];
		$modelArray['model'] = $this->operation->findModel($id);

		if(isset($_POST["submit"]) && Csrf::isTokenValid() ){
			$modelArray = $this->menusModel->update($id);
			if(empty($modelArray['errors'])){
				Session::setFlash('success',$this->language->get("Data was saved"));
				Url::redirect(MODULE_PARTNER."/".$this->dataParams["cName"]);
			}else {
				Session::setFlash('error',$modelArray['errors']);
			}
		}

		View::renderPartner($this->dataParams["cName"].'/update',[
			'dataParams' => $this->dataParams,
			'model' => $modelArray['model'],
			'defaultLang' => $this->dataParams['defaultLang'],
			'menus' => $this->menusModel->getMenus()
		]);
	}

	public function create()
	{
		$modelArray =[];
		$modelArray['model'] = null;

		if(isset($_POST["submit"]) && Csrf::isTokenValid() ){
			$modelArray = $this->menusModel->create();
			if(empty($modelArray['errors'])){
				Session::setFlash('success',$this->language->get("Data was saved"));
				Url::redirect(MODULE_PARTNER."/".$this->dataParams["cName"]);
			}else {
				Session::setFlash('error',$modelArray['errors']);
			}
		}

		View::renderPartner($this->dataParams['cName'].'/create',[
			'dataParams' => $this->dataParams,
			'model' => $modelArray['model'],
			'menus' => $this->menusModel->getMenus(),
		]);

	}

	public function view($id)
	{
		$model = $this->operation->findModel($id);
		View::renderPartner($this->dataParams["cName"].'/view', [
			'dataParams' => $this->dataParams,
			'result' => $model,
			'defaultLang' => $this->dataParams['defaultLang']
		]);
	}

	public function delete($id)
	{
		$this->operation->deleteModel([$id]);
		Url::previous(MODULE_PARTNER."/".$this->dataParams["cName"]);
	}

	public function search(){
		$modelArray = $this->menusModel->search();
		View::renderPartner($this->dataParams["cName"].'/index',[
			'dataParams' => $this->dataParams,
			'rows' => $modelArray['rows'],
            'operationButtons' => new OperationButtons(),
			'pagination' => $modelArray['pagination'],
			'menusModel' => $this->menusModel,
			'page' => 'search'
		]);
	}

    public function up($id){
        MenusModel::move($id,'up');
        Url::previous(MODULE_PARTNER."/".$this->dataParams["cName"]);
    }
    public function down($id){
        MenusModel::move($id,'down');
        Url::previous(MODULE_PARTNER."/".$this->dataParams["cName"]);
    }

//	public function up($id)
//	{
//		$this->operation->move($id,'up');
//		Url::previous(MODULE_PARTNER."/".$this->dataParams["cName"]);
//	}
//
//	public function down($id)
//	{
//		$this->operation->move($id,'down');
//		Url::previous(MODULE_PARTNER."/".$this->dataParams["cName"]);
//	}

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
			Session::setFlash('error',$this->language->get("Nothing selected"));

		}


		Url::previous(MODULE_PARTNER."/".$this->dataParams["cName"]);

	}

	public function status($id)
	{
		$model = $this->operation->findModel($id);
		$status = $model["status"]==1?0:1;
		$this->operation->statusModel([$id],$status);
		Url::previous(MODULE_PARTNER."/".$this->dataParams["cName"]);
	}

}
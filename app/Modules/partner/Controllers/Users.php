<?php
namespace Modules\partner\Controllers;

use Helpers\Csrf;
use Helpers\Operation;
use Helpers\OperationButtons;
use Helpers\Session;
use Modules\partner\Models\GeneralModel;
use Core\View;
use Helpers\Url;
use Modules\partner\Models\model;
use Modules\partner\Models\UsersModel;

class Users extends MyController
{
	public $model;
	public $dataParams;
	public $operation;

    public function __construct()
    {
        parent::__construct();
	    $this->language->load('partner');

	    $this->model = new UsersModel();
	    $this->dataParams = $this->model->getDataParams();

	    $this->operation = new Operation();
	    $this->operation->tableName = $this->dataParams["cName"];

        if(GeneralModel::accessControl(['create', 'update', 'delete'], $this->dataParams["cName"]) == true) {
            Url::redirect(MODULE_PARTNER);
        }
    }

    public function index()
    {
        $usersArray = $this->model->getList();
	    $data = [
		    'dataParams' => $this->dataParams,
		    'model' => $this->model,
		    'operationButtons' => new OperationButtons(),
		    'rows' => $usersArray['rows'],
		    'pagination' => $usersArray['pagination'],
	    ];

		View::renderPartner($this->dataParams["cName"].'/index',$data);
    }

	public function update($id)
	{
		$modelArray =[];
		$modelArray['model'] = $this->operation->findModel($id);

		if(isset($_POST["submit"]) && Csrf::isTokenValid() ){
			$modelArray = $this->model->update($id);
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
			'list' => $this->model->getList()
		]);
	}


	public function create()
	{
		$modelArray =[];
		$modelArray['model'] = null;

		if(isset($_POST["submit"]) && Csrf::isTokenValid() ){
			$modelArray = $this->model->create();
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
			'list' => $this->model->getList(),
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

	public function send($id){
        echo $id;
    }
	public function delete($id)
	{
		$this->operation->deleteModel([$id]);
		Url::previous(MODULE_PARTNER."/".$this->dataParams["cName"]);
	}

	public function search(){
		$modelArray = $this->model->search();
		View::renderPartner($this->dataParams["cName"].'/index',[
			'dataParams' => $this->dataParams,
			'rows' => $modelArray['rows'],
            'operationButtons' => new OperationButtons(),
			'pagination' => $modelArray['pagination'],
			'model' => $this->model,
			'page' => 'search'
		]);
	}

	public function up($id)
	{
		$this->operation->move($id,'up');
		Url::previous(MODULE_PARTNER."/".$this->dataParams["cName"]);
	}

	public function down($id)
	{
		$this->operation->move($id,'down');
		Url::previous(MODULE_PARTNER."/".$this->dataParams["cName"]);
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
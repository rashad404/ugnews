<?php


namespace Modules\partner\Traits;

use Core\Language;
use Helpers\Csrf;
use Helpers\Pagination;
use Core\View;
use Models\LanguagesModel;

trait BaseControllerTrait
{
    protected $partnerModel;
    protected $partnerLng;
    protected $partnerParams;

    protected function initializePartnerController($modelClass, $params)
    {
        $this->partnerLng = new Language();
        $this->partnerLng->load('partner');
        $this->partnerParams = $params;
        $this->partnerModel = new $modelClass($this->partnerParams);
    }

    public function index()
    {
        $data = $this->getListData($this->partnerModel);
        $data['lng'] = $this->partnerLng;
        $data['params'] = $this->partnerParams;

        $data['channel_count'] = $this->partnerModel::countList();
        View::renderPartner($this->partnerParams['name'] . '/index', $data);
    }

    protected function getListData($model)
    {
        if (isset($_POST['csrf_token']) && Csrf::isTokenValid()) {
            $data['list'] = $model::search();
            $pagination = new Pagination();
            $data['pagination'] = $pagination;
        } else {
            $pagination = new Pagination();
            $pagination->limit = 30;
            $data['pagination'] = $pagination;
            $limitSql = $pagination->getLimitSql($model::countList());
            $data['list'] = $model::getList($limitSql);
        }
        return $data;
    }

    public function up($id)
    {
        self::$model::move($id, 'up');
        $this->redirectToPrevious();
    }

    public function down($id)
    {
        self::$model::move($id, 'down');
        $this->redirectToPrevious();
    }

    public function status($id)
    {
        self::$model::statusToggle($id);
        $this->redirectToPrevious();
    }

    public function delete($id)
    {
        self::$model::delete([$id]);
        $this->redirectToPrevious();
    }

    public function operation()
    {
        if (isset($_POST["row_check"])) {
            if (isset($_POST["delete"])) {
                self::$model::delete();
            } elseif (isset($_POST["active"])) {
                self::$model::status(1);
            } elseif (isset($_POST["deactive"])) {
                self::$model::status(0);
            }
        } else {
            Session::setFlash('error', 'Please choose an action');
        }
        $this->redirectToPrevious();
    }

    protected function redirectToPrevious()
    {
        Url::previous(MODULE_PARTNER . "/" . self::$params['name']);
    }
}
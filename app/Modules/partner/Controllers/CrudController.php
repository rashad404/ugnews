<?php
namespace Modules\partner\Controllers;
namespace Modules\partner\Controllers;

use Helpers\Csrf;
use Helpers\Session;
use Helpers\Url;
use Core\View;

class CrudController extends MyController
{
    public function add()
    {
        if (isset($_POST['csrf_token']) && Csrf::isTokenValid()) {
            $modelArray = $this->partnerModel::add();
            if (empty($modelArray['errors'])) {
                Session::setFlash('success', $this->partnerLng->get('Data has been saved successfully'));
                Url::redirect(MODULE_PARTNER . "/" . $this->partnerParams["name"]);
            } else {
                Session::setFlash('error', $modelArray['errors']);
            }
        }
        $data['input_list'] = $this->partnerModel::getInputs();
        $data['params'] = $this->partnerParams;
        $data['item'] = '';
        $data['lng'] = $this->partnerLng;
        View::renderPartner($this->partnerParams["name"] . '/' . __FUNCTION__, $data);
    }

    public function update($id)
    {
        if (isset($_POST['csrf_token']) && Csrf::isTokenValid()) {
            $modelArray = $this->partnerModel::update($id);
            if (empty($modelArray['errors'])) {
                Session::setFlash('success', $this->partnerLng->get('Data has been saved successfully'));
                Url::redirect(MODULE_PARTNER . "/" . $this->partnerParams["name"]);
            } else {
                Session::setFlash('error', $modelArray['errors']);
            }
        }
        $data['input_list'] = $this->partnerModel::getInputs();
        $data['params'] = $this->partnerParams;
        $data['item'] = $this->partnerModel::getItem($id);
        $data['lng'] = $this->partnerLng;
        View::renderPartner($this->partnerParams["name"] . '/' . __FUNCTION__, $data);
    }
}
<?php
namespace Modules\partner\Controllers;

use Modules\partner\Models\AdsModel;
use Modules\partner\Models\ChannelsModel;
use Helpers\Security;
use Helpers\Session;
use Helpers\Url;
use Core\View;

class Ads extends CrudController
{
    public function __construct()
    {
        parent::__construct();
        $params = [
            'name' => 'ads',
            'searchFields' => ['id', 'title', 'text'],
            'title' => 'Ads', // We'll translate this in the view
            'position' => false,
            'status' => true,
            'actions' => true,
            'imageSizeX' => '730',
            'imageSizeY' => '450',
            'thumbSizeX' => '640',
            'thumbSizeY' => '340',
        ];
        $this->initializePartnerController(AdsModel::class, $params);
    }

    public function view($id)
    {
        $data['item'] = $this->partnerModel::getItem($id);
        $data['lng'] = $this->partnerLng;
        $data['params'] = $this->partnerParams;
        View::renderPartner($this->partnerParams['name'] . '/' . __FUNCTION__, $data);
    }

    public function view_portal($id)
    {
        Session::set('user_session_id', $id);
        $pass = $this->partnerModel::getPass($id);
        Session::set("user_session_pass", Security::session_password($pass));
        Url::redirect('user');
    }

    public function index()
    {
        parent::index();
        $data = [];
        View::renderPartner($this->partnerParams['name'] . '/index', $data);
    }
}
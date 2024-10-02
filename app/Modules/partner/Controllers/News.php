<?php
namespace Modules\partner\Controllers;

use Modules\partner\Models\NewsModel;
use Modules\partner\Models\ChannelsModel;
use Helpers\Security;
use Helpers\Session;
use Helpers\Url;
use Core\View;

class News extends CrudController
{
    public function __construct()
    {
        parent::__construct();
        $params = [
            'name' => 'news',
            'searchFields' => ['id', 'title', 'text'],
            'title' => 'News', // We'll translate this in the view
            'position' => true,
            'status' => true,
            'actions' => true,
            'imageSizeX' => '730',
            'imageSizeY' => '450',
            'thumbSizeX' => '640',
            'thumbSizeY' => '340',
            'slug' => 'test',
        ];
        $this->initializePartnerController(NewsModel::class, $params);
    }

    public function index()
    {
        parent::index();
        View::renderPartner($this->partnerParams['name'] . '/index', $data);
    }

    public function view($id)
    {
        $data['item'] = $this->partnerModel::getItem($id);
        $data['lng'] = $this->partnerLng;
        $data['params'] = $this->partnerParams;
        View::renderPartner($this->partnerParams['name'] . '/view', $data);
    }

    public function view_portal($id)
    {
        Session::set('user_session_id', $id);
        $pass = $this->partnerModel::getPass($id);
        Session::set("user_session_pass", Security::session_password($pass));
        Url::redirect('user');
    }

    public static function generateSafeSlug($slug)
    {
        $pattern = '~[^0-9a-z]+~i';
        $slug = preg_replace($pattern, '-', $slug);
        return strtolower(trim($slug, '-'));
    }

    public function upload_image()
    {
        $accepted_origins = array("http://ug.loc", "https://ug.news");
        $imageFolder = "Web/uploads/redactor/images/";

        reset($_FILES);
        $temp = current($_FILES);
        if (is_uploaded_file($temp['tmp_name'])) {
            if (isset($_SERVER['HTTP_ORIGIN'])) {
                if (in_array($_SERVER['HTTP_ORIGIN'], $accepted_origins)) {
                    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
                } else {
                    header("HTTP/1.1 403 Origin Denied");
                    return;
                }
            }

            // Sanitize input
            if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
                header("HTTP/1.1 400 Invalid file name.");
                return;
            }

            // Verify extension
            if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
                header("HTTP/1.1 400 Invalid extension.");
                return;
            }

            $filetowrite = $imageFolder . $temp['name'];
            move_uploaded_file($temp['tmp_name'], $filetowrite);

            $filetowrite = 'https://ug.news/Web/uploads/redactor/images/' . $temp['name'];
            echo json_encode(array('location' => $filetowrite));
        } else {
            header("HTTP/1.1 500 Server Error");
        }
    }

    // Override add method for news-specific logic
    public function add()
    {
        if (isset($_POST['csrf_token']) && Csrf::isTokenValid()) {
            $_POST['slug'] = self::generateSafeSlug($_POST['title'] ?? '');
            $modelArray = $this->partnerModel::add();
            if (empty($modelArray['errors'])) {
                Session::setFlash('success', $this->partnerLng->get('News item has been added successfully'));
                Url::redirect(MODULE_PARTNER . "/" . $this->partnerParams["name"]);
            } else {
                Session::setFlash('error', $modelArray['errors']);
            }
        }
        $data['input_list'] = $this->partnerModel::getInputs();
        $data['params'] = $this->partnerParams;
        $data['item'] = '';
        $data['lng'] = $this->partnerLng;
        View::renderPartner($this->partnerParams["name"] . '/add', $data);
    }

    // Override update method for news-specific logic
    public function update($id)
    {
        if (isset($_POST['csrf_token']) && Csrf::isTokenValid()) {
            $_POST['slug'] = self::generateSafeSlug($_POST['title'] ?? '');
            $modelArray = $this->partnerModel::update($id);
            if (empty($modelArray['errors'])) {
                Session::setFlash('success', $this->partnerLng->get('News item has been updated successfully'));
                Url::redirect(MODULE_PARTNER . "/" . $this->partnerParams["name"]);
            } else {
                Session::setFlash('error', $modelArray['errors']);
            }
        }
        $data['input_list'] = $this->partnerModel::getInputs();
        $data['params'] = $this->partnerParams;
        $data['item'] = $this->partnerModel::getItem($id);
        $data['lng'] = $this->partnerLng;
        View::renderPartner($this->partnerParams["name"] . '/update', $data);
    }
}
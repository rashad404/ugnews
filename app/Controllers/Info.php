<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Core\Language;
use Helpers\Pagination;
use Helpers\Session;
use Models\InfoModel;
use Models\RatingModel;


/**
 * Sample controller showing a construct and 2 methods and their typical usage.
 */
class Info extends Controller
{

    public $lng;
    public static $userId;
    // Call the parent construct
    public function __construct()
    {
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
        self::$userId = intval(Session::get("user_session_id"));;
        new InfoModel();
    }


    public function coronavirus()
    {
        $data['title'] = SITE_TITLE;
        $data['keywords'] = SITE_TITLE;
        $data['description'] = SITE_TITLE;

        $data['def_language'] = self::$def_language;

        $data['userId'] = self::$userId;


        $data['list'] = InfoModel::coronavirusList();
        $data['listSelected'] = InfoModel::coronavirusSelected();

        View::render('info/'.__FUNCTION__, $data);
    }


    public function namaz()
    {
        $data['title'] = SITE_TITLE;
        $data['keywords'] = SITE_TITLE;
        $data['description'] = SITE_TITLE;

        $data['def_language'] = self::$def_language;

        $data['userId'] = self::$userId;


        $data['list'] = InfoModel::namazList();
        $data['today'] = InfoModel::getNamazTime();
        $data['now'] = InfoModel::getNamazText();

        View::render('info/'.__FUNCTION__, $data);
    }


}

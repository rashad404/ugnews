<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Core\Language;
use Helpers\Pagination;
use Helpers\Session;
use Models\RatingModel;


/**
 * Sample controller showing a construct and 2 methods and their typical usage.
 */
class Rating extends Controller
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
        new RatingModel();
    }


    public function channels()
    {
        $data['title'] = SITE_TITLE;
        $data['keywords'] = SITE_TITLE;
        $data['description'] = SITE_TITLE;

        $data['def_language'] = self::$def_language;

        $data['userId'] = self::$userId;

        $pagination = new Pagination();
        $pagination->limit = 10;
        $data['pagination'] = $pagination;
        $data['startRow'] = $pagination->getStartRow();

        $limitSql = $pagination->getLimitSql(RatingModel::countChannels());
        $data['startRow'] = $pagination->getStartRow();

        $data['list'] = RatingModel::topChannels($limitSql);

        View::render('rating/'.__FUNCTION__, $data);
    }

    public function news()
    {
        $data['title'] = SITE_TITLE;
        $data['keywords'] = SITE_TITLE;
        $data['description'] = SITE_TITLE;

        $data['def_language'] = self::$def_language;

        $data['userId'] = self::$userId;

        $pagination = new Pagination();
        $pagination->limit = 10;
        $data['pagination'] = $pagination;
        $data['startRow'] = $pagination->getStartRow();

        $limitSql = $pagination->getLimitSql(RatingModel::countNews());
        $data['startRow'] = $pagination->getStartRow();

        $data['list'] = RatingModel::topNews($limitSql);

        View::render('rating/'.__FUNCTION__, $data);
    }


}

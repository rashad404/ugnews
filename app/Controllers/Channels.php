<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Core\Language;
use Helpers\Cookie;
use Helpers\Pagination;
use Helpers\Url;
use Models\ChannelsModel;
use Models\NewsModel;


/**
 * Sample controller showing a construct and 2 methods and their typical usage.
 */
class Channels extends Controller
{

    public $lng;
    // Call the parent construct
    public function __construct()
    {
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
        new ChannelsModel();
    }

    public function index()
    {
        $data['title'] = SITE_TITLE;
        $data['keywords'] = SITE_TITLE;
        $data['description'] = SITE_TITLE;
        $data['def_language'] = self::$def_language;

        $data['list'] = ChannelsModel::getList(100);

        View::render('channels/'.__FUNCTION__, $data);
    }


    // News inner page
    public function inner($url)
    {
        $data['title'] = SITE_TITLE;
        $data['keywords'] = SITE_TITLE;
        $data['description'] = SITE_TITLE;

        $data['def_language'] = self::$def_language;

        $data['item'] = ChannelsModel::getItem($url);

        $pagination = new Pagination();
        $pagination->limit = 70;
        $data['pagination'] = $pagination;

        $data['list'] = NewsModel::getListByChannel($data['item']['id']);
        $data['region'] = Cookie::get('set_region');
        if($data['region']==0)$data['region']=DEFAULT_COUNTRY;

        View::render('channels/'.__FUNCTION__, $data);
    }

    public function search($text)
    {
        if(isset($_POST['search'])){
            Url::redirect('blog/search/'.urlencode($_POST['search']));
            exit;
        }
        $text = urldecode($text);
        $data['title'] = SITE_TITLE;
        $data['keywords'] = SITE_TITLE;
        $data['description'] = SITE_TITLE;
        $data['def_language'] = self::$def_language;

        $data['list'] = ChannelsModel::getSearchList($text);

        View::render('channels/index', $data);
    }

}

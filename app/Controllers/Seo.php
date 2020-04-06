<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Core\Language;
use Helpers\Url;
use Models\SeoModel;


/**
 * Sample controller showing a construct and 2 methods and their typical usage.
 */
class Seo extends Controller
{

    public $lng;
    // Call the parent construct
    public function __construct()
    {
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
        new SeoModel();
    }

    public function insert_keywords(){
//        SeoModel::insert_keywords();
//        SeoModel::updateDates();
    }

    public function index($title)
    {
        $title = preg_replace('/-/',' ',$title);
        $data = SeoModel::seo_blog($title);;

        $data['def_language'] = self::$def_language;

        $data['item'] = SeoModel::getItem($title);

        if($data['item']['id']<1){
            Url::redirect('not_found');
            exit;
        }
        $data['popular_list'] = SeoModel::getPopularList(10);

        $data['next_item'] = SeoModel::navigate($title,'next');
        $data['previous_item'] = SeoModel::navigate($title,'previous');

        View::render('seo/'.__FUNCTION__, $data);
    }


    public function search($text)
    {
        if(isset($_POST['search'])){
            Url::redirect('find/search/'.urlencode($_POST['search']));
            exit;
        }
        $text = urldecode($text);
        $data['title'] = SITE_TITLE;
        $data['keywords'] = SITE_TITLE;
        $data['description'] = SITE_TITLE;
        $data['def_language'] = self::$def_language;

        $data['list'] = SeoModel::getSearchList($text);

        View::render('seo/index', $data);
    }

}

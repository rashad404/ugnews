<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Core\Language;
use Helpers\Url;
use Models\BlogModel;


/**
 * Sample controller showing a construct and 2 methods and their typical usage.
 */
class Blog extends Controller
{

    public $lng;
    // Call the parent construct
    public function __construct()
    {
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
        new BlogModel();
    }

    // Meta function
    public function meta($keyword, $author, $description)
    {
        ?>
        <meta name="author" content="<?=$author?>">
        <meta name="keywords" content="<?=$keyword?>">
        <meta name="description" content="<?=$description?>">
        <meta name="copyright" content="Fly.az" />
        <?php
    }

    public function index()
    {
        $data['title'] = SITE_TITLE;
        $data['keywords'] = SITE_TITLE;
        $data['description'] = SITE_TITLE;
        $data['def_language'] = self::$def_language;

        $data['list'] = BlogModel::getList(100);

        View::render('blog/'.__FUNCTION__, $data);
    }


    // News inner page
    public function inner($id)
    {
        $data['title'] = SITE_TITLE;
        $data['keywords'] = SITE_TITLE;
        $data['description'] = SITE_TITLE;

        $data['def_language'] = self::$def_language;

        $data['item'] = BlogModel::getItem($id);

        $data['popular_list'] = BlogModel::getPopularList(10);

        $data['next_item'] = BlogModel::navigate($id,'next');
        $data['previous_item'] = BlogModel::navigate($id,'previous');

        View::render('blog/'.__FUNCTION__, $data);
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

        $data['list'] = BlogModel::getSearchList($text);

        View::render('blog/index', $data);
    }

}

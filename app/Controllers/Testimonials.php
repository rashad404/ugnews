<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Core\Language;
use Models\TestimonialsModel;


/**
 * Sample controller showing a construct and 2 methods and their typical usage.
 */
class Testimonials extends Controller
{

    public $lng;
    // Call the parent construct
    public function __construct()
    {
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
        new TestimonialsModel();
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

        $data['list'] = TestimonialsModel::getList(100);

        View::render('testimonials/'.__FUNCTION__, $data);
    }


}

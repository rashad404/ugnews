<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Core\Language;
use Helpers\Url;
use Models\SeoModel;
use Models\SitemapModel;
use Models\SiteModel;


/**
 * Sample controller showing a construct and 2 methods and their typical usage.
 */
class Sitemap extends Controller
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

    public function update()
    {
        SitemapModel::update();
    }



}

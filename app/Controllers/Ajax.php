<?php
namespace Controllers;

use Core\Controller;
use Core\Language;
use Helpers\Session;
use Models\AjaxModel;

/**
 * Sample controller showing a construct and 2 methods and their typical usage.
 */

class Ajax extends Controller
{
    // Call the parent construct
    public function __construct()
    {
        parent::__construct();
        new AjaxModel();
    }


    public function subscribe($id){
        echo AjaxModel::subscribe($id);
    }
    public function un_subscribe($id){
        echo AjaxModel::unSubscribe($id);

    }

    public function like($id){
        echo AjaxModel::like($id);
    }
    public function remove_like($id){
        echo AjaxModel::removeLike($id);
    }

    public function dislike($id){
        echo AjaxModel::dislike($id);
    }

    public function remove_dislike($id){
        echo AjaxModel::removeDislike($id);
    }


    public function search($text){

        echo 'AJAX SEARCH CONTROLLER: '.microtime('return_float').'<br/>';
        EXIT;
        echo AjaxModel::search($text);
    }


}

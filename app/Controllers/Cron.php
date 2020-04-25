<?php
namespace Controllers;

use Core\Controller;
use Models\AjaxModel;
use Models\CronModel;

/**
 * Sample controller showing a construct and 2 methods and their typical usage.
 */

class Cron extends Controller
{
    // Call the parent construct
    public function __construct()
    {
        parent::__construct();
        new CronModel();
    }


    public function coronavirus(){
        echo CronModel::coronavirus();
    }


}

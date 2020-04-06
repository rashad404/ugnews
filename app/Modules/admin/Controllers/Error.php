<?php
namespace Modules\admin\Controllers;

use Core\View;
use Core\Router;
use Helpers\Url;

class Error extends MyController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index404()
    {
		View::renderModule('error/index404');
    }
}
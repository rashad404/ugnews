<?php
namespace Modules\partner\Controllers;

use Core\View;
class Error extends MyController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index404()
    {
		View::renderPartner('error/index404');
    }
}
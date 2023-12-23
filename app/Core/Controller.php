<?php
/**
 * Controller - base controller
 *
 */

namespace Core;
use Helpers\Console;
use Models\FilterModel;
use Models\LanguagesModel;

abstract class Controller
{
    public $view;
    public $language;
    public static $def_language;

    public function __construct()
    {
        $this->view = new View();
        $this->language = new Language();
        self::$def_language = LanguagesModel::defaultLanguage();

    }
}

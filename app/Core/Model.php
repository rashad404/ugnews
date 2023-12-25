<?php

namespace Core;

use Helpers\Database;
use Models\LanguagesModel;

abstract class Model
{
    public static $db;
	public static $language;
	public static $def_language;

    public function __construct()
    {
	    self::$db = Database::get();
	    self::$language = new Language();
	    self::$language->load('app');
	    self::$def_language = LanguagesModel::defaultLanguage();
    }
}

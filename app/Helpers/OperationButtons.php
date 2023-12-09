<?php
namespace Helpers;
use Core\Language;

class OperationButtons
{
	public static $language;

	public function __construct() {
		self::$language = new Language();
		self::$language->load('partner');
	}



}

<?php
namespace Models;
use Core\Model;
use Core\Language;
use DOMDocument;
use DOMXPath;
use Helpers\Curl;
use Helpers\Session;

class CronModel extends Model{

    private static $tableNameCorona = 'coronavirus';
    public static $lng;
    public function __construct(){
        parent::__construct();
        self::$lng = new Language();
        self::$lng->load('app');
    }



}

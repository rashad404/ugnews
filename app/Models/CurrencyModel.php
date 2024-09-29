<?php
namespace Models;

use Core\Language;
use Core\Model;

class CurrencyModel extends Model {

    private static $tableName = 'currencies';


    // Get all currencies from the database
    public static function getAllCurrencies() {
        $query = "SELECT `code`, `name`, `nominal`, `value`, `date` FROM `" . self::$tableName . "` ORDER BY `name` ASC";
        $result = self::$db->select($query);
        return $result;
    }

    // Count total currencies
    public static function countCurrencies() {
        $query = "SELECT COUNT(`id`) as total FROM `" . self::$tableName . "`";
        $count = self::$db->count($query);
        return $count;
    }
}

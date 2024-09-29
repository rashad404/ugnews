<?php
namespace Models;

use Core\Model;

class NamazTimesModel extends Model {

    private static $tableName = 'namaz_times';

    // Get all Namaz times from the database
    public static function getAllNamazTimes() {
        $query = "SELECT `day`, `hijri_day`, `week_day`, `imsak`, `fajr`, `sunrise`, `dhuhr`, `asr`, `maghrib`, `isha`, `midnight` 
                  FROM `" . self::$tableName . "` ORDER BY `day` ASC";
        $result = self::$db->select($query);
        return $result;
    }

    // Count total namaz times
    public static function countNamazTimes() {
        $query = "SELECT COUNT(`id`) as total FROM `" . self::$tableName . "`";
        $count = self::$db->count($query);
        return $count;
    }
}

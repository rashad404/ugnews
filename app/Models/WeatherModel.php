<?php
namespace Models;

use Core\Model;

class WeatherModel extends Model {

    public static function getAllWeather() {
        $sql = "SELECT * FROM weather ORDER BY created_at DESC";
        return self::$db->select($sql);
    }

    public static function getWeatherBySlug($slug) {
        $sql = "SELECT * FROM weather WHERE slug = :slug ORDER BY created_at DESC LIMIT 1";
        return self::$db->select($sql, [':slug' => $slug])[0] ?? null;
    }
}

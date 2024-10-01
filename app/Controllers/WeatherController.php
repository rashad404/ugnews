<?php
namespace Controllers;

use Core\Controller;
use Core\Language;
use Core\View;
use Models\WeatherModel;
use Models\SeoModel;

class WeatherController extends Controller {

    public $lng;

    public function __construct() {
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
    }

    // General weather page
    public function index() {
        $data = SeoModel::weather();

        // Fetch all weather data from the database
        $weatherModel = new WeatherModel();
        $data['all_weather'] = $weatherModel->getAllWeather(); // Fetch all cities' weather
        $data['weather'] = $weatherModel->getWeatherBySlug('Baki'); // Default city as Baku
        $data['def_language'] = self::$def_language;

        // Load the view and pass the data
        View::render('site/weather/index', $data);
    }

    // City-specific weather page
    public function city($slug) {
        // Fetch weather for the specific city
        $weatherModel = new WeatherModel();
        $weather = $weatherModel->getWeatherBySlug($slug);

        $city = $this->lng->get($weather['city_name']);
        $data = SeoModel::weather_city($city);

        $data['weather'] = $weather;
        $data['all_weather'] = $weatherModel->getAllWeather(); // Fetch all cities' weather for the table
        $data['def_language'] = self::$def_language;

        // Load the view and pass the data
        View::render('site/weather/city', $data); // Reuse the same weather view
    }
}

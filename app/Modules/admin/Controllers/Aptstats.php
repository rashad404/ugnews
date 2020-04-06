<?php
namespace Modules\admin\Controllers;

use Core\Language;
use Helpers\Csrf;
use Helpers\Database;
use Helpers\Date;
use Helpers\Session;
use Models\LanguagesModel;
use Core\View;
use Helpers\Url;
use Modules\admin\Models\ApartmentsModel;
use Modules\admin\Models\AptStatsModel;
use Modules\admin\Models\InventoryModel;

class Aptstats extends MyController{
    public static $params = [
        'name' => 'aptstats',
        'searchFields' => ['id','name','shop','note','price'],
        'title' => 'Apartment stats',
        'position' => true,
        'status' => true,
        'actions' => true,
    ];

    public static $model;
    public static $lng;
    public static $def_language;
    public static $rules;

    public function __construct(){
        self::$def_language = LanguagesModel::getDefaultLanguage('admin');
        self::$lng = new Language();
        self::$lng->load('admin');
        self::$rules = ['name' => ['required']];
        self::$model = new AptStatsModel(self::$params);
        parent::__construct();
    }

    public function index(){
        $model = self::$model;
//        $model::updateRoomBedIds();

        $data['params'] = self::$params;
        $data['apartments'] = ApartmentsModel::getActiveList();

        $data['lng'] = self::$lng;
        View::renderModule(self::$params['name'].'/'.__FUNCTION__,$data);
    }
    public function beds(){
        $model = self::$model;
//        $model::updateRoomBedIds();

        $data['params'] = self::$params;
        $data['apartments'] = ApartmentsModel::getActiveList();

        $data['lng'] = self::$lng;
        View::renderModule(self::$params['name'].'/'.__FUNCTION__,$data);
    }


}
<?php
namespace Modules\partner\Controllers;

use Core\Language;
use Models\LanguagesModel;
use Core\View;
use Helpers\Url;
use Modules\partner\Models\ApartmentsModel;
use Modules\partner\Models\AptStatsModel;
use Modules\partner\Models\InventoryModel;
use Modules\partner\Models\TenantsModel;

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
        self::$def_language = LanguagesModel::getDefaultLanguage('partner');
        self::$lng = new Language();
        self::$lng->load('partner');
        self::$rules = ['name' => ['required']];
        self::$model = new AptStatsModel(self::$params);
        parent::__construct();
        new ApartmentsModel();
    }

    public function index(){
        $model = self::$model;
//        $model::updateRoomBedIds();

        $data['params'] = self::$params;
        $data['apartments'] = ApartmentsModel::getActiveList();

        $data['lng'] = self::$lng;
        View::renderPartner(self::$params['name'].'/'.__FUNCTION__,$data);
    }

    public function beds(){

        $data['params'] = self::$params;
        $data['apartments'] = ApartmentsModel::getActiveList();

        $data['lng'] = self::$lng;
        View::renderPartner(self::$params['name'].'/'.__FUNCTION__,$data);
    }


    public function delinquencies(){

        $data['params'] = self::$params;
        $data['params']['title'] = 'Delinquencies';
        $data['list'] = AptStatsModel::getDelinquencies();

        $data['lng'] = self::$lng;
        View::renderPartner(self::$params['name'].'/'.__FUNCTION__,$data);
    }

}
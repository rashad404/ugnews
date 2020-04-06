<?php
namespace Modules\admin\Controllers;

use Core\Language;
use Models\LanguagesModel;
use Core\View;
use Modules\admin\Models\ApartmentsModel;
use Modules\admin\Models\PricesModel;

class Prices extends MyController{
    public static $params = [
        'name' => 'prices',
        'searchFields' => ['id','name','shop','note','price'],
        'title' => 'Bed Prices',
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
        self::$model = new PricesModel(self::$params);
        parent::__construct();
    }

    public function index($id){
        $model = self::$model;

        $data['params'] = self::$params;
        $data['item'] = ApartmentsModel::getItem($id);
        $data['apt_models'] = ApartmentsModel::getModels();
        $data['lease_terms'] = PricesModel::getLeaseTerms();

        $data['lng'] = self::$lng;
        View::renderModule(self::$params['name'].'/index',$data);
    }



}
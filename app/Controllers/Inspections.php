<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Core\Language;
use Helpers\Session;
use Helpers\Csrf;
use Models\AppointmentsModel;


/**
 * Sample controller showing a construct and 2 methods and their typical usage.
 */
class Inspections extends Controller
{
    public static $params = [
        'name' => 'inspections',
        'searchFields' => ['id','first_name','last_name','phone','email'],
    ];

    public $lng;
    public static $model;

    public function __construct(){
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
        self::$model = new AppointmentsModel(self::$params);
    }

    public function walk_form(){
        $model = self::$model;
        $data['title'] = $this->lng->get("Walk Through Form").' '.PROJECT_NAME;
        $data['keywords'] = $this->lng->get("Walk Through Form").' '.SITE_TITLE;
        $data['description'] = $this->lng->get("Walk Through Form").' '.SITE_TITLE;
        $data['def_language'] = self::$def_language;

        if(isset($_POST['csrf_token']) && Csrf::isTokenValid()){
            $modelArray = $model->makeAppointment();
            $data['postData'] = $modelArray['postData'];

            if(empty($modelArray['errors'])){
                Session::setFlash('success',$this->lng->get('We\'ll contact you to confirm your appointment'));
            }else {
                Session::setFlash('error',$modelArray['errors']);
            }
        }else{
            $data['postData'] = [
                'first_name'=>'',
                'last_name'=>'',
                'phone'=>'',
                'email'=>'',
                'street'=>'',
                'city'=>'',
                'state'=>'',
                'zip'=>'',
                'description'=>''];
        }

        View::render('inspections/'.__FUNCTION__, $data);
    }
}

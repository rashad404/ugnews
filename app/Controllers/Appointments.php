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
class Appointments extends Controller
{
    public static $params = [
        'name' => 'appointments',
        'searchFields' => ['id','first_name','last_name','phone','email'],
        'title' => 'Schedule an Appointment',
        'rules' => 'Schedule an Appointment',
    ];

    public $lng;
    public static $model;

    public function __construct(){
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
        self::$model = new AppointmentsModel(self::$params);
    }

    public function index(){
        $model = self::$model;
        $data['title'] = $this->lng->get("Schedule an Appointment").' '.PROJECT_NAME;
        $data['keywords'] = $this->lng->get("Schedule an Appointment").' '.SITE_TITLE;
        $data['description'] = $this->lng->get("Schedule an Appointment").' '.SITE_TITLE;
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
            $data['postData'] = ['first_name'=>'','last_name'=>'','phone'=>'','email'=>'','description'=>''];
        }

        View::render('appointments/'.__FUNCTION__, $data);
    }
}

<?php
namespace Modules\partner\Controllers;

use Core\Language;
use Modules\partner\Models\BalanceModel;
use Modules\partner\Models\BedsModel;
use Modules\partner\Models\DataAnalysisModel;
use Modules\partner\Models\TenantsModel;
use Core\View;

class Dataanalysis extends MyController{
    public static $model;
    public static $lng;
    public static $path = 'dataanalysis';

    public function __construct(){
        self::$lng = new Language();
        self::$lng->load('partner');
        self::$model = new DataAnalysisModel();
        parent::__construct();
    }

    public function index(){
        $data['lng'] = self::$lng;
        $data['occupancy_rate'] = DataAnalysisModel::getOccupancyRate();

        $data['leases'] = DataAnalysisModel::getLeaseCount();
        $data['leases_previous'] = DataAnalysisModel::getLeaseCount('previous');

        $data['applications'] = DataAnalysisModel::getApplicationsCount();
        $data['applications_previous'] = DataAnalysisModel::getApplicationsCount('previous');

        $data['showings'] = DataAnalysisModel::getShowingsCount();
        $data['showings_previous'] = DataAnalysisModel::getShowingsCount('previous');

        View::renderPartner(self::$path.'/'.__FUNCTION__, $data);
    }

    public function test(){
        $data['lng'] = self::$lng;

        View::renderPartner(self::$path.'/'.__FUNCTION__, $data);
    }


    public function getGenderData(){

        new TenantsModel();
        $female_count = TenantsModel::countUsers(2);
        $male_count = TenantsModel::countUsers(1);
        $unknown_count = TenantsModel::countUsers(0);
        echo '
            {
                "list":[
                    {
                        "name" : "Male",
                        "count" : '.$male_count.',
                        "backgroundColor" : "#46BFBD",
                        "hoverBackgroundColor" : "#5AD3D1"
                    }
                ,
                    {
                        "name" : "Female",
                        "count" : '.$female_count.',
                        "backgroundColor" : "#F7464A",
                        "hoverBackgroundColor" : "#FF5A5E"
                    }
                ,
                    {
                        "name" : "Unknown",
                        "count" : '.$unknown_count.',
                        "backgroundColor" : "#ccc",
                        "hoverBackgroundColor" : "#DADADA"
                    }
                ]
            }
        ';
    }

    public function getBedEarningsData(){

        new BedsModel();
        $private = BedsModel::getEarnings(1);
        $double = BedsModel::getEarnings(2);
        $quad = BedsModel::getEarnings(3);
        echo '
            {
                "list":[
                    {
                        "name" : "Private",
                        "count" : "'.$private.'",
                        "backgroundColor" : "#88FF99",
                        "hoverBackgroundColor" : "#88FF39"
                    }
                ,
                    {
                        "name" : "Double",
                        "count" : "'.$double.'",
                        "backgroundColor" : "#639fbf",
                        "hoverBackgroundColor" : "#60bad3"
                    }
                ,
                    {
                        "name" : "Quad",
                        "count" : "'.$quad.'",
                        "backgroundColor" : "#cf90d0",
                        "hoverBackgroundColor" : "#e09be1"
                    }
                ]
            }
        ';
    }

    public function getPayments(){

        new BalanceModel();
        $labels = [];
        $payments = [];
        for($i=1;$i<31;$i++){
            $payments_amount = abs(intval(BalanceModel::getPaymentsByDate(date('Y-m-').$i)));
            $labels[] = $i;
            $payments[] = $payments_amount;
        }
        $labels = '['.implode(',',$labels).']';
        $payments = '['.implode(',',$payments).']';
        echo '
            {
                "list":[
                    {
                        "labels" : '.$labels.',
                        "payments" : '.$payments.'
                    }
                ]
            }
        ';
    }
}
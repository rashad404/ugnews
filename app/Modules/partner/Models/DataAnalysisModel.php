<?php

namespace Modules\partner\Models;

use Core\Model;
use Helpers\Session;

class DataAnalysisModel extends Model{

    private static $tableNameBeds = 'apt_beds';
    private static $tableNameLeases = 'leases';
    private static $tableNameApplications = 'apt_applications';
    private static $tableNameShowings = 'showings';

    private static $params;
    private static $partner_id;

    public function __construct($params=''){
        parent::__construct();
        self::$params = $params;
        self::$partner_id = Session::get('user_session_id');
    }


    public static function getOccupancyRate(){

        $total_beds = self::$db->selectOne("SELECT COUNT(`id`) as c FROM ".self::$tableNameBeds." WHERE `partner_id`='".self::$partner_id."' AND `status`=1");
        $occupied_beds = self::$db->selectOne("SELECT COUNT(`id`) as c FROM ".self::$tableNameBeds." WHERE `partner_id`='".self::$partner_id."' AND `status`=1 AND `tenant_id`>0");

        $rate = $occupied_beds['c']*100/$total_beds['c'];
        $rate = number_format($rate,2,  '.', '');
        return $rate;
    }

    public static function getLeaseCount($type=''){

        if($type=='previous'){
            $start = new \DateTime('first day of previous month');
            $start_date = $start->format('Y-m-d');

            $end = new \DateTime('-1 month');
            $end_ym = $end->format('Y-m');
            if(date('d')>$end->format('d')){
                $end_day = $end->format('d');
            }else{
                $end_day = date('d');
            }
            $end_date = $end_ym.'-'.$end_day;
        }else{
            $start = new \DateTime('first day of this month');
            $start_date = $start->format('Y-m-d');
            $end_date = date('Y-m-d');
        }

        $start_time = strtotime($start_date);
        $end_time = strtotime($end_date);

        $count = self::$db->selectOne("SELECT COUNT(`id`) as c FROM ".self::$tableNameLeases." WHERE 
                    `partner_id`='".self::$partner_id."' AND `user_sign`=1 AND 
                    `user_sign_time`>='".$start_time."' AND `user_sign_time`<='".$end_time."'
                    ");

        return $count['c'];
    }

    public static function getApplicationsCount($type=''){

        if($type=='previous'){
            $start = new \DateTime('first day of previous month');
            $start_date = $start->format('Y-m-d');

            $end = new \DateTime('-1 month');
            $end_ym = $end->format('Y-m');
            if(date('d')>$end->format('d')){
                $end_day = $end->format('d');
            }else{
                $end_day = date('d');
            }
            $end_date = $end_ym.'-'.$end_day;
        }else{
            $start = new \DateTime('first day of this month');
            $start_date = $start->format('Y-m-d');
            $end_date = date('Y-m-d');
        }

        $start_time = strtotime($start_date);
        $end_time = strtotime($end_date);

        $count = self::$db->selectOne("SELECT COUNT(`id`) as c FROM ".self::$tableNameApplications." WHERE 
                    `partner_id`='".self::$partner_id."' AND 
                    `time`>='".$start_time."' AND `time`<='".$end_time."'
                    ");

        return $count['c'];
    }

    public static function getShowingsCount($type=''){

        if($type=='previous'){
            $start = new \DateTime('first day of previous month');
            $start_date = $start->format('Y-m-d');

            $end = new \DateTime('-1 month');
            $end_ym = $end->format('Y-m');
            if(date('d')>$end->format('d')){
                $end_day = $end->format('d');
            }else{
                $end_day = date('d');
            }
            $end_date = $end_ym.'-'.$end_day;
        }else{
            $start = new \DateTime('first day of this month');
            $start_date = $start->format('Y-m-d');
            $end_date = date('Y-m-d');
        }


        $count = self::$db->selectOne("SELECT COUNT(`id`) as c FROM ".self::$tableNameShowings." WHERE 
                    `partner_id`='".self::$partner_id."' AND `type`=1 AND 
                    `date`>='".$start_date."' AND `date`<='".$end_date."'
                    ");

        return $count['c'];
    }


}

?>
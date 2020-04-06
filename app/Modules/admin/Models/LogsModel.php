<?php

namespace Modules\admin\Models;

use Core\Model;
use Helpers\Mail;
use Models\api\ExpressModel;
use Models\LanguagesModel;
use Helpers\Security;
use Helpers\Url;
use Helpers\Validator;
use Helpers\Operation;
use Helpers\Pagination;

class LogsModel extends Model{

    public static $defaultLang;
    public static $tableName = 'logs';
    public static $tableNameMillion = 'million';
    public static $tableNameMillionCode = 'million_codes';
    public static $tableNameEmanat = 'emanat';
    public static $tableNameEmanatCode = 'emanat_codes';
    public static $tableNameCodes = 'codes';
    public static $tableNameLogs = 'logs';
    public static $tableNameLogsFee = 'logs_fee';
    public function __construct(){
        parent::__construct();

	    self::$language->load('admin');
	    self::$defaultLang = LanguagesModel::getDefaultLanguage('admin');
    }


    public function getActions(){
        $list =[
            [0, 'all', 'Bütün loglar'],
            [0, 'transfer', 'Transfer'],
            [1, 'income', 'Gələn məbləğ'],
            [2, 'withdraw', 'Nağdlaşdırma'],
        ];
        return $list;
    }

    protected static function getPost()
    {
        extract($_GET);
        $array = [];
        $skip_list = ['csrf_token','submit'];
        foreach($_GET as $key=>$value){
            if (in_array($key, $skip_list)) continue;
            $array[$key] = Security::safe($_GET[$key]);
        }

        return $array;
    }

    public function balance(){
        $postData = self::getPost();

        $sql_action = $sql_date = $sql_search = '';

        if(!empty($postData['dateFrom'])) {
            if ($postData['action'] > 0) {
                $actionList = $this->getActions();
                $sql_action = " AND `action`='" . $actionList[$postData['action']][1] . "'";
            }
            if ($postData['dateFrom'] > 0) {
                $timeFrom = strtotime( $postData['dateFrom'] );
                $timeTo = strtotime( $postData['dateTo'] );
                $sql_date = " AND `time`>=" . $timeFrom . " AND `time`<" . $timeTo;
            }
            if(!empty($postData['search'])){
                $sql_search = self::searchLikeFor(['phone','object_phone','action','sub_action','object','amount'], $postData['search']);
            }
        }else{
            $beginDay = strtotime("midnight", time());
            $nextDay = strtotime("tomorrow", $beginDay);
            $postData['dateFrom'] = date("Y-m-d",$beginDay);
            $postData['dateTo'] = date("Y-m-d",$nextDay);
            $sql_date = " AND `time`>=" . $beginDay . " AND `time`<" . $nextDay;
            $postData['search'] = '';
        }

        $where = "1 ".$sql_date." ".$sql_action." ".$sql_search;
        $pagination = new Pagination();
        $countRows = self::$db->count("SELECT count(id) FROM  ".self::$tableName." WHERE ".$where);
        $limitSql = $pagination->getLimitSql($countRows);
        $return['pagination'] = $pagination;

        $list = self::$db->select("SELECT `id`,`user`,`phone`,`action`,`sub_action`,`object`,`object_phone`,`amount`,`time` FROM ".self::$tableName." WHERE ".$where." ORDER BY `id` DESC ".$limitSql);
        $sum_sql = self::$db->selectOne("SELECT sum(`amount`) as sum_amount FROM ".self::$tableName." WHERE ".$where);
        $return['list'] = $list;
        $return['sum_amount'] = ($sum_sql['sum_amount']==0) ? 0 : $sum_sql['sum_amount'];
        $return['postData'] = $postData;
        return $return;
    }

    public function million_code(){
        $postData = self::getPost();

        $sql_action = $sql_date = $sql_search = '';

        if(!empty($postData['dateFrom'])) {
            if ($postData['dateFrom'] > 0) {
                $timeFrom = strtotime( $postData['dateFrom'] );
                $timeTo = strtotime( $postData['dateTo'] );
                $sql_date = " AND `time`>=" . $timeFrom . " AND `time`<" . $timeTo;
            }
            if(!empty($postData['search'])){
                $sql_search = self::searchLikeFor(['phone','amount','series','code'], $postData['search']);
            }
        }else{
            $beginDay = strtotime("midnight", time());
            $nextDay = strtotime("tomorrow", $beginDay);
            $postData['dateFrom'] = date("Y-m-d",$beginDay);
            $postData['dateTo'] = date("Y-m-d",$nextDay);
            $sql_date = " AND `time`>=" . $beginDay . " AND `time`<" . $nextDay;
            $postData['search'] = '';
        }

        $where = "1 ".$sql_date." ".$sql_action." ".$sql_search;
        $pagination = new Pagination();
        $countRows = self::$db->count("SELECT count(id) FROM  ".self::$tableNameMillionCode." WHERE ".$where);
        $limitSql = $pagination->getLimitSql($countRows);
        $return['pagination'] = $pagination;

        $list = self::$db->select("SELECT `id`,`phone`,`amount`,`time`,`series`,`code` FROM ".self::$tableNameMillionCode." WHERE ".$where." ORDER BY `id` DESC ".$limitSql);
        $sum_sql = self::$db->selectOne("SELECT sum(`amount`) as sum_amount FROM ".self::$tableNameMillionCode." WHERE ".$where);
        $return['list'] = $list;
        $return['sum_amount'] = ($sum_sql['sum_amount']==0) ? 0 : $sum_sql['sum_amount'];
        $return['postData'] = $postData;
        return $return;
    }

    public function million(){
        $postData = self::getPost();

        $sql_action = $sql_date = $sql_search = '';

        if(!empty($postData['dateFrom'])) {
            if ($postData['dateFrom'] > 0) {
                $timeFrom = strtotime( $postData['dateFrom'] );
                $timeTo = strtotime( $postData['dateTo'] );
                $sql_date = " AND `time`>=" . $timeFrom . " AND `time`<" . $timeTo;
            }
            if(!empty($postData['search'])){
                $sql_search = self::searchLikeFor(['phone','amount'], $postData['search']);
            }
        }else{
            $beginDay = strtotime("midnight", time());
            $nextDay = strtotime("tomorrow", $beginDay);
            $postData['dateFrom'] = date("Y-m-d",$beginDay);
            $postData['dateTo'] = date("Y-m-d",$nextDay);
            $sql_date = " AND `time`>=" . $beginDay . " AND `time`<" . $nextDay;
            $postData['search'] = '';
        }

        $where = "1 ".$sql_date." ".$sql_action." ".$sql_search;
        $pagination = new Pagination();
        $countRows = self::$db->count("SELECT count(id) FROM  ".self::$tableNameMillion." WHERE ".$where);


        $limitSql = $pagination->getLimitSql($countRows);
        $return['pagination'] = $pagination;

        $list = self::$db->select("SELECT `id`,`phone`,`amount`,`time`,`step`,`command` FROM ".self::$tableNameMillion." WHERE ".$where." ORDER BY `id` DESC ".$limitSql);
        $sum_sql = self::$db->selectOne("SELECT sum(`amount`) as sum_amount FROM ".self::$tableNameMillion." WHERE ".$where);
        $return['list'] = $list;
        $return['sum_amount'] = ($sum_sql['sum_amount']==0) ? 0 : $sum_sql['sum_amount'];
        $return['postData'] = $postData;
        return $return;
    }

    public function emanat(){
        $postData = self::getPost();

        $sql_action = $sql_date = $sql_search = '';

        if(!empty($postData['dateFrom'])) {
            if ($postData['dateFrom'] > 0) {
                $timeFrom = strtotime( $postData['dateFrom'] );
                $timeTo = strtotime( $postData['dateTo'] );
                $sql_date = " AND `time`>=" . $timeFrom . " AND `time`<" . $timeTo;
            }
            if(!empty($postData['search'])){
                $sql_search = self::searchLikeFor(['phone','amount'], $postData['search']);
            }
        }else{
            $beginDay = strtotime("midnight", time());
            $nextDay = strtotime("tomorrow", $beginDay);
            $postData['dateFrom'] = date("Y-m-d",$beginDay);
            $postData['dateTo'] = date("Y-m-d",$nextDay);
            $sql_date = " AND `time`>=" . $beginDay . " AND `time`<" . $nextDay;
            $postData['search'] = '';
        }

        $where = "1 ".$sql_date." ".$sql_action." ".$sql_search;
        $pagination = new Pagination();
        $countRows = self::$db->count("SELECT count(id) FROM  ".self::$tableNameEmanat." WHERE ".$where);
        $limitSql = $pagination->getLimitSql($countRows);
        $return['pagination'] = $pagination;

        $list = self::$db->select("SELECT `id`,`phone`,`amount`,`time`,`step` FROM ".self::$tableNameEmanat." WHERE ".$where." ORDER BY `id` DESC ".$limitSql);
        $sum_sql = self::$db->selectOne("SELECT sum(`amount`) as sum_amount FROM ".self::$tableNameEmanat." WHERE ".$where);
        $return['list'] = $list;
        $return['sum_amount'] = ($sum_sql['sum_amount']==0) ? 0 : $sum_sql['sum_amount'];
        $return['postData'] = $postData;
        return $return;
    }

    public function emanat_code(){
        $postData = self::getPost();

        $sql_action = $sql_date = $sql_search = '';

        if(!empty($postData['dateFrom'])) {
            if ($postData['dateFrom'] > 0) {
                $timeFrom = strtotime( $postData['dateFrom'] );
                $timeTo = strtotime( $postData['dateTo'] );
                $sql_date = " AND `time`>=" . $timeFrom . " AND `time`<" . $timeTo;
            }

            if(!empty($postData['search'])){
                $sql_search = self::searchLikeFor(['phone','amount','series','code'], $postData['search']);
            }

        }else{
            $beginDay = strtotime("midnight", time());
            $nextDay = strtotime("tomorrow", $beginDay);
            $postData['dateFrom'] = date("Y-m-d",$beginDay);
            $postData['dateTo'] = date("Y-m-d",$nextDay);
            $sql_date = " AND `time`>=" . $beginDay . " AND `time`<" . $nextDay;
            $postData['search'] = '';
        }

        $where = "1 ".$sql_date." ".$sql_action." ".$sql_search;
        $pagination = new Pagination();
        $countRows = self::$db->count("SELECT count(id) FROM  ".self::$tableNameEmanatCode." WHERE ".$where);
        $limitSql = $pagination->getLimitSql($countRows);
        $return['pagination'] = $pagination;

        $list = self::$db->select("SELECT `id`,`phone`,`amount`,`time`,`series`,`code` FROM ".self::$tableNameEmanatCode." WHERE ".$where." ORDER BY `id` DESC ".$limitSql);
        $sum_sql = self::$db->selectOne("SELECT sum(`amount`) as sum_amount FROM ".self::$tableNameEmanatCode." WHERE ".$where);
        $return['list'] = $list;
        $return['sum_amount'] = ($sum_sql['sum_amount']==0) ? 0 : $sum_sql['sum_amount'];
        $return['postData'] = $postData;
        return $return;
    }

    public function codes(){
        $postData = self::getPost();

        $sql_action = $sql_date = $sql_search = '';

        if(!empty($postData['dateFrom'])) {
            if ($postData['dateFrom'] > 0) {
                $timeFrom = strtotime( $postData['dateFrom'] );
                $timeTo = strtotime( $postData['dateTo'] );
                $sql_date = " AND `activated_time`>=" . $timeFrom . " AND `activated_time`<" . $timeTo;
            }

            if(!empty($postData['search'])){
                $sql_search = self::searchLikeFor(['phone','amount','series','code'], $postData['search']);
            }
        }else{
            $beginDay = strtotime("midnight", time());
            $nextDay = strtotime("tomorrow", $beginDay);
            $postData['dateFrom'] = date("Y-m-d",$beginDay);
            $postData['dateTo'] = date("Y-m-d",$nextDay);
            $postData['search'] = '';
            $sql_date = " AND `activated_time`>=" . $beginDay . " AND `activated_time`<" . $nextDay;
        }

        $where = "1 ".$sql_date." ".$sql_action." ".$sql_search;
        $pagination = new Pagination();
        $countRows = self::$db->count("SELECT count(id) FROM  ".self::$tableNameCodes." WHERE ".$where);
        $limitSql = $pagination->getLimitSql($countRows);
        $return['pagination'] = $pagination;

        $list = self::$db->select("SELECT `id`,`phone`,`amount`,`activated_time`,`series`,`code`,`used_time`,`pid`,`info` FROM ".self::$tableNameCodes." WHERE ".$where." ORDER BY `id` DESC ".$limitSql);
        $sum_sql = self::$db->selectOne("SELECT sum(`amount`) as sum_amount FROM ".self::$tableNameCodes." WHERE ".$where);
        $return['list'] = $list;
        $return['sum_amount'] = ($sum_sql['sum_amount']==0) ? 0 : $sum_sql['sum_amount'];
        $return['postData'] = $postData;

        return $return;
    }

    public function report(){
        $postData = self::getPost();

        $sql_date = '';

        if(!empty($postData['dateFrom'])) {
            if ($postData['dateFrom'] > 0) {
                $timeFrom = strtotime( $postData['dateFrom'] );
                $timeTo = strtotime( $postData['dateTo'] );
                $sql_date = " `time`>=" . $timeFrom . " AND `time`<" . $timeTo;
            }
        }else{
            $beginDay = strtotime("midnight", time());
            $nextDay = strtotime("tomorrow", $beginDay);
            $postData['dateFrom'] = date("Y-m-d",$beginDay);
            $postData['dateTo'] = date("Y-m-d",$nextDay);
            $sql_date = " `time`>=" . $beginDay . " AND `time`<" . $nextDay;
        }

        //Million code
        $where = $sql_date." AND `completed`=1";
        $sql = self::$db->selectOne("SELECT sum(`amount`) as sum_amount FROM ".self::$tableNameMillionCode." WHERE ".$where);
        $return['stats']['million_code'] = $sql['sum_amount'];

        //Million balance
        $where = $sql_date." AND `step`=2";
        $sql = self::$db->selectOne("SELECT sum(`amount`) as sum_amount FROM ".self::$tableNameMillion." WHERE ".$where);
        $return['stats']['million'] = $sql['sum_amount'];

        //Million total
        $return['stats']['million_total'] = $return['stats']['million_code'] + $return['stats']['million'];

        //E-manat code
        $where = $sql_date;
        $sql = self::$db->selectOne("SELECT sum(`amount`) as sum_amount FROM ".self::$tableNameEmanatCode." WHERE ".$where);
        $return['stats']['emanat_code'] = $sql['sum_amount'];

        //Emanat balance
        $where = $sql_date." AND `step`=2";
        $sql = self::$db->selectOne("SELECT sum(`amount`) as sum_amount FROM ".self::$tableNameEmanat." WHERE ".$where);
        $return['stats']['emanat'] = $sql['sum_amount'];

        //Emanat total
        $return['stats']['emanat_total'] = $return['stats']['emanat_code'] + $return['stats']['emanat'];

        //Withdrawals
        $where = $sql_date." AND `action`='withdraw'";
        $sql = self::$db->selectOne("SELECT sum(`amount`) as sum_amount FROM ".self::$tableNameLogs." WHERE ".$where);
        $return['stats']['withdrawals'] = $sql['sum_amount'];

        //Transfer fee
        $where = $sql_date." AND `action`='transfer'";
        $sql = self::$db->selectOne("SELECT sum(`fee`) as sum_amount FROM ".self::$tableNameLogsFee." WHERE ".$where);
        $return['stats']['transfer_fee'] = $sql['sum_amount'];

        //Withdraw fee
        $where = $sql_date." AND `action`='withdraw'";
        $sql = self::$db->selectOne("SELECT sum(`fee`) as sum_amount FROM ".self::$tableNameLogsFee." WHERE ".$where);
        $return['stats']['withdraw_fee'] = $sql['sum_amount'];

        //Express bank order card fee
        $where = $sql_date." AND `action`='expressCard'";
        $sql = self::$db->selectOne("SELECT sum(`fee`) as sum_amount FROM ".self::$tableNameLogsFee." WHERE ".$where);
        $return['stats']['express_card_order_fee'] = $sql['sum_amount'];

        //E-manat Balance fee
        $where = $sql_date." AND `action`='emanatAccount'";
        $sql = self::$db->selectOne("SELECT sum(`fee`) as sum_amount FROM ".self::$tableNameLogsFee." WHERE ".$where);
        $return['stats']['emanat_balance_fee'] = $sql['sum_amount'];

        //Million Balance fee
        $where = $sql_date." AND `action`='millionAccount'";
        $sql = self::$db->selectOne("SELECT sum(`fee`) as sum_amount FROM ".self::$tableNameLogsFee." WHERE ".$where);
        $return['stats']['million_balance_fee'] = $sql['sum_amount'];

        foreach($return['stats'] as $key=>$value){
            if($value==0)$return['stats'][$key] = 0;
        }

        $return['postData'] = $postData;

        return $return;
    }

    public function searchLikeFor($fields, $search_word){

        $sql_search = '';
        if($fields<=1){
            $sql_search = "`".$fields."` LIKE '%".$search_word."%' ";
        } else {
            foreach($fields as $value){
                $sql_search .= "`".$value."` LIKE '%".$search_word."%' OR ";
            }
            $sql_search = substr($sql_search,0,-3);
        }
        return ' AND ('.$sql_search.')';
    }


}
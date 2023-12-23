<?php
namespace Models;
use Core\Model;
use Core\Language;
use Helpers\Console;

class ChargesModel extends Model{


    private static $tableName = 'users';
    private static $tableNameLogs = 'balance_logs';
    public $lng;
    public function __construct(){
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
    }


    public static function addCharges(){
        $list = self::$db->select("SELECT `id`,`partner_id`,`first_name`,`rent`,`parking`,`charge_month` FROM ".self::$tableName." WHERE `bed_id`>0 AND `charge_month`!=".date('m')." ORDER BY `id` DESC");
        $c=1;
        $next_month = date('F',strtotime('first day of +1 month'));
//        echo $next_month;exit;
        foreach ($list as $item) {
            if($item['rent']>0 && $item['charge_month']!=date('m') && date('d')>27){
                $total_amount = $item['rent'] + $item['parking'];
                echo $c.' | ';
                echo $item['id'].' | ';
                echo $item['partner_id'].' | ';
                echo $item['first_name'].'<br/>';
                echo $item['rent'].' | ';
                echo $item['parking'].' | ';
                echo $total_amount.'<br/>';
                echo '<br/>';

                if($item['rent']>0){
                    $desc_rent = 'Rent for '.$next_month.'<br/>';
                    $log_data_rent = [
                        'user_id'=>$item['id'],
                        'partner_id'=>$item['partner_id'],
                        'action'=> 'charge',
                        'amount'=> $item['rent'],
                        'description'=> $desc_rent,
                        'time'=> time(),
                    ];
                    self::$db->insert(self::$tableNameLogs, $log_data_rent);
                }
                if($item['parking']>0){
                    $desc_parking = 'Parking for '.$next_month.'<br/>';
                    $log_data_parking = [
                        'user_id'=>$item['id'],
                        'partner_id'=>$item['partner_id'],
                        'action'=> 'charge',
                        'amount'=> $item['parking'],
                        'description'=> $desc_parking,
                        'time'=> time(),
                    ];
                    self::$db->insert(self::$tableNameLogs, $log_data_parking);
                }


                self::$db->raw("UPDATE ".self::$tableName." SET `balance`=`balance`+ ".$total_amount.", `charge_month`='".date('m')."' WHERE `id`=".$item['id']);

                $c++;
            }
        }
//        Console::varDump($list);
    }
}

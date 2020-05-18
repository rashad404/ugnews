<?php
namespace Models;
use Core\Model;
use Core\Language;
use DOMDocument;
use DOMXPath;
use Helpers\Curl;
use Helpers\Session;

class CronModel extends Model{

    private static $tableNameCorona = 'coronavirus';
    public static $lng;
    public function __construct(){
        parent::__construct();
        self::$lng = new Language();
        self::$lng->load('app');
    }

    public static function coronavirus(){
        include 'static_php/simple_html_dom.php';
        $output = file_get_contents('https://www.worldometers.info/coronavirus');
//echo $output;
        $output = str_replace('main_table_countries_today', 'maintablecountriestoday', $output);


        $html = str_get_html($output);
        $data = $html->find('table[id=maintablecountriestoday]', 0);

        $dom = new DOMDocument;
        @$dom->loadHTML($data);
        $xpath = new DOMXPath($dom);

        $dataArray = [];

        $tr = $dom->getElementsByTagName('tr');

        $c = 0;
        foreach ($tr as $element) {
            if($c > 0){
                $country       = $element->getElementsByTagName('td')->item(1)->textContent;
                $total_cases     = $element->getElementsByTagName('td')->item(2)->textContent;
                $new_cases     = $element->getElementsByTagName('td')->item(3)->textContent;
                $total_deaths       = $element->getElementsByTagName('td')->item(4)->textContent;
                $new_deaths       = $element->getElementsByTagName('td')->item(5)->textContent;
                $total_recovered       = $element->getElementsByTagName('td')->item(6)->textContent;
                $active_cases    = $element->getElementsByTagName('td')->item(7)->textContent;
                $critical    = $element->getElementsByTagName('td')->item(8)->textContent;

                array_push($dataArray, array(
                    "country"      => $country,
                    "total_cases"    => preg_replace("/[,+]/","",$total_cases),
                    "new_cases"    => preg_replace("/[,+]/","",$new_cases),
                    "total_deaths"      => preg_replace("/[,+]/","",$total_deaths),
                    "new_deaths"      => preg_replace("/[,+]/","",$new_deaths),
                    "total_recovered"      => preg_replace("/[,+]/","",$total_recovered),
                    "active_cases"   => preg_replace("/[,+]/","",$active_cases),
                    "critical"   => preg_replace("/[,+]/","",$critical),
                ));
            }
            $c++;
        }

        if(count($dataArray) < 100){
            exit;
        }
        else{
            self::$db->raw("TRUNCATE TABLE `".self::$tableNameCorona."`");
        }

        for($i=0; $i<count($dataArray); $i++){

            $country = $dataArray[$i]['country'];
            $total_cases = $dataArray[$i]['total_cases'];
            $new_cases = $dataArray[$i]['new_cases'];
            $total_deaths = $dataArray[$i]['total_deaths'];
            $new_deaths = $dataArray[$i]['new_deaths'];
            $total_recovered = $dataArray[$i]['total_recovered'];
            $active_cases = $dataArray[$i]['active_cases'];
            $critical = $dataArray[$i]['critical'];

            if(preg_match('/total/i', $country)) $total = 0; else $total = str_replace(',', '', $total_cases);

            if(!preg_match('/Europe|Asia|North America|South America|Africa|Oceania|total/i', $country)){

                $insert_data = [
                    'country' =>$country,
                    'total_cases' =>$total_cases,
                    'new_cases' =>$new_cases,
                    'total_deaths' =>$total_deaths,
                    'new_deaths' =>$new_deaths,
                    'total_recovered' =>$total_recovered,
                    'active_cases' =>$active_cases,
                    'total' =>$total,
                    'critical' =>$critical,
                    'last_updated' =>time(),
                ];
                $insert = self::$db->insert(self::$tableNameCorona,$insert_data);


                if(!$insert){
                    echo "Error <br/>";
                }else echo $country.' - INSERTED<br/>';
            }
        }
    }


}

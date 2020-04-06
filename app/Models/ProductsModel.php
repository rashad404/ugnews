<?php
namespace Models;
use Core\Model;
use Helpers\Cookie;
use Helpers\Security;
use Helpers\Session;
use Helpers\Validator;
use Core\Language;

class ProductsModel extends Model{

    private static $tableName = 'products';
    public $lng;
    public function __construct(){
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
    }
    private static $rules = [
        'text' => ['min_length(10)', 'max_length(10000)'],
    ];
    private static function naming(){
        return include SMVC.'app/language/'.self::$def_language.'/naming.php';
    }


    protected static function getPost()
    {
        extract($_POST);
        $array = [];
        $skip_list = ['csrf_token'];
        foreach($_POST as $key=>$value){
            if (in_array($key, $skip_list)) continue;
            $array[$key] = Security::safe($_POST[$key]);
        }
        return $array;
    }


    public static function countList(){
        return self::$db->count("SELECT count(id) FROM  `".self::$tableName."` WHERE `status` = 1 ");
    }
    public static function countListByBoutique($boutique_id){
        return self::$db->count("SELECT count(id) FROM  `".self::$tableName."` WHERE `status` = 1 AND `boutique_id`=".$boutique_id);
    }
    public static function countListByCat($cat_id){
        return self::$db->count("SELECT count(id) FROM  `".self::$tableName."` WHERE `status` = 1 AND `cat`=".$cat_id);
    }

    public static function getList($cat='', $limit='LIMIT 0,10'){

        if(isset($_POST['filter'])) {
            FilterModel::setFilters();
        }
        if(isset($_GET['reset_filter'])) {
            FilterModel::resetFilters();
        }

        if($cat=='all'){
            $where = '';
        }else{
            $where = " AND `cat`='".$cat."'";
        }
        if(Session::check('filters')) {
            $filters = json_decode(Session::get('filters'), true);
    //            print_r($filters);
    //            echo '<br/>';
            foreach ($filters as $key=>$value) {
                if(preg_match('/feature/', $key)  && $value!='-') {
                    $exp = explode('-', $key);
                    $group_id = $exp[1];
                    $feature_id = $exp[2];
                    $extract_key = '$.group' . $group_id . '.feature' . $feature_id;
                    $extract_value = $value;
                    $where .= " AND JSON_EXTRACT(`features` , '" . $extract_key . "') LIKE '%\"" . $extract_value . "\"%'";
                }
            }
//            echo $where;exit;
            if($filters['price_min']>0)$where .= " AND `price`>='".$filters['price_min']."'";
            if($filters['price_max']>0)$where .= " AND `price`<='".$filters['price_max']."'";
        }

        $array = self::$db->select("SELECT `id`,`title_".self::$def_language."`,`text_".self::$def_language."`,`thumb`,`image`,`price` FROM `" . self::$tableName . "` WHERE `status`=1 ".$where." ORDER BY `id` DESC $limit");
        return $array;
    }


    public static function getProductListByCat($cat, $limit='LIMIT 0,10', $order){
        $cat_name = CategoriesModel::getItemName($cat);

        if($cat>0){
            FilterModel::setFilters(['feature-1-5' => $cat_name]);
        }
        if(isset($_POST['filter'])) {
            FilterModel::setFilters();
        }
        if(isset($_GET['reset_filter'])) {
            FilterModel::resetFilters();
        }
        $where = '';
        if(Session::check('filters')) {
            $filters = json_decode(Session::get('filters'), true);
            //            print_r($filters);
            //            echo '<br/>';
            foreach ($filters as $key=>$value) {
                if(preg_match('/feature/', $key)  && $value!='-') {
                    $exp = explode('-', $key);
                    $group_id = $exp[1];
                    $feature_id = $exp[2];
                    $extract_key = '$.group' . $group_id . '.feature' . $feature_id;
                    $extract_value = $value;
                    $where .= " AND JSON_EXTRACT(`features` , '" . $extract_key . "') LIKE '%" . $extract_value . "%'";
                }
            }

            if(isset($filters['price_min']) && $filters['price_min']>0)$where .= " AND `price`>='".$filters['price_min']."'";
            if(isset($filters['price_max']) && $filters['price_max']>0)$where .= " AND `price`<='".$filters['price_max']."'";
        }

        if($order=='low_price'){
            $mysql_order = '`price` ASC';
        }elseif($order=='high_price'){
            $mysql_order = '`price` DESC';
        }else{
            $mysql_order = '`id` DESC';
        }

        $array = self::$db->select("SELECT `id`,`title_".self::$def_language."`,`text_".self::$def_language."`,`thumb`,`image`,`price` FROM `" . self::$tableName . "` WHERE `status`=1 ".$where." ORDER BY $mysql_order $limit");

        return $array;
    }

    public static function getListByBoutique($boutique_id, $limit='LIMIT 0,10', $order){
        if($order=='low_price'){
            $mysql_order = '`price` ASC';
        }elseif($order=='high_price'){
            $mysql_order = '`price` DESC';
        }else{
            $mysql_order = '`id` DESC';
        }
        $array = self::$db->select("SELECT `id`,`title_".self::$def_language."`,`text_".self::$def_language."`,`thumb`,`image`,`price` FROM `".self::$tableName."` WHERE `status`=1 AND `boutique_id`='".$boutique_id."' ORDER BY ".$mysql_order." $limit");
        return $array;
    }
    public static function getProductListBySimilar($id, $limit=10){
        $product_info = self::getProduct($id);
        $array = self::$db->select("SELECT `id`,`cat`,`title_".self::$def_language."`,`text_".self::$def_language."`,`thumb`,`image`,`price` FROM `".self::$tableName."` WHERE `status`=1 AND `cat`='".$product_info['cat']."' AND `id`!='".$id."' ORDER BY `id` DESC LIMIT $limit");
        return $array;
    }

    public static function getProduct($id){
        $array = self::$db->selectOne("SELECT `id`,`cat`,`title_".self::$def_language."`,`text_".self::$def_language."`,`photos`,`thumb`,`image`,`price`,`features` FROM `".self::$tableName."` WHERE `id`='".$id."' AND `status`=1 ORDER BY `id` DESC");
        return $array;
    }

    public static function formatListTitle($text){
        return ucfirst(mb_strtolower($text));
    }
    public static function formatUrlTitle($text){
        return mb_strtolower($text);
    }
    public static function formatInnerTitle($text){
        return ucfirst(mb_strtolower($text));
    }
    public static function formatListText($text, $length=25){
        return mb_substr(strip_tags(html_entity_decode($text)),0,$length);
    }
    public static function formatInnerText($text){
        return html_entity_decode($text);
    }

    public static function showFeatures($features){
        $array = json_decode($features, true);
        if(!empty($array)) {?>

            <div class="features">
                <h4><?=self::$language->get('Features')?></h4>
                <?php
                foreach ($array as $group_id => $feature):
                    $group_id = preg_replace('/group/', '', $group_id);
                    $group_sql = self::$db->selectOne("SELECT `name` FROM `feature_groups` WHERE `id`='" . $group_id . "'");?>

                    <div><label><?=$group_sql['name']?></label></div>
                    <?php
                    foreach ($feature as $feature_id => $feature_value):

                        $feature_id = preg_replace('/feature/', '', $feature_id);
                        $feature_sql = self::$db->selectOne("SELECT `name` FROM `features` WHERE `id`='" . $feature_id . "'");?>

                        - <label><?=$feature_sql['name']?>:</label>
                        <?php
                        if(is_array($feature_value)):?>
                            <select name="">
                            <?php foreach ($feature_value as $key=> $value): ?>
                                <option value="<?=$key?>"><?=$value?></option>
                            <?php endforeach;?>
                            </select><br/>
                    <?php else: ?>
                        <?=$feature_value?><br/>
                    <?php endif;?>
                        <?php
                    endforeach;
                endforeach;?>
            </div>
            <?php
        }
    }


}

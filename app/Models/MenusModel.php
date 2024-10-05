<?php
namespace Models;
use Core\Model;

class MenusModel extends Model{

	public static $tableName = 'menus';
    public function __construct(){
        parent::__construct();
    }

    public function getMenus()
    {
        $rows = self::$db->select('SELECT 
        `id`,
        `title_'.self::$def_language.'`, 
        `up`, `down`, `url`, `menu_type`, `parent_id`, `status` 
         FROM '.self::$tableName.' ORDER BY `position` DESC, `id` ASC');
        return $rows;
    }


    public static function getCategoryListByParent($parent){
        $array = self::$db->select('SELECT 
        `id`,
        `title_'.self::$def_language.'`,`url`, `parent_id`
         FROM `'.self::$tableName.'` WHERE `parent_id`=:parent AND `status`=1 ORDER BY `position` DESC', [':parent'=>$parent]);
        return $array;
    }


    public static function buildCategoryList(){
        $array = self::getCategoryListByParent(0);
        $list = [];
        foreach ($array as $item){
            $list[$item['id']] = array('name'=>$item['title_'.self::$def_language], 'url'=>$item['url']);
            $array2 = self::getCategoryListByParent($item['id']);
            foreach ($array2 as $item2) {
                $list[$item['id']][$item2['id']] = array('name'=>$item2['title_'.self::$def_language], 'url'=>$item2['url']);
                $array3 = self::getCategoryListByParent($item2['id']);
                foreach ($array3 as $item3){
                    $list[$item['id']][$item2['id']][$item3['id']] = array('name'=>$item3['title_'.self::$def_language], 'url'=>$item3['url']);
                }
            }
        }
        return $list;
    }


    public static function buildMenuList($user_id=0){
        $array = self::buildCategoryList();
        echo ' <ul class="menu"> ';
        foreach ($array as $key=>$value){
            if(count($value)>2){
                echo '<li class="menu_li"><a  href="'.$value['url'].'">'.$value['name'].' <span class="caret"></span></a>';
                echo ' <ul class="sub_menu"> ';
                foreach ($value as $key2=>$value2) {
                    if(!is_array($value2)){}
                    elseif(count($value2)>2){
                        echo '<li class="li_sub_menu"><a href="/'.$value2['url'].'">'.$value2['name'].'</a>';
                        echo ' <ul class="dropdown-menu sub-menu"> ';
                        foreach ($value2 as $key3=>$value3) {
                            if(is_array($value3)) {
                                echo '<li><a href="'.$value3['url'].'">' . $value3['name'] . '</a></li>';
                            }
                        }
                        echo '</ul></li>';
                    }else{
                        echo '<li><a href="'.$value2['url'].'">'.$value2['name'].'</a></li>';
                    }
                }
                echo '</ul></li>';
            }else{
                    echo '<li><a href="' . $value['url'] . '">' . $value['name'] . '</a></li>';
            }
        }

        echo '</ul>';
    }


    public static function buildMenuListMobile($user_id=0){
        $array = self::buildCategoryList();
        echo ' <ul class="nav flex-column"> ';
        foreach ($array as $key=>$value){
            if(count($value)>2){

                echo '<li>
                <div class="mobile_drop_menu sub_menu_toggle" data-id="'.$key.'">
                    '.$value['name'].'
                    <i class="fa fa-angle-down" style="font-size: 20px;float:right;cursor: pointer;"></i> 
                </div>';
                echo ' <ul id="toggle-ul-'.$key.'" class="mobile_sub_menu" style="margin:0;padding:0;"> ';
                foreach ($value as $key2=>$value2) {
                    if(!is_array($value2)){}
                    elseif(count($value2)>2){
                        echo '<li class="li_sub_menu"><a href="'.$value2['url'].'">'.$value2['name'].'</a>';
                        echo ' <ul class="dropdown-menu sub-menu"> ';
                        foreach ($value2 as $key3=>$value3) {
                            if(is_array($value3)) {
                                echo '<li><a href="'.$value3['url'].'">' . $value3['name'] . '</a></li>';
                            }
                        }
                        echo '</ul></li>';
                    }else{
                        echo '<li><a href="'.$value2['url'].'">'.$value2['name'].'</a></li>';
                    }
                }
                // echo '</ul></li>';
            }else{
                if($user_id>0 && $value['name']=='Roommates'){
                }else {
                    echo '<li><a href="/' . $value['url'] . '">' . $value['name'] . '</a></li>';
                }
            }
        }
        echo '</ul>';
    }

}
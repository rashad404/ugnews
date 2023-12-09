<?php

namespace Models;

use Core\Model;
use Models\LanguagesModel;
use Helpers\Security;
use Helpers\Url;
use Helpers\Validator;
use Helpers\Operation;
use Helpers\Pagination;
use Helpers\Recursive;
use Helpers\Database;
use Helpers\Format;

class CategoriesModel extends Model{


    public function __construct(){
        parent::__construct();
	    self::$language->load('app');
    }
    protected static function getPost()
    {
        extract($_POST);
        $skip_list = ['submit','csrf_token'];
        foreach($_POST as $key=>$value){
            if (in_array($key, $skip_list)) continue;
            $array[$key] = Security::safeText($_POST[$key]);
        }
        return $array;
    }


	public function getList()
    {
        $rows = self::$db->select('SELECT 
        `id`,
        `name`, `status`,`template`, `parent` 
         FROM '.$this->dataParams['cName'].' ORDER BY `parent` DESC, `position` DESC');
        return $rows;
    }

	public static function getCategoryList()
    {
        $rows = self::$db->select('SELECT 
        `id`,
        `name`, `parent`
         FROM `categories` WHERE `status`=1 ORDER BY `position` ');
        return $rows;
    }


    public static function getMenu()
    {
        $menu = Recursive::menu(Database::get(), 'menus', 0, \Models\LanguagesModel::defaultLanguage(), 'up','','','nav-item','nav-link');
        return $menu;
    }


    public static function getCategoryListByParent($parent){
        $array = self::$db->select('SELECT 
        `id`,
        `name`, `parent`
         FROM `categories` WHERE `parent`=:parent AND `status`=1 ORDER BY `position` ASC', [':parent'=>$parent]);
        return $array;
    }


    public static function buildCategoryList(){
        $array = self::getCategoryListByParent(0);
        $list = [];
        foreach ($array as $item){
            $list[$item['id']] = array('name'=>$item['name']);
            $array2 = self::getCategoryListByParent($item['id']);
            foreach ($array2 as $item2) {
                $list[$item['id']][$item2['id']] = array('name'=>$item2['name']);
                $array3 = self::getCategoryListByParent($item2['id']);
                foreach ($array3 as $item3){
                    $list[$item['id']][$item2['id']][$item3['id']] = array('name'=>$item3['name']);
                }
            }
        }
        return $list;
    }

    public static function buildMenuList(){
        $array = self::buildCategoryList();
        echo ' <ul class="menu"> ';
            foreach ($array as $key=>$value){
                if(count($value)>1){
                    echo '<li class="dropdown"><a  href="cat/'.$key.'/'.Format::urlText($value['name']).'">'.$value['name'].' <span class="caret"></span></a>';
                    echo ' <ul class="dropdown-menu"> ';
                    foreach ($value as $key2=>$value2) {
                        if(!is_array($value2)){}
                        elseif(count($value2)>1){
                            echo '<li class="li_sub_menu"><a href="cat/'.$key2.'/'.Format::urlText($value2['name']).'">'.$value2['name'].'</a>';
                            echo ' <ul class="dropdown-menu sub-menu"> ';
                            foreach ($value2 as $key3=>$value3) {
                                if(is_array($value3)) {
                                    echo '<li><a href="cat/'.$key3.'/'.Format::urlText($value3['name']).'">' . $value3['name'] . '</a></li>';
                                }
                            }
                            echo '</ul></li>';
                        }else{
                            echo '<li><a href="cat/'.$key2.'/'.Format::urlText($value2['name']).'">'.$value2['name'].'</a></li>';
                        }
                    }
                    echo '</ul></li>';
                }else{
                    echo '<li><a href="cat/'.$key.'/'.Format::urlText($value['name']).'">'.$value['name'].'</a></li>';
                }
            }
        echo '</ul>';
    }

    public static function buildMenuList2(){
        $array = self::buildCategoryList();
        echo ' <ul class="menu"> ';
            foreach ($array as $key=>$value){
                if(count($value)>1){
                    echo '<li class="menu_li"><a  href="cat/'.$key.'/'.Format::urlText($value['name']).'">'.$value['name'].' <span class="caret"></span></a>';
                    echo ' <ul class="sub_menu"> ';
                    foreach ($value as $key2=>$value2) {
                        if(!is_array($value2)){}
                        elseif(count($value2)>1){
                            echo '<li class="li_sub_menu"><a href="cat/'.$key2.'/'.Format::urlText($value2['name']).'">'.$value2['name'].'</a>';
                            echo ' <ul class="dropdown-menu sub-menu"> ';
                            foreach ($value2 as $key3=>$value3) {
                                if(is_array($value3)) {
                                    echo '<li><a href="cat/'.$key3.'/'.Format::urlText($value3['name']).'">' . $value3['name'] . '</a></li>';
                                }
                            }
                            echo '</ul></li>';
                        }else{
                            echo '<li><a href="cat/'.$key2.'/'.Format::urlText($value2['name']).'">'.$value2['name'].'</a></li>';
                        }
                    }
                    echo '</ul></li>';
                }else{
                    echo '<li><a href="cat/'.$key.'/'.Format::urlText($value['name']).'">'.$value['name'].'</a></li>';
                }
            }

        echo '</ul>';
    }


    public static function buildMenuListMobile(){
        $array = self::buildCategoryList();
        echo ' <ul class="nav"> ';
            foreach ($array as $key=>$value){
                if(count($value)>1){

                echo '<li>
                <div style="padding:15px 40px;">
                    <a href="cat/'.$key.'/'.Format::urlText($value['name']).'">'.$value['name'].'</a>
                    <i data-id="'.$key.'" class="fa fa-angle-down sub_menu_toggle" style="font-size: 20px;float:right;cursor: pointer;"></i> 
                </div>';
                    echo ' <ul id="toggle-ul-'.$key.'" class="mobile_sub_menu" style="margin:0;padding: 15px 40px;"> ';
                    foreach ($value as $key2=>$value2) {
                        if(!is_array($value2)){}
                        elseif(count($value2)>1){
                            echo '<li class="li_sub_menu"><a href="cat/'.$key2.'/'.Format::urlText($value2['name']).'">'.$value2['name'].'</a>';
                            echo ' <ul class="dropdown-menu sub-menu"> ';
                            foreach ($value2 as $key3=>$value3) {
                                if(is_array($value3)) {
                                    echo '<li><a href="cat/'.$key3.'/'.Format::urlText($value3['name']).'">' . $value3['name'] . '</a></li>';
                                }
                            }
                            echo '</ul></li>';
                        }else{
                            echo '<li><a href="cat/'.$key2.'/'.Format::urlText($value2['name']).'">'.$value2['name'].'</a></li>';
                        }
                    }
                    echo '</ul></li>';
                }else{
                    echo '<li><a href="cat/'.$key.'/'.Format::urlText($value['name']).'">'.$value['name'].'</a></li>';
                }
            }
        echo '</ul>';
    }

    public static function getItemName($id){
        $rows = self::$db->selectOne('SELECT 
        `name`
         FROM `categories` WHERE `id`=:id', ['id'=>$id]);
        return $rows['name'];
    }


}
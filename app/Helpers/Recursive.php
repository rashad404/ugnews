<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 11/3/2016
 * Time: 4:11 PM
 */

namespace Helpers;


class Recursive {

    public static function sub($db,$parent_id=0,$tableName,$fields = '*',$return=[],$language='az')
    {
        $space='';
        $checkParent=$parent_id;
        $sql_fields = '';
        if(is_array($fields) and count($fields)>0){
            foreach($fields as $field){
                $sql_fields.='`'.$field.'`,';
            }
            $sql_fields = substr($sql_fields,0,-1);
        }else{
            $sql_fields = '`title_'.$language.'`,`id`,`parent_id`';
            $fields = ['title_'.$language,'id','parent_id'];
        }
        for($i=1;$i<=10;$i++){
            $check = $db->query("SELECT id,parent_id FROM ".$tableName." where id='$checkParent' ORDER BY `position` ASC")->fetch(Database::FETCH_ASSOC);

            if($check){
                $space.='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ';
                $checkParent=$check["parent_id"];
            }
            else break;
        }
        if($parent_id>0) $space.='»';
        $orderBy = "ORDER BY `position` ASC";
        $rowss = $db->prepare("SELECT ".$sql_fields." FROM ".$tableName." where parent_id = ? ".$orderBy);
        $rowss->execute([$parent_id]);
        $rows = $rowss->fetchAll(Database::FETCH_ASSOC);
        $limitSql='';
        foreach($rows as $row){
            $parentRows = $db->prepare("SELECT ".$sql_fields." FROM ".$tableName." where id=? limit 1");
            $parentRows->execute([$row["parent_id"]]);
            $parentRow = $parentRows->fetch(Database::FETCH_ASSOC);

             foreach($fields as $field){
                if($field == 'title_'.$language){
                    $return[$row["id"]]["title_".$language]=$space.$row["title_".$language];
                }else{
                    $return[$row["id"]][$field]=$row[$field];
                }

             }


            $counts = $db->prepare("SELECT count(id) FROM ".$tableName." where parent_id=:parent_id");
            $counts->execute([':parent_id'=>$row["id"]]);
            $count = $counts->fetchColumn();
            if($count>0)
            {
                $parent_id=$row["id"];
                $return = Recursive::sub($db,$parent_id,$tableName,$fields,$return);
            }
        }
        return $return;
    }

    public static function menu($db, $tablename, $root_id = 0, $suffix, $place, $static_url = 'page', $mainUlClass='dropdown-menu', $mainLiClass = 'dropdown custom-drop', $otherLiClass='dropdown-submenu'){
        global $full_url;
        $full_url= Url::getFullUrl();
        $categories_query = $db->prepare("select * from " . $tablename . " where `status` = 1 AND $place = 1 order by `position` asc ");
        $categories_query->execute();
        $items = array();

        while ($categories = $categories_query->fetch(\PDO::FETCH_ASSOC))  {
            $items[$categories['id']] = array('title' => $categories['title_'.$suffix],
                'parent_id' => $categories['parent_id'],
                'id' => $categories['id'],
                'url' => $categories['url'],
                'menu_type' => $categories['menu_type']);
        }
        $citems=count($items);

        if($citems<=0) return '';
        elseif($citems==1) $children[] = $items; //in case we have one category item without subcategories, rare but possible
        else foreach( $items as $item ) $children[$item['parent_id']][] = $item;
        $loop = !empty( $children[$root_id] );
        $parent = $root_id;
        $parent_stack = array();
        $html=array();//store html code
        $stack=array();//helper array so to know the current level
        $pic=''; //products_in_category string
        $html[] = '';
        while ( $loop && ( ( $option = each( $children[$parent] ) ) || ( $parent > $root_id ) ) ){
            if ( $option === false ){
                $parent = array_pop( $parent_stack );
                $html[] = '</ul>';
                $html[] = '</li>';
                array_pop( $stack );
            }elseif ( !empty( $children[$option['value']['id']] ) ){
                $stack[]=$option['value']['id'];

                $rt=$root_id>0 ? $root_id.'_' : '';
                $cpath_new=count($stack)<=0 ? $option['value']['id'] : $root_id;
                if(count($stack) < 2) {
                    $class = $mainLiClass;
                    $a_class = '<a class="dropdown-toggle" data-toggle="dropdown">';
                    $a_span = '<span class="caret"></span>';
                } else {
                    $class = $otherLiClass;
                    $a_class = '<a href="'.SITE_URL.'" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">';
                    $a_span = '';
                }
                $html[] = '<li class="'.$class.'">'.$a_class;
                $sm=0;
                if((isset($cPath_array) && in_array($option['value']['id'], $cPath_array))){
                    $sm=1;
                    $html[]='<strong>'.stripslashes($option['value']['title']).$pic.'</strong>';
                }else{
                    $html[]=stripslashes($option['value']['title']) . $pic;
                }

                $html[]=' '.$a_span.'</a>';
                $html[] = '<ul class="'.$mainUlClass.'">';

                $parent_stack[]=$option['value']['parent_id'];
                $parent = $option['value']['id'];
            }else{
                $rt=$root_id>0 ? $root_id.'_' : '';
                $cpath_new= $option['value']['id'];
                if($option['value']['menu_type'] == 'site') {
                    $link = SITE_URL.$option['value']['url'];
                } elseif($option['value']['menu_type'] == 'static') {
                    $link = SITE_URL.$static_url.'/'.$cpath_new.'/'.$option['value']['url'];
                } elseif ($option['value']['menu_type'] == 'url') {
                    $link = $option['value']['url'];
                } else {
                    $link = SITE_URL.$cpath_new;
                }

                if(SITE_URL.$full_url == $link) {
                    $active_class = ' active';
                } else {
                    $active_class = '';
                }
                $html[]= '<li class="'.$mainLiClass.''.$active_class.'"><a  class="'.$otherLiClass.'" href="'.$link.'" >';
                if (isset($cPath_array) && in_array($option['value']['id'], $cPath_array)) {
                    $html[]='<strong>'.stripslashes($option['value']['title']).$pic.'</strong>';
                }else{
                    $html[]=stripslashes($option['value']['title']).$pic;
                }
                $html[]='</a>';
            }
        }

        $data = implode($html);
        return $data;
    }

    public static function submenu($db, $tablename, $root_id = 0, $cat_name, $suffix, $mainUlClass='dropdown-menu',$submenuUlClass='dropdown-submenu'){
        $categories_query = $db->prepare("select * from " . $tablename . " where `status` = 1 order by `position` asc ");
        $categories_query->execute();
        $items = array();
        while ($categories = $categories_query->fetch(\PDO::FETCH_ASSOC))  {
            $items[$categories['id']] = array('title' => $categories['title_'.$suffix],
                'parent_id' => $categories['parent_id'],
                'id' => $categories['id'],
                'url' => $categories['url'],
                'menu_type' => $categories['menu_type']);
        }
        $citems=count($items);

        if($citems<=0) return '';
        elseif($citems==1) $children[] = $items; //in case we have one category item without subcategories, rare but possible
        else foreach( $items as $item ) $children[$item['parent_id']][] = $item;
        $loop = !empty( $children[$root_id] );
        $parent = $root_id;
        $parent_stack = array();
        $html=array();//store html code
        $stack=array();//helper array so to know the current level
        $pic=''; //products_in_category string
        $html[]='<ul class="'.$mainUlClass.'">';
        while ( $loop && ( ( $option = each( $children[$parent] ) ) || ( $parent > $root_id ) ) ){
            if ( $option === false ){
                $parent = array_pop( $parent_stack );
                $html[] = '</ul>';
                $html[] = '</li>';
                array_pop( $stack );
            }elseif ( !empty( $children[$option['value']['id']] ) ){
                $stack[]=$option['value']['id'];
                $rt=$root_id>0 ? $root_id.'_' : '';
                $cpath_new=count($stack)<=0 ? $option['value']['id'] : $root_id;
                $html[] = '<li class="'.$submenuUlClass.'">
            <a href="'.SITE_URL.'" class="dropdown-toggle disabled" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">';
//            if (SHOW_COUNTS == 'true') {
//                $products_in_category = tep_count_products_in_category($option['value']['id']);
//                if ($products_in_category > 0) {
//                    $pic='&nbsp;(' . $products_in_category . ')';
//                }
//            }
                $sm=0;
                if((isset($cPath_array) && in_array($option['value']['id'], $cPath_array))){
                    $sm=1;
                    $html[]='<strong>'.stripslashes($option['value']['title']).$pic.'</strong>';
                }else{
                    $html[]=stripslashes($option['value']['title']) . $pic;
                }

                $html[]='</a>';
                $html[] = '
            <ul class="'.$mainUlClass.'">';


                $parent_stack[]=$option['value']['parent_id'];
                $parent = $option['value']['id'];
            }else{
                $rt=$root_id>0 ? $root_id.'_' : '';
                $cpath_new= $option['value']['id'];
                if($option['value']['menu_type'] == 'site') {
                    $link = SITE_URL.$option['value']['url'];
                } elseif($option['value']['menu_type'] == 'static') {
                    $link = SITE_URL.$cpath_new.'/'.$option['value']['url'];
                } elseif ($option['value']['menu_type'] == 'url') {
                    $link = $option['value']['url'];
                } else {
                    $link = SITE_URL.$cpath_new;
                }
                $html[]= '<li><a href="'.$link.'" >';
//            if (SHOW_COUNTS == 'true') {
//                $products_in_category = tep_count_products_in_category($option['value']['id']);
//                if ($products_in_category > 0) {
//                    $pic='&nbsp;(' . $products_in_category . ')';
//                }
//            }
                if (isset($cPath_array) && in_array($option['value']['id'], $cPath_array)) {
                    $html[]='<strong>'.stripslashes($option['value']['title']).$pic.'</strong>';
                }else{
                    $html[]=stripslashes($option['value']['title']).$pic;
                }
                $html[]='</a>';
            }
        }
        $html[]='</ul>';
        $data = '<li class="dropdown custom-drop">' .
            '<a class="dropdown-toggle" data-toggle="dropdown">'.$cat_name.' <span class="caret"></span></a>' .
            implode($html) .
            '</li>';
        return $data;
    }

    public static function subArray($db, $tableName, $parent_id, $language, $fields = '*', $return=[])
    {
        $space='';
        $checkParent=$parent_id;
        for($i=1;$i<=10;$i++){
            $check = $db->query("SELECT id,parent_id FROM ".$tableName." where id='$checkParent' ORDER BY `position` ASC")->fetch(\PDO::FETCH_ASSOC);
             if($check){
                $space.='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;»» ';
                $checkParent=$check["parent_id"];
            }
            else break;
        }
        $orderBy = "ORDER BY `position` ASC";
        $rows_alt = $db->prepare("SELECT * FROM ".$tableName." where parent_id = ? ".$orderBy);
        $rows_alt->execute([$parent_id]);
        $rows = $rows_alt->fetchAll(\PDO::FETCH_ASSOC);
        $limitSql='';
        foreach($rows as $row){
            $parentRows = $db->prepare("SELECT * FROM ".$tableName." where id=? limit 1");
            $parentRows->execute([$row["parent_id"]]);
            $parentRow = $parentRows->fetch(Database::FETCH_ASSOC);

            $return[$row["id"]]["parent_title_$language"]=$parentRow["title_$language"];
            $return[$row["id"]]["title_$language"]=$space.$row["title_$language"];
            $return[$row["id"]]["parent_id"]=$parentRow["id"];
            $return[$row["id"]]["id"]=$row["id"];
            $return[$row["id"]]["id"]=$parentRow["parent_id"];
            $return[$row["id"]]["parent_id"]=$row["parent_id"];
            $return[$row["id"]]["status"]=$row["status"];
            $return[$row["id"]]["parent_text_$language"]=$parentRow["text_$language"];
            $return[$row["id"]]["text_$language"]=$row["text_$language"];
            $return[$row["id"]]["data"]=[];


            $counts = $db->prepare("SELECT count(id) FROM ".$tableName." where parent_id=:parent_id");
            $counts->execute([':parent_id'=>$row["id"]]);
            $count = $counts->fetchColumn();
            if($count>0)
            {

                $childRows = $db->prepare("SELECT * FROM ".$tableName." where parent_id=:parent_id");
                $childRows->execute([':parent_id'=>$row["id"]]);
                $childRows = $childRows->fetchAll();

                //$parent_id=$row["id"];
               // $return_alt_sql = self::sub($db,$tableName,$parent_id,$language,$return);
                $i = 1;
                foreach ($childRows as $return_alt) {
                    $return[$row['id']]['data'][$i]["parent_title_$language"] = $row["title_$language"];
                    $return[$row['id']]['data'][$i]["title_$language"] = $return_alt["title_$language"];
                    $return[$row['id']]['data'][$i]["parent_id"] = $return_alt["id"];
                    $return[$row['id']]['data'][$i]["id"] = $return_alt["id"];
                    $return[$row['id']]['data'][$i]["id"] = $return_alt["parent_id"];
                    $return[$row['id']]['data'][$i]["status"] = $return_alt["status"];
                    $return[$row['id']]['data'][$i]["parent_id"] = $return_alt["parent_id"];
                    $return[$row['id']]['data'][$i]["parent_text_$language"] = $return_alt["text_$language"];
                    $return[$row['id']]['data'][$i]["text_$language"] = $return_alt["text_$language"];
                    $i++;
                }


            }
        }
        return $return;
    }
}
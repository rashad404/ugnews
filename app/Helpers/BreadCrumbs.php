<?php
namespace Helpers;

class BreadCrumbs{

    public static function get($links, $title){
        $array = ['99450','99451','99455','99470','99477'];

        $return = '<ul class="list-inline">';
        foreach ($links as $link) {
            $return .= '<li><a href="'.$link['url'].'">'.$link['name'].'</a> /</li>';
        }
            $return .= '<li>'.$title.'</li>';
        $return .= '</ul>';

        return $return;
    }


}
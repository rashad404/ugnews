<?php

namespace Helpers;


class Console{
    public static function log($text, $title='Console')
    {
        ?><script> console.log('<?=$title?>:','<?=$text?>');</script><?php
    }

    public static function varDump($data){
        echo '<pre>';
            print_r($data);
        echo '</pre>';
        exit;
    }
}

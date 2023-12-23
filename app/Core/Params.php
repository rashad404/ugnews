<?php
namespace Core;

class Params
{
	public function __construct()
    {
        ob_start();
        include 'app/Core/ParamsInclude.php';

        foreach ($params_list as $define_name=>$define_value) {
            define($define_name, $define_value);
        }
    }
}

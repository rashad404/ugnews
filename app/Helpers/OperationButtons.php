<?php

namespace Helpers;
use Core\Language;

class OperationButtons
{
	public static $language;

	public function __construct() {
		self::$language = new Language();
		self::$language->load('partner');
	}

	public static function getPositionIcons($id,$path)
    {
        $return =
	        '<span onclick="javascript:window.location.href = \''.Url::to($path."/up/".$id).'\'" class="pointer admin-arrow"><img src="'.Url::templateModulePath().'icons/arrowtop.png"></i></span>'
            .'<span onclick="javascript:window.location.href = \''.Url::to($path."/down/".$id).'\'" class="pointer admin-arrow"><img src="'.Url::templateModulePath().'icons/arrowdown.png"></span>';
        return $return;
    }

    public static function getStatusIcons($id,$status,$readOnly=''){
        if($status==1) $checked='checked'; else $checked='';
        if(intval($readOnly)==1) $readOnly='readOnly';
        return '
            <div class="pos-rel-top-6 top-0">
                <input type="checkbox" name="my-checkbox" '.$readOnly.' data-size="mini" class="admin-switch" data-on-text="ON" data-off-text="OFF" value="'.$id.'" '.$checked.'>
            </div>';
    }

    public static function getCrudIcons($id,$path)
    {
    	$return = '<a href="'.Url::to($path."/view/".$id).'" title="'.self::$language->get("View").'" data-toggle="tooltip" class="btn btn-xs btn-success"><i class="fa fa-eye"></i></a> '
            .'<a href="'.Url::to($path."/update/".$id).'" title="'.self::$language->get("Edit").'" data-toggle="tooltip" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a> '
            .'<a href="'.Url::to($path."/delete/".$id).'" title="'.self::$language->get("Delete").'" data-toggle="tooltip" class="btn btn-xs btn-danger btn-delete delete_confirm"><i class="fa fa-trash"></i></a>';

    	return $return;
    }

    public static function getCrudIconsViewEdit($id,$path)
    {
        $return =
            '<a href="'.Url::to($path."/view/".$id).'" title="'.self::$language->get("View").'" data-toggle="tooltip" class="btn btn-xs btn-success"><i class="fa fa-eye"></i></a> '
            .'<a href="'.Url::to($path."/update/".$id).'" title="'.self::$language->get("Edit").'" data-toggle="tooltip" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a>';

        return $return;
    }

    public static function getCrudIconsEditDel($id,$path)
    {
        $return =
            '<a href="'.Url::to($path."/update/".$id).'" title="'.self::$language->get("Edit").'" data-toggle="tooltip" class="btn btn-xs btn-primary"><i class="fas fa-pencil-alt"></i></a> '
            .'<a href="'.Url::to($path."/delete/".$id).'" title="'.self::$language->get("Delete").'" data-toggle="tooltip" class="btn btn-xs btn-danger btn-delete delete_confirm"><i class="fa fa-trash"></i></a>';

        return $return;
    }

    public static function getCrudIconsEdit($id,$path)
    {
        $return =
            '<a href="'.Url::to($path."/update/".$id).'" title="'.self::$language->get("Edit").'" data-toggle="tooltip" class="btn btn-xs btn-primary"><i class="fas fa-pencil-alt"></i></a> ';

        return $return;
    }

    public static function getCrudIconsDel($id,$path)
    {
        return '<a href="'.Url::to($path."/delete/".$id).'" title="'.self::$language->get("Delete").'" data-toggle="tooltip" class="btn btn-xs btn-danger btn-delete delete_confirm"><i class="fa fa-trash"></i></a>';
    }

    public static function getCrudIconsquestions($id,$path)
    {
        $return =
	        '<div class="operation-buttons">'
	        .'<a href="'.Url::to($path."/update/".$id).'" class="btn btn-warning btn-circle">'
	        .'<i class="glyphicon glyphicon-pencil"></i>'
	        .'</a>'
	        .'<a href="'.Url::to($path."/delete/".$id).'" data-placement="left" class="btn btn-danger btn-circle delete_confirm">'
	        .'<i class="fa fa-times"></i>'
	        .'</a>'
	        .'</div>';

        return $return;
    }

    public static function getCrudIconsnotedit($id,$path)
    {
        $return =
	        '<div class="operation-buttons">'
	        .'<a href="'.Url::to($path."/sendMessage/".$id).'" class="btn btn-warning btn-circle">'
	        .'<i class="glyphicon glyphicon-send"></i>'
	        .'</a>'
	        .'<a href="'.Url::to($path."/delete/".$id).'" data-placement="left" class="btn btn-danger btn-circle delete_confirm">'
	        .'<i class="fa fa-times"></i>'
	        .'</a>'
	        .'</div>';

        return $return;
    }


}

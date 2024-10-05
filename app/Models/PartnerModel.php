<?php
namespace Models;
use Core\Model;
use Helpers\Cookie;

class PartnerModel extends Model{

    public function __construct(){
        parent::__construct();
    }


    public static function getInfo($user_id=0){

        $data = [];
        if($user_id==0) {
            $data['id'] = Cookie::get('user_id');
        }else{
            $data['id'] = $user_id;
        }


            $data['name'] = 'UG.news';
            $data['website'] = 'ug.news';
            $data['header_logo'] = 'ug_news.svg';
            $data['header_logo_white'] = 'ug_news_white.svg';
            $data['phone'] = '+1 (323) 800-1151';
            $data['email'] = 'info@ug.news';
            $data['address'] = '5670 Wilshire blvd, Los Angeles, CA 90036';

            $data['square']['access_token'] = 'EAAAEIMwBOa4v75eWMriPMybaVP74H5ga_jYW6gpCtCnOHa5CkwAwiUIKE1uZlCL';
            $data['square']['location_id'] = '944YWA55S1RRS';

        return $data;
    }

}

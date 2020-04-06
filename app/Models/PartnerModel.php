<?php
namespace Models;
use Core\Model;
use Helpers\Cookie;

class PartnerModel extends Model{

    public function __construct(){
        parent::__construct();
    }


    public static function getInfo($partner_id=0){

        $data = [];
        if($partner_id==0) {
            $data['id'] = Cookie::get('partner_id');
        }else{
            $data['id'] = $partner_id;
        }


            $data['name'] = 'USABN';
            $data['website'] = 'usabn.org';
            $data['header_logo'] = 'usabn.svg';
            $data['header_logo_white'] = 'usabn_white.svg';
            $data['phone'] = '+1 (323) 800-1151';
            $data['email'] = 'info@usabn.org';
            $data['address'] = '5670 Wilshire blvd, Los Angeles, CA 90036';

            $data['square']['access_token'] = 'EAAAEIMwBOa4v75eWMriPMybaVP74H5ga_jYW6gpCtCnOHa5CkwAwiUIKE1uZlCL';
            $data['square']['location_id'] = '944YWA55S1RRS';

        return $data;
    }

}

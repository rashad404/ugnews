<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Core\Language;
use Helpers\Url;
use Models\BlogModel;
use Models\SiteModel;


/**
 * Sample controller showing a construct and 2 methods and their typical usage.
 */
class Visa extends Controller
{

    public $lng;
    // Call the parent construct
    public function __construct()
    {
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
        new SiteModel();
    }

    public function index(){
        $data['title'] = 'Viza dəstəyi, Viza almaq, Viza xidməti, viza müraciəti, visa desteyi';
        $data['keywords'] = 'Viza dəstəyi, Viza almaq, Viza xidməti, viza müraciəti, visa desteyi';
        $data['description'] = 'Viza dəstəyi, Viza almaq, Viza xidməti, viza müraciəti, visa desteyi';
        $data['def_language'] = self::$def_language;

        View::render('visa/'.__FUNCTION__, $data);
    }

    public function schengen(){
        $data['title'] = 'Şengen viza dəstəyi, Avropa vizası, Schengen vizası, Şengen vizası almaq, Şengen viza xidməti';
        $data['keywords'] = 'Şengen viza dəstəyi, Avropa vizası, Schengen vizası, Şengen vizası almaq, Şengen viza xidməti';
        $data['description'] = 'Şengen viza dəstəyi, Avropa vizası, Schengen vizası, Şengen vizası almaq, Şengen viza xidməti';
        $data['def_language'] = self::$def_language;

        View::render('visa/'.__FUNCTION__, $data);
    }
    public function usa(){
        $data['title'] = 'USA viza dəstəyi, Amerika vizası, Birləşmiş Ştatlar vizası, Amerikaya viza almaq, Amerika viza xidməti';
        $data['keywords'] = 'USA viza dəstəyi, Amerika vizası, Birləşmiş Ştatlar vizası, Amerikaya viza almaq, Amerika viza xidməti';
        $data['description'] = 'USA viza dəstəyi, Amerika vizası, Birləşmiş Ştatlar vizası, Amerikaya viza almaq, Amerika viza xidməti';
        $data['def_language'] = self::$def_language;

        View::render('visa/'.__FUNCTION__, $data);
    }
    public function uk(){
        $data['title'] = 'İngiltərə viza dəstəyi, London vizası, Birləşmiş Krallıq vizası, İngiltərəyə viza almaq, İngiltərə viza xidməti';
        $data['keywords'] = 'İngiltərə viza dəstəyi, London vizası, Birləşmiş Krallıq vizası, İngiltərəyə viza almaq, İngiltərə viza xidməti';
        $data['description'] = 'İngiltərə viza dəstəyi, London vizası, Birləşmiş Krallıq vizası, İngiltərəyə viza almaq, İngiltərə viza xidməti';
        $data['def_language'] = self::$def_language;

        View::render('visa/'.__FUNCTION__, $data);
    }
    public function canada(){
        $data['title'] = 'Kanada viza dəstəyi, Canada vizası, Kanada vizası, Kanadaya viza almaq, Kanada viza xidməti';
        $data['keywords'] = 'Kanada viza dəstəyi, Canada vizası, Kanada vizası, Kanadaya viza almaq, Kanada viza xidməti';
        $data['description'] = 'Kanada viza dəstəyi, Canada vizası, Kanada vizası, Kanadaya viza almaq, Kanada viza xidməti';
        $data['def_language'] = self::$def_language;

        View::render('visa/'.__FUNCTION__, $data);
    }
    public function uae(){
        $data['title'] = 'Dubay viza dəstəyi, Birləşmiş Ərəb Əmirlikləri vizası, Dubay vizası, Dubaya viza almaq, Dubay viza xidməti, BƏƏ vizası';
        $data['keywords'] = 'Dubay viza dəstəyi, Birləşmiş Ərəb Əmirlikləri vizası, Dubay vizası, Dubaya viza almaq, Dubay viza xidməti, BƏƏ vizası';
        $data['description'] = 'Dubay viza dəstəyi, Birləşmiş Ərəb Əmirlikləri vizası, Dubay vizası, Dubaya viza almaq, Dubay viza xidməti, BƏƏ vizası';
        $data['def_language'] = self::$def_language;

        View::render('visa/'.__FUNCTION__, $data);
    }
    public function china(){
        $data['title'] = 'Çin viza dəstəyi, Çin vizası, Çinə viza almaq, Çin viza xidməti';
        $data['keywords'] = 'Çin viza dəstəyi, Çin vizası, Çinə viza almaq, Çin viza xidməti';
        $data['description'] = 'Çin viza dəstəyi, Çin vizası, Çinə viza almaq, Çin viza xidməti';
        $data['def_language'] = self::$def_language;

        View::render('visa/'.__FUNCTION__, $data);
    }

}

<?php
namespace Modules\partner\Controllers;

use Helpers\Csrf;
use Helpers\Database;
use Helpers\File;
use Helpers\Security;
use Helpers\Session;
use Helpers\Validator;
use Core\View;
use Core\Language;
use Models\LanguagesModel;
use Helpers\Url;

class Params extends MyController
{
    public function __construct()
    {
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
    }

    private static $cName = 'params';
    private static $rules = [
        'DEFAULT_CURRENCY' => ['min_length(1)', 'max_length(3)'],
        'SITE_EMAIL' => ['min_length(5)', 'max_length(30)','email'],
        'GOOGLE_ANALYTICS' => ['min_length(10)', 'max_length(20)'],
    ];

    private static function naming(){
        return include SMVC.'app/language/'.LanguagesModel::defaultLanguage().'/naming.php';
    }

    protected static function getPost()
    {
        extract($_POST);
        $array = [];
        $skip_list = ['csrf_token','submit'];
        foreach($_POST as $key=>$value){
            if (in_array($key, $skip_list)) continue;
            $array[$key] = Security::safe($_POST[$key]);
        }

        return $array;
    }


    public function update()
    {
        if(isset($_POST["submit"]) && Csrf::isTokenValid() ){
            $postArray = $this->getPost();

            $validator = Validator::validate($postArray, self::$rules, self::naming());

            if ($validator->isSuccess()) {

                $array_text = '<?php $params_list = [';
                foreach ($postArray as $key =>$value) {
                    $array_text .= '\''.$key.'\' => \''.$value.'\', ';
                }
                $array_text .= '];';

                $update = File::create('app/Core/ParamsInclude.php',$array_text);
                if(!$update){
                    $return['errors'] = 'Can\'t create params file';
                }
            }else{
                $return['errors'] = implode('<br/>',$validator->getErrors());
            }

            if(empty($return['errors'])){
                Session::setFlash('success','Məlumatlar yadda saxlanıldı');
                Url::redirect(MODULE_PARTNER."/".self::$cName."/update/");
            }else{
                Session::setFlash('error',$return['errors']);
            }
        }
        $data = [];
        View::renderPartner(self::$cName.'/update',[
            'data' => $data
        ]);
    }


}
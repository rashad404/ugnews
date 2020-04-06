<?php

namespace Modules\user\Models;

use Core\Model;
use Helpers\Security;
use Helpers\Session;
use Helpers\Validator;
use Models\PartnerModel;
use SquareConnect;

class PaymentsModel extends Model{

    private static $tableNameLogs = 'balance_logs';
    private static $tableNameUsers = 'users';

    private static $params;
    private static $user_id;
    private static $partner_id;
    private static $rules;

    public function __construct($params=''){
        parent::__construct();
        self::$params = $params;
        self::$rules = [
            'amount' => ['min(1)', 'max(2000)', 'positive', 'amount'],
        ];
        self::$user_id = Session::get('user_session_id');
        $user_info = UserModel::getItem(self::$user_id);
        self::$partner_id = $user_info['partner_id'];
    }


    protected static function getPost()
    {
        extract($_POST);
        $skip_list = ['csrf_token','image'];
        $array = [];
        foreach($_POST as $key=>$value){
            if (in_array($key, $skip_list)) continue;
            $array[$key] = Security::safe($_POST[$key]);
        }
        return $array;
    }


    public static function naming(){
        return [];
    }


    public static function pay()
    {
        $partner_array = PartnerModel::getInfo(self::$partner_id);
        $access_token = $partner_array['square']['access_token'];
        $location_id = $partner_array['square']['location_id'];


        $return = [];
        $return['errors'] = null;
        $post_data = self::getPost();
        $validator = Validator::validate($post_data, self::$rules, self::naming());

        if ($validator->isSuccess()) {
            $return['errors'] = null;

//            $access_token = 'EAAAEIMwBOa4v75eWMriPMybaVP74H5ga_jYW6gpCtCnOHa5CkwAwiUIKE1uZlCL';

            # setup authorization
                        $api_config = new SquareConnect\Configuration();
                        $api_config->setHost("https://connect.squareup.com");
                        $api_config->setAccessToken($access_token);
                        $api_client = new SquareConnect\ApiClient($api_config);

            # create an instance of the Payments API class
                        $payments_api = new SquareConnect\Api\PaymentsApi($api_client);
//                        $location_id = '944YWA55S1RRS';
                        $nonce = $_POST['nonce'];

            $body = new SquareConnect\Model\CreatePaymentRequest();

            $amountMoney = new SquareConnect\Model\Money();

            # Monetary amounts are specified in the smallest unit of the applicable currency.
            # This amount is in cents. It's also hard-coded for $1.00, which isn't very useful.

            $amount = intval($_POST['amount'] * 100);
            $original_amount = $amount/100;
            $charge_amount = $amount*1.03;

            $amountMoney->setAmount($charge_amount);
            $amountMoney->setCurrency("USD");

            $body->setSourceId($nonce);
            $body->setAmountMoney($amountMoney);
            $body->setLocationId($location_id);

            # Every payment you process with the SDK must have a unique idempotency key.
            # If you're unsure whether a particular payment succeeded, you can reattempt
            # it with the same idempotency key without worrying about double charging
            # the buyer.
            $body->setIdempotencyKey(uniqid());

            try {
                $result = $payments_api->createPayment($body);
//                print_r($result);exit;
//                echo 'Payment success!';
//                $return['errors'] = $result;
                $description =  "#card";
                $log_data = [
                    'user_id'=>self::$user_id,
                    'partner_id'=>self::$partner_id,
                    'action'=> 'card',
                    'amount'=> '-'.$original_amount,
                    'description'=> $description,
                    'time'=> time(),
                ];
                self::$db->insert(self::$tableNameLogs, $log_data);
                self::$db->raw('UPDATE '.self::$tableNameUsers.' SET `balance`=`balance`-'.$original_amount.' WHERE `id`='.self::$user_id);


            } catch (SquareConnect\ApiException $e) {
//                echo "Exception when calling PaymentsApi->createPayment:";
//                var_dump($e->getResponseBody());
//                exit;
                $return['errors'] = 'Payment Error (TransactionApi->charge)';
            }













//            $access_token = 'EAAAEIMwBOa4v75eWMriPMybaVP74H5ga_jYW6gpCtCnOHa5CkwAwiUIKE1uZlCL';
//            SquareConnect\Configuration::getDefaultConfiguration()->setAccessToken($access_token);
//            $transactions_api = new SquareConnect\Api\TransactionsApi();
//            $location_id = '944YWA55S1RRS';
//            $nonce = $_POST['nonce'];

//
//            $amount = intval($_POST['amount'] * 100);
//            $original_amount = $amount/100;
//            $request_body = array (
//                "card_nonce" => $nonce,
//                "amount_money" => array (
//                    "amount" => $amount,
//                    "currency" => "USD"
//                ),
//                "idempotency_key" => uniqid()
//            );

//            try {
//                $result = $transactions_api->charge($location_id,  $request_body);
//
//                if($result['transaction']['id']){
//                    echo 'Payment success!';
//                    $description =  "#".$result['transaction']['id']."";
//                    $log_data = [
//                        'user_id'=>self::$user_id,
//                        'partner_id'=>self::$partner_id,
//                        'action'=> 'card',
//                        'amount'=> '-'.$original_amount,
//                        'description'=> $description,
//                        'time'=> time(),
//                    ];
//                    self::$db->insert(self::$tableNameLogs, $log_data);
//                    self::$db->raw('UPDATE '.self::$tableNameUsers.' SET `balance`=`balance`-'.$original_amount.' WHERE `id`='.self::$user_id);
//
//                }
//            } catch (SquareConnect\ApiException $e) {
//                $return['errors'] = 'Payment Error (TransactionApi->charge)';
////                var_dump($e->getResponseBody());
//            }

        }else{
            $return['errors'] = implode('<br/>',array_map("ucfirst", $validator->getErrors()));
        }
        return $return;

    }
}

?>
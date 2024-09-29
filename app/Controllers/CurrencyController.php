<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Models\CurrencyModel;
use Helpers\Pagination;
use Models\SeoModel;

class CurrencyController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    // Currencies page
    public function index() {
        $data = SeoModel::general();

        // Fetch all currencies from the database
        $currencyModel = new CurrencyModel();
        $data['currencies'] = $currencyModel->getAllCurrencies();
        $data['def_language'] = 'en'; // Assuming default language is set as English

        // Load the view and pass the data
        View::render('site/currencies', $data);
    }
}

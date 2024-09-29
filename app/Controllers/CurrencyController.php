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
        $data = SeoModel::currencies();

        // Fetch all currencies from the database
        $currencyModel = new CurrencyModel();
        $data['currencies'] = $currencyModel->getAllCurrencies();
        $data['def_language'] = self::$def_language;

        // Load the view and pass the data
        View::render('site/currencies', $data);
    }
}

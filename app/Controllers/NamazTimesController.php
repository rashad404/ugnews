<?php
namespace Controllers;

use Core\Controller;
use Core\View;
use Models\NamazTimesModel;
use Models\SeoModel;

class NamazTimesController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    // Namaz times page
    public function index() {
        $data = SeoModel::namaz_times();

        // Fetch all namaz times from the database
        $namazTimesModel = new NamazTimesModel();
        $data['namaz_times'] = $namazTimesModel->getAllNamazTimes();
        $data['def_language'] = 'en'; // Assuming default language is set as English

        // Load the view and pass the data
        View::render('site/namaz_times', $data);
    }
}

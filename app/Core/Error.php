<?php
/**
 * Error class - calls a 404 page.
 *
 */

namespace Core;

use Core\Controller;
use Core\View;
use Helpers\Url;

/**
 * Error class to generate 404 pages.
 */
class Error extends Controller
{
    /**
     * $error holder.
     *
     * @var string
     */
    private $error = null;

    /**
     * Save error to $this->error.
     *
     * @param string $error
     */
    public function __construct($error)
    {
        parent::__construct();
        $this->error = $error;
    }

    /**
     * Load a 404 page with the error message.
     */
    public function index()
    {
        header("HTTP/1.0 404 Not Found");

        $data['title'] = '404';
        $data['error'] = $this->error;
        ?>

        <style>
            div.not_found{
                text-align: center;
                font-size: 48px;
            }
            div.link{
                text-align: center;
                padding-top: 20px;
                font-size: 28px;
            }
            div.link a{
                text-decoration: none;
            }
            div.link a:hover{
                color:red;
            }
        </style>
        <div class="not_found">404 Page Not Found</div>
        <div class="link">
            <a href="/">Return to main page</a>
        </div>

<?php
        exit;
        View::render('error/404', $data, 'error');
    }
	
	public function module_index($modul='https://ug.news')
    {
       return  Url::redirect($modul."/error/index404");
    }

    /**
     * Display errors.
     *
     * @param  array  $error an error of errors
     * @param  string $class name of class to apply to div
     *
     * @return string return the errors inside divs
     */
    public static function display($error, $class = 'alert alert-danger')
    {
        $row = '';
        if (is_array($error)) {
            foreach ($error as $error) {
                $row .= "<div class='$class'>$error</div>";
            }
            return $row;
        } else {
            if (isset($error)) {
                return "<div class='$class'>$error</div>";
            }
        }
    }
}

<?php
/**
 * url Class
 *
 * @author David Carr - dave@daveismyname.com
 * @version 2.2
 * @date June 27, 2014
 * @date updated Sept 19, 2015
 */

namespace Helpers;

/**
 * Collection of methods for working with urls.
 */
class Url
{
    /**
     * Redirect to chosen url.
     *
     * @param  string  $url      the url to redirect to
     * @param  boolean $fullpath if true use only url in redirect instead of using DIR
     */
    public static function redirect($url = null, $fullpath = false)
    {
        if ($fullpath == false) {
            $url = DIR.$url;
        }
        header('Location: '.$url);
        exit;
    }


    /**
     * Created the absolute address to the template folder.
     *
     * @param  boolean $custom
     * @return string url to template folder
     */
    public static function templatePath($templateName = DEFAULT_TEMPLATE){
        return  '/app/templates/'.$templateName.'/';
    }
	public static function templateModulePath($moduleName=MODULE_ADMIN,$moduleTemplateName = DEFAULT_MODULE_TEMPLATE){
        return  DIR.'app/Modules/'.$moduleName.'/templates/'.$moduleTemplateName.'/';
    }
	public static function templateUserPath(){
        return  DIR.'app/Modules/user/templates/main/';
    }
	public static function templatePartnerPath(){
        return  DIR.'app/Modules/partner/templates/main/';
    }

    public static function getPath($path = 'Helpers'){
        return  DIR.'app/'.$path.'/';
    }

    public static function filePath($path = 'uploads'){
        return  DIR.'Web/'.$path.'/';
    }

    public static function uploadPath($path = 'uploads'){
        return  'Web/'.$path.'/';
    }

    public static function serverPath()
    {
        return Security::safe($_SERVER['DOCUMENT_ROOT']);
    }
    public static function trendyolImgPath()
    {
        return 'https://img-trendyol.mncdn.com/mnresize/415/622/';
    }

    public static function to($url)
    {
        return DIR.$url;
    }

    /**
     * Created the relative address to the template folder.
     *
     * @param  boolean $custom
     * @return string url to template folder
     */
    public static function relativeTemplatePath()
    {
        return "app/templates/";
    }

    /**
     * Converts plain text urls into HTML links, second argument will be
     * used as the url label <a href=''>$custom</a>.
     *
     *
     * @param  string $text   data containing the text to read
     * @param  string $custom if provided, this is used for the link label
     *
     * @return string         returns the data with links created around urls
     */
    public static function autoLink($text, $custom = null)
    {
        $regex   = '@(http)?(s)?(://)?(([-\w]+\.)+([^\s]+)+[^,.\s])@';

        if ($custom === null) {
            $replace = '<a href="http$2://$4">$1$2$3$4</a>';
        } else {
            $replace = '<a href="http$2://$4">'.$custom.'</a>';
        }

        return preg_replace($regex, $replace, $text);
    }

    /**
     * This function converts and url segment to an safe one, for example:
     * `test name @132` will be converted to `test-name--123`
     * Basicly it works by replacing every character that isn't an letter or an number to an dash sign
     * It will also return all letters in lowercase.
     *
     * @param $slug - The url slug to convert
     *
     * @return mixed|string
     */
    public static function generateSafeSlug($slug)
    {
        setlocale(LC_ALL, "en_US.utf8");

        $slug = preg_replace('/[`^~\'"]/', null, iconv('UTF-8', 'ASCII//TRANSLIT', $slug));

        $slug = htmlentities($slug, ENT_QUOTES, 'UTF-8');

        $pattern = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
        $slug = preg_replace($pattern, '$1', $slug);

        $slug = html_entity_decode($slug, ENT_QUOTES, 'UTF-8');

        $pattern = '~[^0-9a-z]+~i';
        $slug = preg_replace($pattern, '-', $slug);

        return strtolower(trim($slug, '-'));
    }

    /**
     * Go to the previous url.
     */
    public static function previous($url = '')
    {   $referer  = $_SERVER['HTTP_REFERER'];
        if($referer==''){
            $referer = self::to($url);
        }
        header('Location: '. $referer);
        exit;
    }



    /**
     * Get all url parts based on a / seperator.
     *
     * @return array of segments
     */
    public static function segments()
    {
        return explode('/', $_SERVER['REQUEST_URI']);
    }

    /**
     * Get item in array.
     *
     * @param  array $segments array
     * @param  int $id array index
     *
     * @return string - returns array index
     */
    public static function getSegment($segments, $id)
    {
        if (array_key_exists($id, $segments)) {
            return $segments[$id];
        }
        return null;
    }

    /**
     * Get last item in array.
     *
     * @param  array $segments
     * @return string - last array segment
     */
    public static function lastSegment($segments)
    {
        return end($segments);
    }

    /**
     * Get first item in array
     *
     * @param  array segments
     * @return int - returns first first array index
     */
    public static function firstSegment($segments)
    {
        return $segments[0];
    }
	
	public static function getController()
    {
        $uri = parse_url($_SERVER['QUERY_STRING'], PHP_URL_PATH);
		$uri=explode("/",$uri);
		if(is_dir('app/Modules/'.$uri[0]))
		{
			if(isset($uri[1]) && $uri[1]!='') $controller=$uri[1];
			if(!isset($controller) OR $controller=='') $controller=DEFAULT_MODULE_CONTROLLER;
		}
		else
		{
			if(isset($uri[0]) && $uri[0]!='') $controller=$uri[0];
			if(!isset($controller) OR $controller=='') $controller=DEFAULT_CONTROLLER;
		}

        $controller=explode("&",$controller);
        $controller=$controller[0];
		return $controller;
    }
	
	public static function getMethod()
    {
        $uri = parse_url($_SERVER['QUERY_STRING'], PHP_URL_PATH);
		$uri=explode("/",$uri);
		if(is_dir('app/Modules/'.$uri[0]))
		{
			if(isset($uri[2]) && $uri[2]!='') $method=$uri[2];
			else $method=DEFAULT_MODULE_METHOD;
		}
		else
		{
			if(isset($uri[1]) && $uri[1]!='') $method=$uri[1];
			else $method=DEFAULT_METHOD;
		}
        $method=explode("&",$method);
        $method=$method[0];

		return $method;
    }
	
	public static function getModule()
    {
        $uri = parse_url($_SERVER['QUERY_STRING'], PHP_URL_PATH);
		$uri=explode("/",$uri);
		if(is_dir('app/Modules/'.$uri[0]))
		{
			$module=$uri[0];
		}
		else
		{
			$module=false;
		}
		return $module;
    }

    public static function getModuleController()
    {
        $uri = parse_url($_SERVER['QUERY_STRING'], PHP_URL_PATH);
        $uri=explode("/",$uri);
        $moduleController='';
        if(is_dir('app/Modules/'.$uri[0])){
            $moduleController.=$uri[0].'/';

            if(isset($uri[1]) && $uri[1]!='') $controller=$uri[1];
            if(!isset($controller) || $controller=='') $controller=DEFAULT_MODULE_CONTROLLER;
        }
        else{
            if(isset($uri[0]) && $uri[0]!='') $controller=$uri[0];
            if(!isset($controller) || $controller=='') $controller=DEFAULT_CONTROLLER;
        }

        $controller=explode("&",$controller);
        $controller=$controller[0];
        return $moduleController.=$controller.'/';
    }


	
	public static function getArgs()
    {
        $uri = parse_url($_SERVER['QUERY_STRING'], PHP_URL_PATH);
		$uri=explode("/",$uri);
		if(is_dir('app/Modules/'.$uri[0]))
		{
			if(count($uri)>3){
				array_shift($uri);	array_shift($uri);	array_shift($uri);	$args=$uri;
			}
			else $args=array();
		}
		else
		{
			if(count($uri)>2){
				array_shift($uri);	array_shift($uri);	$args=$uri;
			}
			else $args=array();
		}
		return $args;
    }

    public static function getFullUrl()
    {
        $uri = parse_url($_SERVER['QUERY_STRING'], PHP_URL_PATH);
        return $uri;
    }

    public static function addFullUrl($adds=[]){
        $uri = self::getFullUrl();
        foreach($adds as $variable=>$value){
            if(strpos($uri,$variable."=")>0) $uri=self::remove_qs_key($uri,$variable);
            $uri.='&'.$variable.'='.$value;
        }
        if(strpos($uri,"&")>0) $uri=substr_replace($uri,"?",strpos($uri,"&"),1);
        return $uri;
    }

    public static function remove_qs_key($url, $key) {
        $url = preg_replace('/(?:&|(\?))' . $key . '=[^&]*(?(1)&|)?/i', "$1", $url);
        return $url;
    }


    public static function str2Url($string)
    {
        $converter = array(
            'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
            'ı' => 'i', 'ə' => 'e', 'ş' => 's',
            'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
            'İ' => 'I', 'Ə' => 'E', 'Ş' => 'S',
            'Ç' => 'C', 'Š' => 'S', 'š' => 's',
            'Ž' => 'Z', 'ž' => 'z', 'À' => 'A',
            'Á' => 'A', 'Â' => 'A', 'Ã' => 'A',
            'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A',
            'È' => 'E', 'É' => 'E', 'Ê' => 'E',
            'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I',
            'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N',
            'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O',
            'Õ' => 'O', 'Ø' => 'O', 'Ù' => 'U',
            'Ú' => 'U', 'Û' => 'U', 'Ý' => 'Y',
            'Þ' => 'B', 'ß' => 'Ss','à' => 'a',
            'á' => 'a', 'â' => 'a', 'ã' => 'a',
            'ä' => 'a', 'å' => 'a', 'æ' => 'a',
            'ç' => 'c', 'è' => 'e', 'é' => 'e',
            'ê' => 'e', 'ë' => 'e',
            'ì' => 'i', 'í' => 'i', 'î' => 'i',
            'ï' => 'i', 'ð' => 'o', 'ñ' => 'n',
            'ò' => 'o', 'ó' => 'o', 'ô' => 'o',
            'õ' => 'o', 'ø' => 'o', 'ù' => 'u',
            'ú' => 'u', 'û' => 'u', 'ý' => 'y',
            'þ' => 'b', 'ÿ' => 'y', 'А' => 'A',
            'Б' => 'B', 'В' => 'V', 'Г' => 'G',
            'Ğ' => 'G', 'Д' => 'D', 'Ё' => 'YO',
            'Ж' => 'ZH', 'З' => 'Z', 'И' => 'I',
            'Й' => 'Y', 'К' => 'K', 'Л' => 'L',
            'П' => 'P','У' => 'U', 'Ф' => 'F',
            'Ц' => 'C', 'Ч' => 'CH','Ш' => 'SH',
            'Щ' => 'SCH', 'Ъ' => '', 'Ы' => 'Y',
            'Ь' => '', 'Э' => 'E','Ю' => 'YU',
            'Я' => 'YA', 'а' => 'a', 'б' => 'b',
            'в' => 'v', 'г' => 'g','д' => 'd',
            'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
            'з' => 'z','и' => 'i', 'й' => 'y',
            'к' => 'k', 'л' => 'l', 'м' => 'm',
            'н' => 'n','о' => 'o', 'п' => 'p',
            'р' => 'r','с' => 's', 'т' => 't',
            'у' => 'u','ф' => 'f', 'х' => 'h',
            'ц' => 'c', 'ч' => 'ch', 'ш' => 'sh',
            'щ' => 'sch','ъ' => '', 'ы' => 'y',
            'ь' => '', 'э' => 'e', 'ю' => 'yu',
            'я' => 'ya','?' => '-',':' => '-',
            "'" => ' ', '"' => ' ', '»' => '',
            '«' => ''
        );

        $string = strtr($string, $converter);

        $str = strtolower($string);

        // заменям все ненужное нам на "-"
        $str = preg_replace('~[^-a-z0-9_]+~u', '-', $str);

        // удаляем начальные и конечные '-'
        $str = trim($str, "-");

        $str = substr($str, 0, 60);
        return $str;

    }

    public static function getUserImage($id, $gender=1)
    {

        if (file_exists(Url::uploadPath() . 'users/' . $id . '.jpg')) {
            $return = Url::filePath() . 'users/' . $id . '.jpg?ref=' . rand(1111111, 9999999);
        }elseif($gender==2){
            $return = URL::templatePath() . 'img/profile-photo-female.png';
        }else{
            $return = URL::templatePath() . 'img/profile-photo.png';
        }

        return $return;
    }
}



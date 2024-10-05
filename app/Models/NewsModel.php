<?php
namespace Models;
use Core\Model;
use Helpers\Cookie;
use Helpers\Security;
use Helpers\Session;
use Helpers\Validator;
use Core\Language;
use Helpers\Url;

class NewsModel extends Model
{
    private static $tableName = 'news';
    private static $tableNameCategories = 'categories';
    private static $tableNameTags = 'tags';
    private static $tableNameChannels = 'channels';
    private static $tableNameSubscribers = 'subscribers';
    private static $tableNameLikes = 'likes';
    private static $tableNameUniequeViews = 'unique_views';
    private static $region;
    public $lng;
    public function __construct()
    {
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
        self::$region = Cookie::get('set_region');
        if (self::$region == 0) {
            self::$region = DEFAULT_COUNTRY;
        }
    }

    public static function getList($limit = 'LIMIT 0,10')
    {
        $array = self::$db->select('SELECT `id`,`publish_time`,`title`,`slug`,`title_extra`,`text`,`tags`,`thumb`,`image`,`user_id`,`category_id`,`view`,`channel_id` FROM `' . self::$tableName . '` WHERE `publish_time`<=' . time() . " AND `status`=1 AND `country_id`='" . self::$region . "' ORDER BY `publish_time` DESC $limit");
        return $array;
    }
    public static function getSimilarNews($id, $limit = 6)
    {
        $array = self::getItem($id, false);
        $title = Security::safe($array['title']);
        
        //SELECT *,
        //MATCH(`name`, `middlename`, `surname`) AGAINST ('John' IN NATURAL LANGUAGE MODE) AS score
        //FROM person
        //ORDER BY score DESC;
        $array = self::$db->select(
            "SELECT `id`,`publish_time`,`title`,`title_extra`,`text`,`slug`,`tags`,`thumb`,`image`,`user_id`,`category_id`,`view`,`channel_id`,
 MATCH(`title`,`text`) AGAINST ('" .
                $title .
                "' IN NATURAL LANGUAGE MODE) AS score
 FROM `" .
                self::$tableName .
                '` WHERE `id`!=' .
                $id .
                " AND `status`=1 AND `country_id`='" .
                self::$region .
                "' ORDER BY `score` DESC LIMIT $limit",
        );
        return $array;
    }

    //Cats
    public static function getListByCat($id, $limit = 'LIMIT 0,10')
    {
        $array = self::$db->select('SELECT `id`,`publish_time`,`title`,`title_extra`,`text`,`tags`,`thumb`,`slug`,`image`,`user_id`,`category_id`,`view`,`channel_id` FROM `' . self::$tableName . '` WHERE `publish_time`<=' . time() . " AND `status`=1 AND `country_id`='" . self::$region . "' AND `category_id`='" . $id . "' ORDER BY `publish_time` DESC $limit");
        return $array;
    }

    //Cats
    public static function getListByChannel($id, $limit = 'LIMIT 0,10')
    {
        $array = self::$db->select('SELECT `id`,`publish_time`,`title`,`title_extra`,`text`,`tags`,`thumb`,`slug`,`image`,`user_id`,`category_id`,`view`,`channel_id` FROM `' . self::$tableName . '` WHERE `publish_time`<=' . time() . " AND `status`=1 AND `channel_id`='" . $id . "' ORDER BY `publish_time` DESC $limit");
        return $array;
    }

    public static function countListByCat($cat)
    {
        $array = self::$db->count('SELECT count(id) FROM `' . self::$tableName . "` WHERE `status`=1 AND `country_id`='" . self::$region . "' AND `category_id`='" . $cat . "'");
        return $array;
    }
    public static function getCategoryIdByName($name)
    {
        $array = self::$db->select('SELECT `id` FROM `' . self::$tableNameCategories . "` WHERE `status`=1  AND `name`='" . $name . "'");
        return $array;
    }

    public static function countListByChannel($id)
    {
        $array = self::$db->count('SELECT count(id) FROM `' . self::$tableName . "` WHERE `status`=1 AND `country_id`='" . self::$region . "' AND `channel_id`='" . $id . "'");
        return $array;
    }

    public static function countList()
    {
        $array = self::$db->count('SELECT count(id) FROM `' . self::$tableName . "` WHERE `status`=1 AND `country_id`='" . self::$region . "'");
        return $array;
    }

    //Tags cat
    public static function getListByTagCat($id, $limit = 'LIMIT 0,10')
    {
        $tag = self::getTagName($id);
        $array = self::$db->select('SELECT `id`,`time`,`publish_time`,`title`,`title_extra`,`text`,`tags`,`thumb`,`image`,`slug`,`user_id`,`category_id`,`view`,`channel_id` FROM `' . self::$tableName . "` WHERE `status`=1 AND `country_id`='" . self::$region . "' AND  FIND_IN_SET ('" . $tag . "', `tags`) ORDER BY `id` DESC $limit");
        return $array;
    }

    public static function countListByTagCat($cat)
    {
        $tag = self::getTagName($cat);
        $array = self::$db->count('SELECT count(id) FROM `' . self::$tableName . "` WHERE `status`=1 AND  FIND_IN_SET ('" . $tag . "', `tags`)");
        return $array;
    }

    //City
    public static function getListByCity($id, $limit = 'LIMIT 0,10')
    {
        $array = self::$db->select('SELECT `id`,`time`,`publish_time`,`title`,`title_extra`,`slug`,`text`,`tags`,`thumb`,`image`,`user_id`,`category_id`,`view`,`channel_id` FROM `' . self::$tableName . "` WHERE `status`=1 AND `city_id`='" . $id . "' ORDER BY `id` DESC $limit");
        return $array;
    }

    public static function countListByCity($id)
    {
        $array = self::$db->count('SELECT count(id) FROM `' . self::$tableName . "` WHERE `status`=1 AND `city_id`='" . $id . "'");
        return $array;
    }

    //Tags
    public static function getListByTag($tag, $limit = 'LIMIT 0,10')
    {
        $array = self::$db->select('SELECT `id`,`publish_time`,`title`,`title_extra`,`text`,`tags`,`slug`,`thumb`,`image`,`user_id`,`category_id`,`view`,`channel_id` FROM `' . self::$tableName . '` WHERE `publish_time`<=' . time() . " AND `status`=1 AND  FIND_IN_SET ('" . $tag . "', `tags`) ORDER BY `publish_time` DESC $limit");
        return $array;
    }

    public static function countListByTag($tag)
    {
        $array = self::$db->count('SELECT count(id) FROM `' . self::$tableName . "` WHERE `status`=1 AND  FIND_IN_SET ('" . $tag . "', `tags`)");
        return $array;
    }

    private static function isBrowserAllowed()
    {
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $skipList = ['Wget', 'SemrushBot', 'Barkrowler', 'AhrefsBot', 'YandexBot', 'MJ12bot', 'DotBot', 'ImagesiftBot', 'ClaudeBot', 'bingbot', 'Googlebot', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.0.0 Safari/537.36', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/109.0.0.0 Safari/537.36', 'testadd'];

        $pattern = '~' . implode('|', array_map('preg_quote', $skipList, array_fill(0, count($skipList), '~'))) . '~i';
        $matches = preg_grep($pattern, [$userAgent]);

        if (!empty($matches)) {
            return false;
        }

        return true;
    }
    private static function calculateUniqueView($newsId)
    {
        // We don't want to process for bots, search engines etc.
        if (!self::isBrowserAllowed()) {
            return false;
        }

        // Check if the user has a cookie named "ugnews_uv1"; if not, generate one
        if (!isset($_COOKIE['ugnews_uv1'])) {
            $cookieIdentifier = uniqid(); // Generate a unique identifier
            setcookie('ugnews_uv1', $cookieIdentifier, time() + 86400, '/'); // Set the cookie to expire in 24 hours
        } else {
            $cookieIdentifier = $_COOKIE['ugnews_uv1'];
        }

        $userIP = $_SERVER['REMOTE_ADDR'];
        $userAgent = $_SERVER['HTTP_USER_AGENT'];
        $currentTime = time();

        // Check if there is a recent record in the unique_views table with the same IP, user agent, and "ugnews_uv1" cookie identifier

        $where = "`news_id`='$newsId' AND `ip`='$userIP' AND `browser`='$userAgent' AND `cookie`='$cookieIdentifier' AND `create_time` > ($currentTime - 3600)";

        $previousView = self::$db->selectOne('SELECT id FROM `' . self::$tableNameUniequeViews . '` WHERE ' . $where . ' ');

        if ($previousView == 0) {
            // If there are no recent previous views, increment the visit_count and record the unique views

            // Insert Unique Views
            $insert_data = [
                'ip' => $userIP,
                'browser' => $userAgent,
                'cookie' => $cookieIdentifier,
                'visit_count' => 1,
                'create_time' => $currentTime,
                'news_id' => $newsId,
            ];
            self::$db->insert('unique_views', $insert_data);

            // Update News view count
            self::$db->raw('UPDATE `' . self::$tableName . "` SET `view`=`view`+1 WHERE `id`='" . $newsId . "'");

            // If inserted, return true
        } else {
            // Update Unique Views
            self::$db->raw('UPDATE `' . self::$tableNameUniequeViews . "` SET `visit_count`=`visit_count`+1, `update_time`= '" . time() . "' WHERE " . $where . ' ');

            // If inserted, return false
            return false;
        }
    }
    public static function getItem($id, $count = true)
    {
        $array = self::$db->selectOne('SELECT `id`,`publish_time`,`slug`,`title`,`title_extra`,`text`,`tags`,`thumb`,`image`,`user_id`,`category_id`,`view`,`channel_id`,`likes`,`dislikes` FROM `' . self::$tableName . "` WHERE `id`='" . $id . "' AND `status`=1");

        if ($count && $id) {
            $isUniqueView = self::calculateUniqueView($id);
            if ($isUniqueView) {
                self::$db->raw('UPDATE `' . self::$tableNameChannels . "` SET `view`=`view`+1 WHERE `id`='" . $array['channel_id'] . "'");
            }
        }
        return $array;
    }
    public static function getItemName($slug)
    {
        // self::getSlugAll();
        // $normalizedTitle = self::generateSlug($title);

        // return var_dump($slug);
        
    
        $array = self::$db->selectOne('SELECT `id` FROM `' . self::$tableName . "` WHERE `slug`='" . $slug . "'");
    
        if ($array) {
            return $array['id'];
        } else {
            return '';
        }

        
    }

    public static function getSlugAll(){
        self::addSlugColumn();
        $newsList = self::$db->select("SELECT `id`, `title` FROM ".self::$tableName);
        foreach ($newsList as $news) {
            $slug = self::generateSlug($news['title']);
            $updateResult = self::$db->update(self::$tableName, ['slug' => $slug], ['id' => $news['id']]);
            
            if ($updateResult) {
                echo "<p style='color:green;'><b>Slug yaradıldı<b> Xəbər: {$news['title']} - Yeni Slug: {$slug}</b>\n";
            } else {
                echo "<p style='color:red;'>Slug yaradıla bilmədi Xəbər: {$news['title']}</p>\n";
            }
        }
    }
    public static function addSlugColumn()
    {
    $columnExists = self::$db->raw("SHOW COLUMNS FROM " . self::$tableName . " LIKE 'slug'")->rowCount() > 0;

    if (!$columnExists) {
        self::$db->raw("ALTER TABLE " . self::$tableName . " ADD COLUMN `slug` VARCHAR(100) DEFAULT NULL");
    }
    }
    
    protected static function generateSlug($title) {
        $slug = Url::generateSafeSlug($title);
        return $slug;
    }


    public static function getCatName($id)
    {
        $array = self::$db->selectOne('SELECT `name` FROM `' . self::$tableNameCategories . "` WHERE `id`='" . $id . "'");
        if ($array) {
            return $array['name'];
        } else {
            return '';
        }
    }
    public static function getTagName($id)
    {
        $array = self::$db->selectOne('SELECT `name` FROM `' . self::$tableNameTags . "` WHERE `id`='" . $id . "'");
        if ($array) {
            return $array['name'];
        } else {
            return '';
        }
    }

    public static function navigate($id, $action)
    {
        if ($action == 'next') {
            $action_symbol = '>';
        } else {
            $action_symbol = '<';
        }
        $array = self::$db->selectOne('SELECT `id`,`time`,`publish_time`,`title`,`title_extra`,`text`,`tags`,`thumb`,`image`,`user_id` FROM `' . self::$tableName . '` WHERE `id` ' . $action_symbol . " '" . $id . "' AND `status`=1 ORDER BY `id` DESC");
        return $array;
    }

    public static function subscribeCheck($id)
    {
        $user_id = intval(Session::get('user_session_id'));
        $check = self::$db->selectOne('SELECT `id` FROM `' . self::$tableNameSubscribers . '` WHERE `channel_id`=' . $id . " AND `user_id`='" . $user_id . "'");
        if ($check) {
            return true;
        } else {
            return false;
        }
    }

    public static function likeCheck($id)
    {
        $user_id = intval(Session::get('user_session_id'));
        $check = self::$db->selectOne('SELECT `id` FROM `' . self::$tableNameLikes . '` WHERE `liked`=1 AND `news_id`=' . $id . " AND `user_id`='" . $user_id . "'");
        if ($check) {
            return true;
        } else {
            return false;
        }
    }
    public static function dislikeCheck($id)
    {
        $user_id = intval(Session::get('user_session_id'));
        $check = self::$db->selectOne('SELECT `id` FROM `' . self::$tableNameLikes . '` WHERE `disliked`=1 AND `news_id`=' . $id . " AND `user_id`='" . $user_id . "'");
        if ($check) {
            return true;
        } else {
            return false;
        }
    }
}

<?php
namespace Models;
use Core\Model;
use Core\Language;
use Helpers\Console;
use Helpers\Format;
use Helpers\Url;
use Helpers\XLSXReader;

class SitemapModel extends Model{

    private static $tableName = 'seo';
    public $lng;

    public static $limit = 50000;
    public static $website = 'https://lordhousing.com';
    
    public function __construct(){
        parent::__construct();
        $this->lng = new Language();
        $this->lng->load('app');
    }


    public static function update(){

        $data =array();
        $links = array();
        $links[] = self::$website;
        $links[] = self::$website.'/apartments';
        $links[] = self::$website.'/locations';
        $links[] = self::$website.'/apartments/downtown-la';
        $links[] = self::$website.'/apartments/koreatown';
        $links[] = self::$website.'/apartments/santa-monica';
        $links[] = self::$website.'/airport-service';
        $links[] = self::$website.'/how-it-works';
        $links[] = self::$website.'/contact-us';
        $links[] = self::$website.'/about';
        $links[] = self::$website.'/faqs';
        $links[] = self::$website.'/testimonials';
        $links[] = self::$website.'/blog';
        $links[] = self::$website.'/schedule-an-appointment';


        $count_links = count($links);
        for($i=0;$i<$count_links;$i++){
            $data[] = '
				<url>
					<loc>'.$links[$i].'</loc>
					<changefreq>daily</changefreq>
					<priority>0.80</priority>
				</url>
				';
        }

        $group_data = array();
        $sitemap_group_start = '<?xml version="1.0" encoding="UTF-8"?>
   <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        $sitemap_group_end = '</sitemapindex>';

        $sitemap_start = '<?xml version="1.0" encoding="UTF-8"?>
<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">';
        $sitemap_end = '</urlset>';


        $array = self::$db->select("SELECT `id`,`title` FROM `".self::$tableName."` WHERE `status`=1 ORDER BY `id` ASC");
        foreach ($array as $item) {
            $item['title'] = Format::urlText($item['title']);
            
            $data[] = '
			<url>
				<loc>'.self::$website.'/find/'.$item['title'].'</loc>
				<changefreq>daily</changefreq>
				<priority>0.80</priority>
			</url>
		';
        }

        $data = array_unique($data);
        $data = array_values($data);

        $sitemap_count = ceil(count($data)/self::$limit);
        if(count($data)<self::$limit){
            $new_limit = count($data);
        }else{
            $new_limit = self::$limit;
        }

        for($i=1;$i<=$sitemap_count;$i++){
            for($c=($i-1)*$new_limit;$c<$i*$new_limit;$c++){
                $sitemap_body[$i][] = $data[$c];
//                echo $c.'<br/>';
            }

            $group_data[] ='<sitemap>
		  <loc>'.self::$website.'/sitemaps/sitemap'.$i.'.xml</loc>
		  <lastmod>'.date("c").'</lastmod>
	   </sitemap>';

            $sitemap_body_text ='';
            foreach ($sitemap_body[$i] as $sitemap_body_array_name => $sitemap_body_array_value){
                $sitemap_body_text .= $sitemap_body_array_value;
            }
            $fp = fopen('sitemaps/sitemap'.$i.'.xml', 'w');
            fwrite($fp, $sitemap_start.$sitemap_body_text.$sitemap_end);
            fclose($fp);
        }
        $sitemap_group_body_text='';
        foreach ($group_data as $group_array_name => $group_array_value){
            $sitemap_group_body_text .= $group_array_value;
        }

        $fp = fopen('sitemaps/sitemap_index.xml', 'w');
        fwrite($fp, $sitemap_group_start.$sitemap_group_body_text.$sitemap_group_end);
        fclose($fp);

    }


















}

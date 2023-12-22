<?php

namespace Helpers;

class SearchCategoryDetector {

    //Spell suggest: http://php.net/manual/en/function.pspell-suggest.php (aspell php)
    //Detect text on image: https://sourceforge.net/projects/phpocr/
    //https://codereview.stackexchange.com/questions/27173/php-spell-checker-with-suggestions-for-misspelled-words

    //english word list mysql: http://androidtech.com/html/wordnet-mysql-20.php
    //58000 english words lower case: http://www.mieliestronk.com/corncob_lowercase.txt

    //How google search sugegstion works: https://searchengineland.com/how-google-instant-autocomplete-suggestions-work-62592
    //https://www.quora.com/How-does-Googles-autocomplete-feature-work-Can-someone-explain-the-technical-side-of-it

    //Weather api: https://openweathermap.org (https://www.ibm.com/developerworks/library/os-apache-lucenesearch/index.html)
    //Web spider: http://www.openwebspider.org/
    //Bigdata alternative: http://hadoop.apache.org/ (https://www.linkedin.com/pulse/20140715074957-112543856-how-to-build-a-search-engine-like-google/)



    private static $specifications = [
        'calculator' => ['min_length(3)', 'max_length(20)'],
    ];

	public static function detect($query){
//        $query = '3+4 nearMe';
        $query = strtolower($query);
        if(self::weather($query)){
            return 'Weather Detected';
        }
        if(self::timezone($query)){
            return 'Timezone Detected';
        }
        if(self::calculator($query)){
            return self::calculator($query);
        }
        if(self::nearMe($query)){
            return 'Near me Detected';
        }

	}


	public static function weather($query){
        if (preg_match("/weather/", $query)) return true;
    }
	public static function timezone($query){
        if (preg_match("/timezone/", $query)) return true;
    }
	public static function nearMe($query){
        if (preg_match("/near\s*me/", $query)) return true;
    }

	public static function calculator($query){
        if (preg_match("/(\d+)([+-\/\*])(\d+)$/", $query, $matches)) {
            $number1 = $matches[1];
            $operator = $matches[2];
            $number2 = $matches[3];
            if($operator=="-"){
                $result = $number1 - $number2;
            }elseif ($operator=="+"){
                $result = $number1 + $number2;
            }elseif ($operator=="/"){
                $result = $number1 / $number2;
            }elseif ($operator=="*"){
                $result = $number1 * $number2;
            }else{
                $result = false;
            }
            return $result;
        }
    }
	public static function min_length($text, $length){
		if(strlen($text)>$length) return true;
	}
}
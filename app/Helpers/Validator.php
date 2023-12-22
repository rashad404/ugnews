<?php

namespace Helpers;
use Models\LanguagesModel;
use Helpers\Sms;
use Helpers\Date;

class Validator {

	private $errors = array();
	private $namings = array();

	private function __construct($errors, $namings) {
		$this->errors  = (array) $errors;
		$this->namings = (array) $namings;
	}

	public function isSuccess() {
		return (empty($this->errors) == true);
	}

	public function getErrors(){
		return $this->errors;
	}

	public static function validate($array, $rules, $naming, $lang=''){
		if(empty($lang)){
			$validation_lang_texts = include SMVC.'app/language/'.LanguagesModel::defaultLanguage().'/validation.php';
		}else{
			$validation_lang_texts = include SMVC.'app/language/'.$lang.'/validation.php';
		}
		$errors = null;

		foreach ($rules as $input => $input_rules){
		    if(!isset($array[$input]))$array[$input]='';
			$text = $array[$input];

			if (array_key_exists($input, $naming)) {
				$input_name = $naming[ $input ];
			}else{
				$input_name = $input;
			}

			foreach ($input_rules as $rule){

				// Checking required rule
				if(preg_match("/required/",$rule)){
					if(!self::required($text)){
						$errors[] = preg_replace("/:attribute/",$input_name,$validation_lang_texts['required']);
					}
				}

				if(!empty($text)) {
                    // Checking required rule
                    if (preg_match("/amount/", $rule)) {
                        if (!self::amount($text)) {
                            $errors[] = preg_replace("/:attribute/", $input_name, $validation_lang_texts['amount']);
                        }
                    }

                    // Checking prefix rule
                    if (preg_match("/prefix/", $rule)) {
                        if (!self::prefix($text)) {
                            $errors[] = preg_replace("/:attribute/", $input_name, $validation_lang_texts['prefix']);
                        }
                    }

                    // Checking integer rule
                    if (preg_match("/integer/", $rule)) {
                        if (!self::integer($text)) {
                            $errors[] = preg_replace("/:attribute/", $input_name, $validation_lang_texts['integer']);
                        }
                    }
                    // Checking positive rule
                    if (preg_match("/positive/", $rule)) {
                        if (!self::positive($text)) {
                            $errors[] = preg_replace("/:attribute/", $input_name, $validation_lang_texts['positive']);
                        }
                    }
                    // Checking noZero rule
                    if (preg_match("/no_zero/", $rule)) {
                        if (!self::noZero($text)) {
                            $errors[] = preg_replace("/:attribute/", $input_name, $validation_lang_texts['no_zero']);
                        }
                    }
                    // Checking noZero rule for selectbox
                    if (preg_match("/selectbox/", $rule)) {
                        if (!self::noZero($text)) {
                            $errors[] = preg_replace("/:attribute/", $input_name, $validation_lang_texts['selectbox']);
                        }
                    }

                    // Checking phone rule
                    if (preg_match("/phone/", $rule)) {
                        if (!self::phone($text)) {
                            $errors[] = preg_replace("/:attribute/", $input_name, $validation_lang_texts['phone']);
                        }
                    }

                    // Checking full phone rule (prefix+phone)
                    if (preg_match("/fullPhone/", $rule)) {
                        if (!self::fullPhone($text)) {
                            $errors[] = preg_replace("/:attribute/", $input_name, $validation_lang_texts['fullPhone']);
                        }
                    }

                    // Checking email rule
                    if (preg_match("/email/", $rule)) {
                        if (!self::email($text)) {
                            $errors[] = preg_replace("/:attribute/", $input_name, $validation_lang_texts['email']);
                        }
                    }

                    // Checking min length rule
                    if (preg_match("/min_length\((\d+)\)$/", $rule, $matches)) {
                        if (!self::min_length($text, $matches[1])) {
                            $val_error = $validation_lang_texts['min_length'];
                            $val_error = preg_replace("/:attribute/", $input_name, $val_error);
                            $val_error = preg_replace("/:params\(0\)/", $matches[1], $val_error);
                            $errors[] = $val_error;
                        }
                    }

                    // Checking max length rule
                    if (preg_match("/max_length\((\d+)\)$/", $rule, $matches)) {
                        if (!self::max_length($text, $matches[1])) {
                            $val_error = $validation_lang_texts['max_length'];
                            $val_error = preg_replace("/:attribute/", $input_name, $val_error);
                            $val_error = preg_replace("/:params\(0\)/", $matches[1], $val_error);
                            $errors[] = $val_error;
                        }
                    }
                    // Checking exact length rule
                    if (preg_match("/exact_length\((\d+)\)$/", $rule, $matches)) {
                        if (!self::exact_length($text, $matches[1])) {
                            $val_error = $validation_lang_texts['exact_length'];
                            $val_error = preg_replace("/:attribute/", $input_name, $val_error);
                            $val_error = preg_replace("/:params\(0\)/", $matches[1], $val_error);
                            $errors[] = $val_error;
                        }
                    }
                    // Checking min rule
                    if (preg_match("/min\((\d+)\)$/", $rule, $matches)) {
                        if (!self::min($text, $matches[1])) {
                            $val_error = $validation_lang_texts['min'];
                            $val_error = preg_replace("/:attribute/", $input_name, $val_error);
                            $val_error = preg_replace("/:params\(0\)/", $matches[1], $val_error);
                            $errors[] = $val_error;
                        }
                    }
                    // Checking max rule
                    if (preg_match("/max\((\d+)\)$/", $rule, $matches)) {
                        if (!self::max($text, $matches[1])) {
                            $val_error = $validation_lang_texts['max'];
                            $val_error = preg_replace("/:attribute/", $input_name, $val_error);
                            $val_error = preg_replace("/:params\(0\)/", $matches[1], $val_error);
                            $errors[] = $val_error;
                        }
                    }

                    // Checking birth date
                    if (preg_match("/birth_date/", $rule)) {
                        if (!self::birth_date($text)) {
                            $errors[] = preg_replace("/:attribute/", $input_name, $validation_lang_texts['birth_date']);
                        }
                    }
                    // Checking birth month
                    if (preg_match("/birth_month/", $rule)) {
                        if (!self::birth_month($text)) {
                            $errors[] = preg_replace("/:attribute/", $input_name, $validation_lang_texts['birth_month']);
                        }
                    }
                    // Checking birth year
                    if (preg_match("/birth_year/", $rule)) {
                        if (!self::birth_year($text)) {
                            $errors[] = preg_replace("/:attribute/", $input_name, $validation_lang_texts['birth_year']);
                        }
                    }
                }
			}
		}

		return new static($errors, $naming);
	}

	public static function required($text){
		if(!empty($text)) return true;
	}

	public static function amount($text){
        return preg_match("/^-?[0-9]+(?:\.[0-9]{1,2})?$/", $text);
	}

	public static function integer($text){
		if(ctype_digit(strval($text)))return true;
	}
	public static function positive($text){
		if($text>=0)return true;
	}
	public static function noZero($text){
		if($text!=0)return true;
	}
	public static function phone($text){
		if(strlen($text)==10 && ctype_digit(strval($text)))return true;
	}
	public static function fullPhone($text){
		if(strlen($text)>=10 && strlen($text)<=14 && ctype_digit(strval($text)))return true;
	}

	public static function prefix($text){
		$valid_prefixes = Sms::getOperatorPrefixes();
		if(in_array($text, $valid_prefixes))return true;
	}

	public static function email($text){
		if (filter_var($text, FILTER_VALIDATE_EMAIL)) return true;
	}

	public static function min_length($text, $length){
		if(strlen($text)>=$length) return true;
	}

	public static function max_length($text, $length){
		if(strlen($text)<=$length) return true;
	}

	public static function exact_length($text, $length){
		if(strlen($text)==$length) return true;
	}
    public static function birth_day($text){
        if (in_array($text, Date::getDays())) return true;
    }
    public static function birth_month($text){
        if (in_array($text, Date::getMonthsInt())) return true;
    }
    public static function birth_year($text){
        if (in_array($text, Date::getYears())) return true;
    }
    public static function min($text, $number){
        if($text>=$number) return true;
    }
    public static function max($text, $number){
        if(intval($text)<=$number) return true;
    }

}
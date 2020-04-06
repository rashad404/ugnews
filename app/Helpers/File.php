<?php

namespace Helpers;


class File
{
    public static function create($file, $text)
    {
        $error = true;
        try {
            $fp = fopen($file, "w");
            if ( !$fp ) {
                throw new Exception('File open failed.');
            }
        } catch ( Exception $e ) {
            $error = $e->getMessage();
        }

        fwrite($fp, $text);
        fclose($fp);

        return $error;
    }

    public static function recurse_copy($path,$newPath) {
        $dir = opendir($path);
        mkdir($newPath, 0777, true);
        chmod($newPath, 0777);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($path . '/' . $file) ) {
                    self::recurse_copy($path . '/' . $file,$newPath . '/' . $file);
                }
                else {
                    copy($path . '/' . $file,$newPath . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    public static function makeDir($path)
    {
        if(is_dir($path))
            return true;
        mkdir($path, 0777, true);
        if(is_dir($path)) {
            chmod($path, 0777);
            return true;
        }

        return false;
    }

    public static function rmDir($directory)
    {
        foreach(glob("{$directory}/*") as $file)
        {
            if(is_dir($file)) {
                self::rmDir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($directory);
    }
}

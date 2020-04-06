<?php
namespace Helpers;

class Mail
{

    public static function sendMail($from, $to, $title, $text)
    {
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= "From: $from" . "\r\n";

        $send = mail($to, $title, $text, $headers);
        if ($send) {
            return true;
        } else {
            return false;
        }
    }




    public static function sendMail_attachment($from, $to, $title, $text, $files) {
        // boundary
        $semi_rand = md5(time());
        $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
        $headers = "From: $from";
        // headers for attachment
        $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";

        // multipart boundary
        $message = "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" .
            "Content-Type: text/html; charset=\"utf-8\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $text . "\n\n";
        $message .= "--{$mime_boundary}\n";

        // preparing attachments
        for($x=0;$x<count($files);$x++){
            $file = fopen($files[$x],"rb");
            $data = fread($file,filesize($files[$x]));
            fclose($file);
            $data = chunk_split(base64_encode($data));
            $message .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"$files[$x]\"\n" .
                "Content-Disposition: attachment;\n" . " filename=\"$files[$x]\"\n" .
                "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
            $message .= "--{$mime_boundary}\n";
        }

        if (mail($to, $title, $message, $headers)) {
            return true;
        } else {
            return false;
        }
    }

}
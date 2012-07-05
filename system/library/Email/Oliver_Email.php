<?php

require_once LIBRARY_DIR . 'functions.php';
require_once CONFIG_DIR . 'mail.php';

class Oliver_email {
    
    private $logfile = 'log.txt';
    

    function send_text_mail($sender = null , $receiver = null , $subject = null , $param = null, $layout = "default") {
        //check emailadresses
        if(is_email($sender) && is_email($receiver)) {
            
            if(!isset($subject))
                $subject = 'no Subject';
                            
            $message = $this->_check_msg($sender, $receiver , $param , $layout , 'text');
            
            $mid = md5(TIME());
            
            if(@mail($receiver, $subject, $message, $this->_mailheader($sender, $receiver , $mid))) {
                $this->_write_log($sender, $receiver, $mid, $subject);
            }
        }
    }
    
    function send_html_mail($sender = null , $receiver = null , $subject = null , $param = null, $layout = "default") {
        if(is_email($sender) && is_email($receiver)) {
            
            if(!isset($subject))
                $subject = 'no Subject in html';
            
            $message = $this->_check_msg($sender, $receiver, $param , $layout , 'html');
            
            $mid = md5(TIME());
            
            if(@mail($receiver, $subject, $message, $this->_mailheader($sender, $receiver , $mid , 'html'))) {
                $this->_write_log($sender, $receiver, $mid, $subject);
            }
            
        }
    }
    
    private function _mailheader ($sender , $receiver , $mid , $type = "text") {
        switch ($type) {
            case 'html':
                $type = 'text/html';
                $code = 8;
                break;
            default:
                $type = 'text/plain';
                $code = 7;
                break;
        }
        
        $mailheader  = "From: <" .$sender. ">\r\n";
        $mailheader .= "Reply-To: " .$receiver. "<" .$receiver. ">\r\n";
        $mailheader .= "Return-Path: noreply@" .$_SERVER['SERVER_NAME']. "\r\n";
        $mailheader .= "MIME-Version: 1.0\r\n";
        $mailheader .= "Content-Type: ".$type."; charset=UTF-8\r\n";
        $mailheader .= "Content-Transfer-Encoding: ".$code."bit\r\n";
        $mailheader .= "Message-ID: <" .$mid. " noreply." .$sender. ">\r\n";
        $mailheader .= "X-Mailer: PHP v" .phpversion(). "\r\n\r\n";
        
        return $mailheader;
    }
    
    private function _write_log($sender , $receiver , $mid , $subject) {
        if(!is_writeable(EMAIL_DIR . $this->logfile))
                die('change Permission of: ' . $this->logfile);
        
        $string = $mid . ' ' . $sender . ' ' . $receiver . ' ' . $subject;
        
        $handle = fopen(EMAIL_DIR . $this->logfile, 'a+');
        fwrite($handle, $string . "\r\n");
        fclose($handle);
    }
    
    private function _check_msg($sender , $receiver , $param =null , $layout = "default", $type = "text") {
        
        switch ($type) {
            case 'html':
                $ext = '.html';
                break;
            default:
                $ext = '.txt';
                break;
        }
        
        if(file_exists(LIBRARY_DIR . "Email/tpl/" . $layout . $ext)) {
            $message = file_get_contents(LIBRARY_DIR . "Email/tpl/" . $layout . $ext);
        }  else {
            $message = file_get_contents(LIBRARY_DIR . "Email/tpl/default" . $ext);
        }

        if(isset($param) && is_array($param)) {
            foreach ($param as $key => $value) {
                $message = str_replace("{".$key."}", $value, $message);
            }
        }else{
            $message = str_replace("{from}", $sender, $message);
            $message = str_replace("{to}", $receiver, $message);
            $message = str_replace("{message}", DEFAULT_TXT, $message);
        }
        
        return $message;
    }

}

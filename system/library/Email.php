<?php
/**
 * Mail class V0.1beta
 * create by onkelzfreak1988
 * for RAINCMS
 * www.rainframework.com 
 */

class Email {
    
    private static  $email_class_dir = "Email/",
                    $email_class_file = "Oliver_Email.php",
                    $email_class = "Oliver_email";
    
            private $email_obj;

    function __construct() {
        require_once self::$email_class_dir . self::$email_class_file ;
        $this->email_obj = new self::$email_class;
    }
    
    /**
     *
     * @param string $sender 
     * @param string $receiver
     * @param string $subject -> Email subject use "no Subject" if empty
     * @param array $param -> array("placeholder"=>"value") -> use placeholder of your tpl
     * @param string $layout -> create a layout as .txt like default.txt in Email/tpl/
     * 
     * @example $this->email->send_text_mail($sender , $receiver , $subject , $param , $layout)
     * @example $this->email->send_text_mail($sender , $receiver)
     * @example $this->email->send_text_mail($sender , $receiver , 'test html mail' , array("from"=>$sender) , "mail/testmail"); 
     * @desc send email load the .txt file
     *      -> looking Email/tpl/default.txt for an example
     */
    function send_text_mail($sender = null , $receiver = null , $subject = null , $param = null , $layout = "default") {
        $this->email_obj->send_text_mail($sender , $receiver , $subject , $param , $layout);
    }
    
    
    /**
     *
     * @param string $sender
     * @param string $receiver
     * @param string $subject
     * @param array $param
     * @param string $layout 
     * 
     * @example $this->email->send_html_mail($sender , $receiver , $subject , $param , $layout)
     * @example $this->email->send_html_mail($sender , $receiver)
     * @example $this->email->send_html_mail($sender , $receiver , 'test html mail' , array("from"=>$sender) , "mail/testmail"); 
     * @desc send email load the .html file
     *      -> looking Email/tpl/default.html for an example
     */
    function send_html_mail($sender = null , $receiver = null , $subject = null , $param = null , $layout = "default") {
        $this->email_obj->send_html_mail($sender , $receiver , $subject , $param , $layout);
    }

}
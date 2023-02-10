<?php
namespace Atabasch;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Atabasch\Config;


class Mailer{

    public null|PHPMailer $mail = null;
    public string $host     = '';
    public string $username = '';
    public string $password = '';
    public int $port        = 465;
    public string $encrypt  = 'ssl';

    /**
     * Ekstra ayarlar için $this->mail kullan
     *     $this->mail->addReplyTo('info@example.com', 'Information');
     *     $this->mail->addCC('cc@example.com');
     *     $this->mail->addBCC('bcc@example.com');
     */

    public function __construct(){
        $this->host     = Config::get('mail_host');
        $this->username = Config::get('mail_username');
        $this->password = Config::get('mail_password');
        $this->port     = Config::get('mail_port');
        $this->encrypt  = Config::get('mail_encryption');
        $this->initMailer();
    }

    private function initMailer(){
        $this->mail = new PHPMailer(true);
        //Server settings
        $this->mail->SMTPDebug = 0;                      
        $this->mail->isSMTP();
        $this->mail->Host       = $this->host;
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = $this->username;
        $this->mail->Password   = $this->password;
        $this->mail->SMTPSecure = $this->encrypt;
        $this->mail->Port       = $this->port;
        
        if(Config::get('mail_reply', false)){
            $this->mail->addReplyTo(Config::get('mail_reply'), Config::get('mail_sender_name', '')); 
        }

        $this->mail->setLanguage('tr');
        $this->from(Config::get('mail_sender'), Config::get('mail_sender_name', ''));
    }

    /**
     * E-postaya ek dosya ekler.
     * $path: Eklenecek dosyanın yolu.
     * $name: Eklenen dosya için bir isim.
     * @throws Exception
     */
    public function attachment($path=null, $name=''){
        if($path){
            $this->mail->addAttachment($path, $name);
        }
        return $this;
    }

    public function from($email, $name=''){
        $this->mail->setFrom($email, $name);
        return $this;
    }

    public function to($email, $name=''){
        $this->mail->addAddress($email, $name);
        return $this;
    }

    public function send($subject='', $content='', $to=null){
        if($to){
            $to = is_array($to)? $to : [$to];
            $this->to($to[0], $to[1] ?? '');
        }

        try {
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body    = $content;
            // $this->mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            return $this->getResult($this->mail->send());
        } catch (Exception $e) {
            $message = "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
            return $this->getResult(false, $message);
            }
    }

    public function getResult($status=true, $message=null){
        return (object)[
            'status' => $status,
            'message' => $message
        ];
    }

}
?>

<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
// require 'vendor/autoload.php';

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

//Instantiation and passing `true` enables exceptions
function send_mail($send_to_email, $send_to_name, $subject, $content, $option = array())
{
    global $config;
    $config_email = $config['email'];
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = 0;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host = $config_email['smtp_host'];                     //Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   //Enable SMTP authentication
        $mail->Username = $config_email['smtp_user'];                     //SMTP username
        $mail->Password = $config_email['smtp_pass'];                               //SMTP password
        $mail->SMTPSecure = $config_email['smtp_secure'];         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port = $config_email['smtp_port'];                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
        $mail->CharSet = $config_email['charset'];
        //Recipients
        $mail->setFrom($config_email['smtp_user'], $config_email['smtp_fullname']); // được gửi từ đâu
        $mail->addAddress($send_to_email, $send_to_name);     //Add a recipient
        // $mail->addAddress('ellen@example.com');               //Name is optional
        $mail->addReplyTo($config_email['smtp_user'], $config_email['smtp_fullname']); // email người dùng phải hồi
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');

        //Attachments
        // $mail->addAttachment('post_1.png');         //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
        if (!empty($option['file_tmp_name'])) {
            $mail->addAttachment($option['file_tmp_name'], $option['file_name']);    // Add attachments
        }
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $content;
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';


        $mail->send();
        return true;
    } catch (Exception $e) {
        return "Email gửi bị lỗi. Mailer Error: {$mail->ErrorInfo}";
    }
}
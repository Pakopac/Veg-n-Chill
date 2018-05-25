<?php

namespace Model;

use Cool\DBManager;
use Cool\BaseController;
use PHPMailer\PHPMailer\PHPMailer;

class MailManager
{
    public function contact($mail, $object, $message)
    {
        $datas = ["Contact: ".$mail, "Message: ".$message];
        $all = join(",<br>", $datas);
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true,
            ),
        );
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = '465';
        $mail->isHTML();
        $mail->Username = "contact@vegnchill.com";
        $mail->Password = "<&ur8RdeY";
        $mail->setFrom('contact@vegnchill.com');
        $mail->Subject = $object;
        $mail->Body = $all;
        $mail->addAddress("contact@vegnchill.com");
        $result = $mail->send();
        if ($result == true) {
            $arr = [
                'success' => "ok",
                'message' => "Mail successfully sent"
            ];
            return $arr;
        } else {
            $arr = [
                'success' => "failed",
                'message' => "Your mail was not sent"
            ];
            return $arr;
        }
    }
}
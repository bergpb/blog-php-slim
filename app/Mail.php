<?php

namespace App;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;


class Mail
{
    private $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function send(array $to, string $template, string $subject, array $payload)
    {
        $mail = new PHPMailer(true);

        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = getenv('MAIL_HOST');
            $mail->SMTPAuth = getenv('MAIL_SMTPAUTH');
            $mail->Username = getenv('MAIL_USERNAME');
            $mail->Password = getenv('MAIL_PASSWORD');
            $mail->SMTPSecure = getenv('MAIL_SMTPSECURE');
            $mail->Port = getenv('MAIL_PORT');

            $mail->CharSet = 'utf-8';
            $mail->setFrom($to['email'], $to['name']);
            $mail->addAddress($to['email'], $to['name']);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $this->container->view->render(
                $this->container->response,
                'mails/' . $template,
                $payload
            );

            $mail->send();
        } catch (PHPMailerException $e) {
            echo 'Houve um erro ao enviar o email.';
        }
    }
}
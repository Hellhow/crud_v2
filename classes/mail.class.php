<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/php-sql_poste_Vincent_v2/variable.php';
// require_once $_SERVER['DOCUMENT_ROOT'] . '/sirjacques.vincent/php-sql_poste_Vincent_v2/variable.php';
class Mail
{
    private const FROM_ADDRESS = "sirjacques.vinc@gmail.com";
    private const REPLY_TO_ADDRESS = "sirjacques.vinc@gmail.com";
    private const SUBJECT = "Inscription au blog exo";
    private const CHARSET = "UTF-8";

    private $headers;
    private $logo;

    public function __construct()
    {
        $this->headers = "From: " . self::FROM_ADDRESS . " \r\n";
        $this->headers = "Reply-To: " . self::REPLY_TO_ADDRESS . " \r\n";
        $this->headers = "MIME-Version: 1.0\r\n";
        $this->headers = "Content-type: text/html; charset=" . self::CHARSET . "\r\n";
        $this->logo = "";
    }
    public function Post($mail, $user, $id, $token)
    {
        $message = '<html>
        <head><title></title></head>
        <body>
        Bonjour, M./Mme. ' . $user . '<br><br>
        
        Il ne vous reste plus qu\'à finaliser votre inscription.<br>
        Cliquez sur le lien si-dessous : <br>
        <a href="' . SITE_ROOT_A . '/page/log/confirmation.php?id=' . $id . '&t=' . $token . '">Finalisez ici.</a><br><br>
        
        Cordialement, tout l\'équipe de développement.
        </body>
        </html>';
        return mb_send_mail($mail, self::SUBJECT, $message, $this->headers);
    }
}

<?php
/**
* SendMailSmtpClass
*
* Класс для отправки писем через SMTP с авторизацией
*
* @author Ipatov Evgeniy <admin@ipatov-soft.ru>
* @version 1.0
*/
class SendMailSmtpClass {

  /**
  *
  * @var string $smtp_username - логин
  * @var string $smtp_password - пароль
  * @var string $smtp_host - хост
  * @var string $smtp_from - от кого
  * @var integer $smtp_port - порт
  * @var string $smtp_charset - кодировка
  *
  */
  public $smtp_username;
  public $smtp_password;
  public $smtp_host;
  public $smtp_from;
  public $smtp_port;
  public $smtp_charset;

  public function __construct($smtp_username, $smtp_password, $smtp_host, $smtp_from, $smtp_port = 25, $smtp_charset = "utf-8") {
  $this->smtp_username = $smtp_username;
  $this->smtp_password = $smtp_password;
  $this->smtp_host = $smtp_host;
  $this->smtp_from = $smtp_from;
  $this->smtp_port = $smtp_port;
  $this->smtp_charset = $smtp_charset;
  }

  /**
  * Отправка письма
  *
  * @param string $mailTo - получатель письма
  * @param string $subject - тема письма
  * @param string $message - тело письма
  * @param string $headers - заголовки письма
  *
  * @return bool|string В случаи отправки вернет true, иначе текст ошибки *
  */
  function send($mailTo, $subject, $message, $headers) {
  $contentMail = "Date: " . date("D, d M Y H:i:s") . " UT\r\n";
  $contentMail .= 'Subject: =?' . $this->smtp_charset . '?B?' . base64_encode($subject) . "=?=\r\n";
  $contentMail .= $headers . "\r\n";
  $contentMail .= $message . "\r\n";

  try {
  if(!$socket = @fsockopen($this->smtp_host, $this->smtp_port, $errorNumber, $errorDescription, 30)){
  throw new Exception($errorNumber.".".$errorDescription);
  }
  if (!$this->_parseServer($socket, "220")){
  throw new Exception('Connection error');
  }

  fputs($socket, "HELO " . $this->smtp_host . "\r\n");
  if (!$this->_parseServer($socket, "250")) {
  fclose($socket);
  throw new Exception('Error of command sending: HELO');
  }

  fputs($socket, "AUTH LOGIN\r\n");
  if (!$this->_parseServer($socket, "334")) {
  fclose($socket);
  throw new Exception('Autorization error');
  }

  fputs($socket, base64_encode($this->smtp_username) . "\r\n");
  if (!$this->_parseServer($socket, "334")) {
  fclose($socket);
  throw new Exception('Autorization error');
  }

  fputs($socket, base64_encode($this->smtp_password) . "\r\n");
  if (!$this->_parseServer($socket, "235")) {
  fclose($socket);
  throw new Exception('Autorization error');
  }

  fputs($socket, "MAIL FROM: ".$this->smtp_username."\r\n");
  if (!$this->_parseServer($socket, "250")) {
  fclose($socket);
  throw new Exception('Error of command sending: MAIL FROM');
  }

  fputs($socket, "RCPT TO: " . $mailTo . "\r\n");
  if (!$this->_parseServer($socket, "250")) {
  fclose($socket);
  throw new Exception('Error of command sending: RCPT TO');
  }

  fputs($socket, "DATA\r\n");
  if (!$this->_parseServer($socket, "354")) {
  fclose($socket);
  throw new Exception('Error of command sending: DATA');
  }

  fputs($socket, $contentMail."\r\n.\r\n");
  if (!$this->_parseServer($socket, "250")) {
  fclose($socket);
  throw new Exception("E-mail didn't sent");
  }

  fputs($socket, "QUIT\r\n");
  fclose($socket);
  } catch (Exception $e) {
  return $e->getMessage();
  }
  return true;
  }

  private function _parseServer($socket, $response) {
  while (@substr($responseServer, 3, 1) != ' ') {
  if (!($responseServer = fgets($socket, 256))) {
  return false;
  }
  }
  if (!(substr($responseServer, 0, 3) == $response)) {
  return false;
  }
  return true;

  }
}




$post = (!empty($_POST)) ? true : false;

if($post) {
  $user_email = trim($_POST['email']);
  $user_name = htmlspecialchars($_POST['name']);
  $user_email = htmlspecialchars($_POST['email']);
  $user_message = htmlspecialchars($_POST['message']);
  $user_tel = htmlspecialchars($_POST["tel"]);
  $error = '';

  if(!$user_name) {
    $error .= 'Пожалуйста введите ваше имя<br />';
  }

  // Проверка телефона
  function ValidateTel($valueTel) {
    $regexTel = "/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/";
    if($valueTel == "") {
      return false;
    } else {
      $string = preg_replace($regexTel, "", $valueTel);
    }
    return empty($string) ? true : false;
  }
  if(!$user_email) {
    $error .= "Пожалуйста введите email<br />";
  }
  if($user_email && !ValidateTel($user_email)) {
    $error .= "Введите правильный email<br />";
  }


  if ((!$error) && (!$user_message || strlen($user_message) < 1)) {
    $error .= "Введите ваше сообщение<br />";
  }
  if(!$error) {

    $name_tema = "=?utf-8?b?". base64_encode($user_name) ."?=";

    $subject ="Новая заявка с сайта krimretrit.ru";
    $subject1 = "=?utf-8?b?". base64_encode($subject) ."?=";
    /*
    $user_message ="\n\nСообщение: ".$user_message."\n\nИмя: " .$user_name."\n\nТелефон: ".$user_tel."\n\n";
    */
    $message1 ="\n\nИмя: ".$user_name."\n\nТелефон: " .$user_tel."\n\nE-mail: " .$user_email."\n\nСообщение: ".$user_message."\n\n";

    $header = "Content-Type: text/plain; charset=utf-8\n";
    $header .= "From: Новая заявка krimretrit.ru\n\n";

    //$sendto   = "lchip@mail.ru"; // почта, на которую будет приходить письмо
    $sendto   = "chessmaster7320@gmail.com"; // почта, на которую будет приходить письмо
    $sendfrom = "admin@krimretrit.ru"; // адрес отправителя и логин для авторизации на почте
    $mailpassw = 'nimda5'; //Password to use for SMTP authentication
    $hostname = 'mail.krimretrit.ru'; //Set the hostname of the mail server
    $hostport = 587;

$error .= "<script>alert('". iconv('utf-8', 'windows-1251', $message1)."'); </script>";
    $mailSMTP = new SendMailSmtpClass($sendfrom, $mailpassw, $hostname, 'krimretrit.ru', $hostport); // создаем экземпляр класса
    // $mailSMTP = new SendMailSmtpClass('логин', 'пароль', 'хост', 'имя отправителя', smtp_порт, $smtp_charset = "utf-8");

    $result = $mailSMTP->send($sendto, $subject1, iconv('utf-8', 'windows-1251', $message1), $header); // отправляем письмо
    // $result = $mailSMTP->send('Кому письмо', 'Тема письма', 'Текст письма', 'Заголовки письма');

    if($result === true) {
      echo 'OK';
    } else {
      echo '<div class="notification_error">'.$error.'</div>';
    }
  } else {
    echo '<div class="notification_error">'.$error.'</div>';
  }

  // if($result === true) {
  //   echo "Письмо успешно отправлено";
  // } else{
  //   echo "Письмо не отправлено. Ошибка: " . $result;
  // }

}
?>

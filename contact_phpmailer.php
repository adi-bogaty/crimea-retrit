<?php

$post = (!empty($_POST)) ? true : false;

if($post) {
  $email = trim($_POST['email']);
  $name = htmlspecialchars($_POST['name']);
  $email = htmlspecialchars($_POST['email']);
  $message = htmlspecialchars($_POST['message']);
  $tel = htmlspecialchars($_POST["tel"]);
  $error = '';

  //$sendto   = "lchip@mail.ru"; // почта, на которую будет приходить письмо
  $sendto   = "chessmaster7320@gmail.com"; // почта, на которую будет приходить письмо
  $sendfrom = "admin@krimretrit.ru"; // адрес отправителя и логин для авторизации на почте

  if(!$name) {
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
  if(!$email) {
    $error .= "Пожалуйста введите email<br />";
  }
  if($email && !ValidateTel($email)) {
    $error .= "Введите правильный email<br />";
  }
  if(!$error)

    // (length)
    if(!$message || strlen($message) < 1) {
      $error .= "Введите ваше сообщение<br />";
    }
  if(!$error) {
    $name_tema = "=?utf-8?b?". base64_encode($name) ."?=";

    $subject ="Новая заявка с сайта krimretrit.ru";
    $subject1 = "=?utf-8?b?". base64_encode($subject) ."?=";
    /*
    $message ="\n\nСообщение: ".$message."\n\nИмя: " .$name."\n\nТелефон: ".$tel."\n\n";
    */
    $message1 ="\n\nИмя: ".$name."\n\nТелефон: " .$tel."\n\nE-mail: " .$email."\n\nСообщение: ".$message."\n\n";

    $header = "Content-Type: text/plain; charset=utf-8\n";
    $header .= "From: Новая заявка krimretrit.ru\n\n";
    //$mail = mail("lchip@mail.ru", $subject1, iconv ('utf-8', 'windows-1251', $message1), iconv ('utf-8', 'windows-1251', $header));


    // use PHPMailer\PHPMailer\PHPMailer;
    // use PHPMailer\PHPMailer\SMTP;

    require('phpmailer/PHPMailer.php');
    require('phpmailer/SMTP.php');

    $mail = new PHPMailer();
    //Tell PHPMailer to use SMTP
    $mail->isSMTP();
    //Enable SMTP debugging
    // 0 = off (for production use)
    // 1 = client messages
    // 2 = client and server messages
    $mail->SMTPDebug = 2;
    //Set the hostname of the mail server
    $mail->Host = 'mail.krimretrit.ru';
    // $mail->Host = 's41.hostia.name';
    // use
    // $mail->Host = gethostbyname('smtp.gmail.com');
    // if your network does not support SMTP over IPv6
    //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
    $mail->Port = 587;
    //Set the encryption system to use - ssl (deprecated) or tls
    $mail->SMTPSecure = 'ssl';
    //Whether to use SMTP authentication
    $mail->SMTPAuth = true;
    //Username to use for SMTP authentication - use full email address for gmail
    $mail->Username = $sendfrom;
    //Password to use for SMTP authentication
    $mail->Password = "nimda5";
    //Set who the message is to be sent from
    $mail->setFrom($sendfrom, 'admin@krimretrit.ru');
    $mail->addAddress($sendto, 'Manager');
    $mail->addAddress($email, $name);
    //Set the subject line
    $mail->Subject = $subject1;
    $mail->Body = iconv('utf-8', 'windows-1251', $message1);

    if($mail->Send()) {
      echo 'OK';
    }
  } else {
    echo '<div class="notification_error">'.$error.'</div>';
  }

}
?>

<?php
/**
 * 
 * Author:          Fussraider
 * Site:            http://fussraider.ru
 * 
 * Description:     Пример использования скрипта, для отправки сообщения используя SMTP.
 * 
**/

error_reporting(E_ALL);		
require_once './sender.php';
$sender = new sender;

if(!empty($_GET['send']))
{
	if(!empty($_GET['text']) && !empty($_GET['to']) && !empty($_GET['to_name']))
	{
		$message_text = $_GET['text'];

		$message_data = array(
								'to'		=> $_GET['to'],
								'to_name' 	=> $_GET['to_name'],
								'title'		=> $sender->mail_content['title'],
								'text'		=> $message_text,
								'alt_text'	=> strip_tags($message_text)
							);
		$mailSend = $sender->sendMail($sender->smtp_data, $message_data);
		if($mailSend == 0)
		{
			echo "Отправка удалась!";
		}
		else
		{
			echo "Ошибка при отправке :(";
		}
	}
	else
	{
		die("не все данные заполнены");
	}
}
else
{
	echo '
		<form action="index.php" method="get">
		Кому:<br /> 
			<input type="text" name="to_name" /><br />
		Адрес почты:<br /> 
			<input type="email" name="to" /><br /><br />
		<textarea rows="20" cols="100" name="text">Текст сообщения</textarea><br /><br />
		<button type="submit" name="send" value="true">Отправить!</button>
		</form>
	';
}



?>
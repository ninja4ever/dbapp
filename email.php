<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href='https://fonts.googleapis.com/css?family=Lato:400,700,300&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
<?php 
function load_files($dir = 'pdf'){

    $result = array();
    $files = scandir($dir);

    foreach($files as $f){
        if(!in_array($f, array('.','..')) ){
            $result[] = $f;
        }
    }

    return $result;
}
require("PHPMailer-master/PHPMailerAutoload.php");
require('email_config.php');
$mail = new PHPMailer();
 

$mail->isSMTP();


$mail->Host = $serwer;
$mail->Port = $port;
$mail->SMTPSecure = $szyfr; 
$mail->SMTPKeepAlive = true;
$mail->SMTPAuth = true;
$mail->Username = $user;
$mail->Password = $pass;

$mail->SetLanguage("pl", "PHPMailer_5.2.4/language/");
$mail->CharSet = "UTF-8";
$mail->ContentType = "text/html";
$mail->isHTML(true);  
$mail->setFrom($email, $nazwa);
$mail->addReplyTo($email, 'Information');
$mail->Subject = 'Raport';
$mail->Body = $message;
$s = load_files();
foreach($s as $f){
$mail->addAttachment('pdf/'.$f, $f);
}
$mail->AddAddress($addres);
?>
    <div class="container">
       <div class="row clearfix m_t_b_15p">
          <div class="item-33p">
          <a href="index" class="return_link">Powrót do strony głównej</a>
              </div>
          </div>
        <div class="row">
        <h1 class="main_title">
    <?php
    if ($mail->send()){
        echo "E-mail został wysłany <br>";
    }
    else{
        echo "E-mail nie mógł zostać wysłany, przyczyna :" . $mail->ErrorInfo;
    }
    $mail->SmtpClose();
    ?>
            </h1>
        </div>
    </div>  
</body>
</html>

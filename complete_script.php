<?php
include_once 'config.php';
$data->insert_data();
$s = $data->count_daily_transactions();
$s1 = $data->count_unique_users();
$s2 = $data->count_users_mail_domain();
$s3 = $data->count_users_transactions();
$s4 = $data->count_users_transactions_gt3();
$s5 = $data->count_seven_days_transactions();
$s6 = $data->seven_days_transactions();
require('fpdf181/tfpdf.php');

class PDF extends tFPDF
{

 
//Page footer
function Footer()
{
    //Position at 1.5 cm from bottom
    $this->SetY(-15);
    //Arial italic 8
    $this->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
    $this->SetFont('DejaVu','',8);
    //Page number
    $this->Cell(0,10,'Strona '.$this->PageNo().'/{nb}',0,0,'C');
}

function FancyTable($header, $data, $main_head, $sub_head = '')
{
	
	
	$this->SetTextColor(134, 183, 186 );
	
    $this->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
    $this->SetFont('DejaVu','',12);
	$this->Cell(190,7,$main_head,0,0,'C',false);
	$this->Ln();
    
    if($sub_head !== ''){
        $this->Cell(190,7,$sub_head,0,0,'C',false);
        $this->Ln();
    }
    // Colors, line width and bold font
    $this->SetFillColor(255,0,0);
    $this->SetTextColor(250, 184, 127 );
    $this->SetDrawColor(238, 238, 238 );
	$this->SetLineWidth(0.1);
    $this->SetLineWidth(.3);
   
    // Header
    $w = array(95, 95);
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],7,$header[$i],'B',0,'C',false);
    $this->Ln();
    // Color and font restoration
    $this->SetFillColor(134, 183, 186 );
    $this->SetTextColor(0);
    $this->SetFont('');
    // Data
    $fill = false;
    foreach($data as $row)
    {
        $this->Cell($w[0],6,$row[0],0,0,'C',$fill);
        $this->Cell($w[1],6,$row[1],0,0,'C',$fill);
     
        $this->Ln();
        $fill = !$fill;
    }
    // Closing line
    $this->Cell(array_sum($w),0,'','T');
}
}

//Instanciation of inherited class
$pdf=new PDF();
$header = array('DATA', 'LICZBA TRANSAKCJI');
$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);

$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('DejaVu','',12);
$pdf->FancyTable($header,$s, 'DZIENNE TRANSAKCJE');
unset($s);

$pdf->Output('pdf/count_daily_transactions.pdf', 'F');

$pdf=new PDF();
$header = array('DATA', 'UNIKALNI UŻYTKOWNICY');
$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);

$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('DejaVu','',12);
$pdf->FancyTable($header,$s1, 'LICZBA UNIKALNYCH UŻYTKOWNIKÓW');
unset($s1);

$pdf->Output('pdf/count_unique_users.pdf', 'F');

$pdf=new PDF();
$header = array('DOMENA', 'LICZBA UŻYTKOWNIKÓW');
$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);

$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('DejaVu','',12);
$pdf->FancyTable($header,$s2, 'DOMENY MAILOWE UŻYTKOWNIKÓW');
unset($s2);

$pdf->Output('pdf/count_users_mail_domain.pdf', 'F');

$pdf=new PDF();
$header = array('IMIĘ I NAZWISKO', 'LICZBA TRANSAKCJI');
$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);

$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('DejaVu','',12);
$pdf->FancyTable($header,$s3, 'TRANSAKCJE UŻYTKOWNIKÓW');
unset($s3);

$pdf->Output('pdf/count_users_transactions.pdf', 'F');

$pdf=new PDF();
$header = array('DATA', 'LICZBA TRANSAKCJI');
$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);

$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('DejaVu','',12);
$pdf->FancyTable($header,$s4, 'DZIENNE TRANSAKCJE');
unset($s4);

$pdf->Output('pdf/count_users_transactions_gt3.pdf', 'F');


$sigma = 0;
$temp = 0;
$n = sizeof($s6);
foreach($s5 as $row){
   foreach($s6 as $row1){
       $t = ($row1['amount'] - $row['avg_amount']);
       $temp = $temp + pow($t,2); 
   }
}

unset($s6);
$sigma = sqrt($temp / $n);
$pdf=new PDF();
$header = array('DZIEŃ', 'ŚREDNIA WARTOŚĆ TRANSAKCJI');
$pdf->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);

$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('DejaVu','',12);
$pdf->FancyTable($header,$s5, 'TRANSAKCJE Z OSTATNICH 7 DNI', 'ODCHYLENIE STANDARDOWE: '.round($sigma, 5));
unset($s5);

$pdf->Output('pdf/count_seven_days_transactions.pdf', 'F');

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

if ($mail->send()){
    echo "E-mail został wysłany <br>";
}
else{
    echo "E-mail nie mógł zostać wysłany, przyczyna :" . $mail->ErrorInfo;
}
$mail->SmtpClose();
?>
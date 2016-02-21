<?php

include_once 'config.php';
$s = $data->count_seven_days_transactions();
$s1 = $data->seven_days_transactions();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Document</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href='https://fonts.googleapis.com/css?family=Lato:400,700,300&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/style.css" />
</head>
<body>
   <section>
    <div class="container">
      <div class="row clearfix m_t_b_15p">
      <div class="item-33p">
      <a href="index" class="return_link">Powrót do strony głównej</a>
          </div>
      </div>
       <div class="row">
           <h1 class="main_title">transakcje z ostatnich 7 dni</h1>
       </div>
    <?php
    $sigma = 0;
    $temp = 0;
    $n = sizeof($s1);
    foreach($s as $row){
       foreach($s1 as $row1){
           $t = ($row1['amount'] - $row['avg_amount']);
           $temp = $temp + pow($t,2); 
       }
    }
    
    unset($s1);
    $sigma = sqrt($temp / $n);
    
    ?>
       <div class="row">
           <h1 class="main_title">odchylenie standarowe: <?php echo round($sigma, 5); ?></h1>
       </div>
        <div class="row clearfix">
            <div class="item-100p">
                <table class="main_table">
                    <thead>
                        <tr>
                            <th>dzień</th>
                            <th>średnia wartość transakcji</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($s as $row){
                            ?>
                            <tr>
                            <?php
                            echo '<td>'.$row['date'].'</td><td>'.$row['avg_amount'].'</td>';
                            
                            ?>
                            </tr>
                            <?php
                        }
                        unset($s);
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </section>
   
</body>
</html>
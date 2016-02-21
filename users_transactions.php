<?php

include_once 'config.php';

$s = $data->count_users_transactions();

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
           <h1 class="main_title">transakcje użytkowników</h1>
       </div>
        <div class="row clearfix">
            <div class="item-100p">
                <table class="main_table">
                    <thead>
                        <tr>
                            <th>Imię i nazwisko</th>
                            <th>liczba transakcji</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($s as $row){
                            ?>
                            <tr>
                            <?php
                            echo '<td>'.$row['name'].'</td><td>'.$row['count_user_t'].'</td>';
                            
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
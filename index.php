<?php ?>
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
            <div class="row">
                <h1 class="main_title">Raporty</h1>
            </div>
            <div class="row clearfix">
                <div class="item-33p">
                    <a class="link" href="daily-transactions"><span>dzienne transakcje</span></a>
                </div>
                <div class="item-33p">
                    <a class="link" href="daily-unique-users"><span>dzienni unikatowi użytkownicy</span></a>
                </div>
                <div class="item-33p">
                    <a href="users-domains" class="link"><span>domeny mailowe użytkowników</span></a>
                </div>
                <div class="item-33p">
                    <a href="users-transactions" class="link"><span>transakcje użytkowników</span></a>
                </div>
                <div class="item-33p">
                    <a href="users-transactions-gt3" class="link"><span>użytkownikcy z > 3 transakcjami</span></a>
                </div>
                <div class="item-33p">
                    <a href="last-seven-days" class="link"><span>ostatnie 7 dni (średnia wartość transakcji i odchylenie standardowe)</span></a>
                </div>
            </div>
            <div class="row clearfix">
               <div class="item-100p">
                <a href="create-data-to-send" class="link"><span>Tworzenie raportu do wysłania emailem</span></a>
                </div>
            </div>
            <div class="row clearfix">
               <div class="item-100p">
                <a href="insert-data" class="link"><span>Import danych do bazy</span></a>
                </div>
            </div>
        </div>
    </section>
</body>
</html>
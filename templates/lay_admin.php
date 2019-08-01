<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Главная страница
</title>
    <link rel="stylesheet" href="../css/vendors.min.css">
    <link rel="stylesheet" href="../css/main.min.css">
  </head>
  <body>
    <div class="wrapper">
      <div class="maincontent">
          <?=$content_users; ?><br><br>
          <?=$content_orders; ?><br>
      </div>
     <script src="js/vendors.min.js"></script>
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>
    <script src="js/main.min.js"></script>
  </body>
</html>
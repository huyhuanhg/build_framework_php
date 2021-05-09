<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Learn Bootstrap</title>
    <link rel="stylesheet" type="text/css" href="assets/bootstrap/css/bootstrap.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js"
            integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="assets/bootstrap/js/bootstrap.js"></script>
</head>
<body>
<div class="container">
    <div class="row">
        <?php
            $this->renderPartial('layouts/nav', ['name'=>'Hoang huy huan']);
        ?>
    </div>
    <div class="row">
        <?php
        $this->renderPartial('layout2/index', ['name'=>'Hoang huy hung']);
        ?>
    </div>
</div>
</body>
</html>
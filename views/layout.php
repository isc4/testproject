<!doctype html>
<html lang="ru">
<head>
    <title><?=$this->e($title)?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="/Assets/style.css">
</head>
<body>
<nav class="nav">
    <div class="container">
        <ul>
            <li><a href="/" class="btn btn-light">Home page</a></li>
            <li><a href="/add" class="btn btn-success">Add users</a></li>
            <li><a href="/statistic" class="btn btn-success">Statistic users</a></li>
        </ul>
    </div>
</nav>

<?=$this->section('content')?>


</body>
</html>
<?php
ini_set('display_errors', 1);
require_once('vendor/j7mbo/twitter-api-php/TwitterAPIExchange.php');
require_once 'TwitterAPI.php';

$twitter = new  TwitterAPI();
$stmt = $twitter->runQuery("SELECT * FROM `hashtag`");
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
//echo '<pre>';
//print_r($data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Twitter API SEARCH</title>
    <!-- Latest compiled and minified CSS -->
    <!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"-->
    <!--          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">-->
    <link rel="stylesheet" href="https://bootswatch.com/spacelab/bootstrap.min.css">
</head>
<body>
<div class="container">
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                        data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">ระบบสนับสนุนการตัดสินใจเลือกเพลง</a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">เมนู
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="../index.php">หน้าหลัก</a></li>
                            <li class="active"><a href="index.php">Search Twitter <span class="sr-only">(current)</span></a></li>
                            <li><a href="sum.php">ข้อมูล Twitter ทั้งหมด<span class="sr-only">(current)</span></a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <br>
    <div class="row">
        <div class="col-sm-12">
            <div class="col-sm-3">
                <div class="list-group">
                    <a href="#" class="list-group-item active">
                        ข้อมูล #Hashtag เพลงฮิต
                    </a>
                    <?php
                    foreach ($data as $res) {
                        echo '<a href="data.php?id=' . $res['ID_hashtag'] . '" class="list-group-item"><span class="badge">' . $res['total'] . '</span>' . $res['Text_hashtag'] . '</a>';
                    }
                    ?>
                </div>
            </div>
            <div class="col-sm-9">
                <div class="panel panel-success" style="margin-top: 15px">
                    <div class="panel-heading ">
                        <h3 class="panel-title">ข้อมูล </h3>
                    </div>
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>#HashTag</th>
                            <th>Text</th>
                            <th>สถานะ</th>
                        </tr>
                        </thead>
                        <?php
                        if (isset($_GET['id'])) {
                            $id = $_GET['id'];
                            $stmt = $twitter->runQuery("SELECT hashtag.ID_hashtag ,text_twitter.IdText,hashtag.Text_hashtag,text_twitter.text,text_twitter.isActive
                                FROM hashtag 
                                INNER JOIN text_twitter
                                ON hashtag.ID_hashtag = text_twitter.ID_hashtag
                                WHERE hashtag.ID_hashtag =:id");
                            $stmt->execute(array(':id' => $id));

                            if ($stmt->rowCount()) {
                                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($data as $value) {
                                    ?>
                                    <tbody>
                                    <tr>
                                        <td><?php echo $value['Text_hashtag'] ?></td>
                                        <td><?php echo $value['text'] ?></td>
                                        <td><?php
                                                if ($value['isActive'] == 0 ){
                                                    echo '<a href="cut_String.php?ID='.$value['IdText'].'" class="btn btn-warning btn-xs">ยังไม่คำนวณ</a>';
                                                }else if ($value['isActive'] == 1) {
                                                    echo '<a href="cut_String.php?ID='.$value['IdText'].'" class="btn btn-success btn-xs">คำนวณ</a>';
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                    </tbody>
                                    <?php
                                }
                            }
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- Scripts -->
<script src="https://bootswatch.com/bower_components/jquery/dist/jquery.min.js"></script>
<script src="https://bootswatch.com/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>
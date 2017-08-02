<?php
require_once 'TwitterAPI.php';


$twitter = new  TwitterAPI();


$stmt = $twitter->runQuery("SELECT hashtag.ID_hashtag,hashtag.Text_hashtag,hashtag.URL,feeling.comein,feeling.love,feeling.secretly_in_love,feeling.lonelorn,feeling.bored,feeling.lovely FROM hashtag INNER JOIN feeling ON hashtag.ID_hashtag = feeling.ID_hashtag");
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt2 = $twitter->runQuery("SELECT hashtag.ID_hashtag,hashtag.Text_hashtag,hashtag.URL,feeling.comein,feeling.love,feeling.secretly_in_love,feeling.lonelorn,feeling.bored,feeling.lovely,opinion.like,opinion.Indifferent FROM hashtag INNER JOIN feeling ON hashtag.ID_hashtag = feeling.ID_hashtag INNER JOIN opinion ON hashtag.ID_hashtag =opinion.ID_hashtag");
$stmt2->execute();
$data2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Twitter API SEARCH</title>
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
                            <li><a href="index.php">Search Twitter <span class="sr-only">(current)</span></a>
                            <li><a href="data.php">ข้อมูล Twitter <span class="sr-only">(current)</span></a>
                            <li class="active"><a href="sum.php">ข้อมูล Twitter ทั้งหมด<span
                                            class="sr-only">(current)</span></a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <br>
    <div class="row">
        <div class="col-sm-12">
            <div class="col-sm-12">
                <div class="panel panel-primary" style="margin-top: 15px">
                    <div class="panel-heading ">
                        <h3 class="panel-title">ข้อมูล Twitter ทั้งหมด</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th></th>
                                <th>#hashtag</th>
                                <th>URL</th>
                                <th>Comein</th>
                                <th>love</th>
                                <th>Secretly in love</th>
                                <th>Lonelorn</th>
                                <th>Bored</th>
                                <th>Lovely</th>
                            </tr>
                            </thead>
                            <?php
                            foreach ($data as $res){
                            ?>
                            <tbody>
                            <tr>
                                <th><?= $res['ID_hashtag'] ?></th>
                                <td><?= $res['Text_hashtag'] ?></td>
                                <td><?= $res['URL'] ?></td>
                                <td><?= $res['comein'] ?></td>
                                <td><?= round(($res['love']*100)/$res['comein']); ?></td>
                                <td><?= round(($res['secretly_in_love']*100)/$res['comein']); ?></td>
                                <td><?= round(($res['lonelorn']*100)/$res['comein']); ?></td>
                                <td><?= round(($res['bored']*100)/$res['comein']);?></td>
                                <td><?= round(($res['lovely']*100)/$res['comein']); ?></td>
                            </tr>
                            </tbody>
                            <?php } ?>
                        </table>
                        <a href="excel.php" class="btn btn-success">EXCEL</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="col-sm-12">
                <div class="panel panel-primary" style="margin-top: 15px">
                    <div class="panel-heading ">
                        <h3 class="panel-title">ข้อมูล Twitter ทั้งหมด</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th></th>
                                <th>#hashtag</th>
                                <th>URL</th>
                                <th>love</th>
                                <th>secretly in love</th>
                                <th>lonelorn</th>
                                <th>bored</th>
                                <th>lovely</th>
                                <th>Comments</th>
                            </tr>
                            </thead>
                            <?php
                            foreach ($data2 as $res2){
                                ?>
                                <tbody>
                                <tr>
                                    <th><?= $res2['ID_hashtag'] ?></th>
                                    <td><?= $res2['Text_hashtag'] ?></td>
                                    <td><?= $res2['URL'] ?></td>
                                    <td><?= round(($res2['love']*100)/$res2['comein']); ?></td>
                                    <td><?= round(($res2['secretly_in_love']*100)/$res2['comein']); ?></td>
                                    <td><?= round(($res2['lonelorn']*100)/$res2['comein']); ?></td>
                                    <td><?= round(($res2['bored']*100)/$res2['comein']);?></td>
                                    <td><?= round(($res2['lovely']*100)/$res2['comein']); ?></td>
                                    <td><?php
                                        $Indifferent = $res2['Indifferent'];
                                        $like = $res2['like'];
                                        if ($like > $Indifferent ){
                                            echo 'LIKE';
                                        }else{
                                            echo 'Normal';
                                        }
                                        ?>
                                    </td>
                                </tr>
                                </tbody>
                            <?php } ?>
                        </table>
                        <a href="excel2.php" class="btn btn-success">EXCEL</a>
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

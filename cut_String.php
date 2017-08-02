<?php
require_once 'TwitterAPI.php';
require_once 'Thsplitlib.php';

$twitter = new  TwitterAPI();
$ths = new  Thsplitlib();
if (isset($_GET['ID'])) {
    $id = $_GET['ID'];
    $stmt = $twitter->runQuery("SELECT hashtag.ID_hashtag ,hashtag.Text_hashtag , text_twitter.ID ,text_twitter.IdText ,text_twitter.text,text_twitter.year ,text_twitter.isActive 
    FROM hashtag INNER JOIN text_twitter 
    ON hashtag.ID_hashtag = text_twitter.ID_hashtag 
    WHERE text_twitter.IdText =:ID");
    $stmt->execute(array(':ID' => $id));
    $data = $stmt->fetch(PDO::FETCH_ASSOC);
//    echo '<pre>';
//    print_r($data);


}
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
                            <li><a href="sum.php">ข้อมูล Twitter ทั้งหมด<span class="sr-only">(current)</span></a>
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
                        <h3 class="panel-title">ข้อมูล Twitter ID : <?= $data['IdText']; ?></h3>
                    </div>
                    <div class="panel-body">
                        <div class="col-sm-12">
                            <div class="col-sm-7">
                                <div class="form-group">
                                    <label for="textArea" control-label">ข้อความ Twitter</label>
                                    <textarea class="form-control" rows="3" id="textArea"
                                              disabled=""><?= $data['text']; ?></textarea>
                                </div>
                            </div>
                            <div class="col-sm-5">
                                <div class="form-group">
                                    <label class="control-label" for="disabledInput">#Hashtag Twitter</label>
                                    <input class="form-control" id="disabledInput" type="text"
                                           value="<?= $data['Text_hashtag']; ?>" disabled="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="panel panel-danger" style="margin-top: 15px">
                    <div class="panel-heading ">
                        <h3 class="panel-title">ข้อมูลอักษระ ตัดตัวพิเศษ</h3>
                    </div>
                    <div class="panel-body">
                        <?php
                        $string = $data['text'];
                        try {
                            if ($res = $twitter->processTwitter($string)) {
                                if ($res == null) {
                                    echo 'fail data';
                                } else {
                                    echo 'TEXT : ' . json_decode($res);
                                    echo '<hr/>';
                                    echo 'success data';
                                }
                            }
                        } catch (PDOException $e) {
                            echo $e->getMessage();
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="panel panel-danger" style="margin-top: 15px">
                    <div class="panel-heading ">
                        <h3 class="panel-title">การประมวลผลอักษระ</h3>
                    </div>
                    <div class="panel-body">
                        <?php
                        $text = trim(json_decode($res));
                        //                        echo $text;
                        //                        echo '<hr/>';
                        try {
                            if ($result = $ths->processText($text)) {
                                if ($result == null) {
                                    echo 'fail data';
                                } else {
                                    $strArr = json_decode($result);
                                    echo 'success data';
                                }
                            }
                        } catch (PDOException $e) {
                            echo $e->getMessage();
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="panel panel-success" style="margin-top: 15px">
                    <div class="panel-heading ">
                        <h3 class="panel-title">ประมาลผลคำ</h3>
                    </div>
                    <div class="panel-body">
                        <div class="col-sm-6">
                            <div class="list-group">
                                <a href="#" class="list-group-item  active">ข้อมูลด้านความคิดเห็น</a>
                                <?php

                                    if ($count1 = $twitter->opinion($strArr)){
                                        if ($count1 == null) {
                                            echo 'fail data';
                                        } else {
                                            $sum1 = json_decode($count1);
//                                            echo '<pre>';
//                                            print_r($sum1);
                                            echo ' <a  class="list-group-item"><span class="badge">'.$sum1->like.'</span>ชอบ</a>';
                                            echo '<a  class="list-group-item"><span class="badge">'.$sum1->Indifferent.'</span>เฉย</a>';
                                            echo '<a  class="list-group-item"><span class="badge">'.$sum1->count.'</span>ผลรามของคำที่หา</a>';
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="list-group">
                                <a href="#" class="list-group-item active">ข้อมูลด้านความรู้สึก</a>
                                <?php
                                if ($count2 = $twitter->feeling($strArr)){
                                    if ($count2 == null) {
                                        echo 'fail data';
                                    } else {
                                        $sum2 = json_decode($count2);
//                                        echo '<pre>';
//                                            print_r($sum2);
                                        echo '<a  class="list-group-item"><span class="badge">'.$sum2->love.'</span>ความรัก</a>';
                                        echo '<a  class="list-group-item"><span class="badge">'.$sum2->secretly_in_love.'</span>แอบรัก</a>';
                                        echo '<a class="list-group-item"><span class="badge">'.$sum2->lonelorn.'</span>อกหัก</a>';
                                        echo '<a  class="list-group-item"><span class="badge">'.$sum2->bored.'</span>เบื่อ</a>';
                                        echo '<a  class="list-group-item"><span class="badge">'.$sum2->lovely.'</span>เหงา</a>';
                                        echo '<a  class="list-group-item"><span class="badge">'.$sum2->count2.'</span>รวม</a>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
<!--                        --><?php
//                        echo '<hr>';
//                        echo '<pre>';
//                        print_r($strArr);
//                        ?>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="panel panel-info" style="margin-top: 15px">
                        <div class="panel-heading ">
                            <h3 class="panel-title">ข้อมูล</h3>
                        </div>
                        <div class="panel-body">
                            <form action='' method='post'>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">ID  Twitter</label>
                                    <input type="text" class="form-control" name="ID_hashtag" value="<?= $data['ID_hashtag']; ?>"
                                           disabled>
                                    <input type="hidden" class="form-control" name="ID_hashtag" value="<?= $data['ID_hashtag']; ?>">
                                    <input type="hidden" class="form-control" name="IdText" value="<?= $data['IdText']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Hashtag  Twitter</label>
                                    <input type="text" class="form-control" name="Text_hashtag" value="<?= $data['Text_hashtag']; ?>"
                                           disabled>
                                    <input type="hidden" class="form-control" name="Text_hashtag" value="<?= $data['Text_hashtag']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">ข้อมูลด้านความคิดเห็น</label>
                                    <input type="text" class="form-control" name="like" value="ชอบ : <?= $sum1->like ?>"
                                           disabled>
                                    <input type="text" class="form-control" name="Indifferent" value="เฉย :<?= $sum1->Indifferent ?>"
                                           disabled>
                                    <input type="text" class="form-control" name="count" value="รวม : <?= $sum1->count ?>"
                                           disabled>

                                    <input type="hidden" class="form-control" name="like" value="<?= $sum1->like ?>">
                                    <input type="hidden" class="form-control" name="Indifferent" value="<?= $sum1->Indifferent ?>">
                                    <input type="hidden" class="form-control" name="count" value="<?= $sum1->count ?>">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">ข้อมูลด้านความรู้สึก</label>
                                    <input type="text" class="form-control" name="love" value="ความรัก : <?= $sum2->love ?>"
                                           disabled>
                                    <input type="text" class="form-control" name="secretly_in_love" value="แอบรัก :<?= $sum2->secretly_in_love ?>"
                                           disabled>
                                    <input type="text" class="form-control" name="lonelorn" value="อกหัก : <?= $sum2->lonelorn ?>"
                                           disabled>
                                    <input type="text" class="form-control" name="bored" value="เบื่อ : <?= $sum2->bored ?>"
                                           disabled>
                                    <input type="text" class="form-control" name="lovely" value="เหงา : <?= $sum2->lovely ?>"
                                           disabled>

                                    <input type="hidden" class="form-control" name="love" value="<?= $sum2->love ?>">
                                    <input type="hidden" class="form-control" name="secretly_in_love" value="<?= $sum2->secretly_in_love ?>">
                                    <input type="hidden" class="form-control" name="lonelorn" value="<?= $sum2->lonelorn ?>">
                                    <input type="hidden" class="form-control" name="bored" value="<?= $sum2->bored ?>">
                                    <input type="hidden" class="form-control" name="lovely" value="<?= $sum2->lovely ?>">
                                    <input type="hidden" class="form-control" name="count2" value="<?= $sum2->count2 ?>">
                                </div>
                                <div class="form-group">
                                    <button name="btn-signup" type="submit" class="form-control btn btn-success">เพิ่มข้อมูล
                                    </button>
                                </div>
                            </form>
                            <?php
                            if (isset($_POST['btn-signup'])) {

                                  $ID_hashtag = $_POST['ID_hashtag'];
                                  $IdText = $_POST['IdText'];
                                  $like = $_POST['like'];
                                  $Indifferent = $_POST['Indifferent'];
                                  $count = $_POST['count'];
                                  $love = $_POST['love'];
                                  $secretly_in_love = $_POST['secretly_in_love'];
                                  $lonelorn = $_POST['lonelorn'];
                                  $bored = $_POST['bored'];
                                  $lovely = $_POST['lovely'];
                                  $cou = $_POST['count2'];

                                try {
                                    if ($twitter->insert_data($ID_hashtag,$IdText,$like,$Indifferent,$count,$love,$secretly_in_love,$lonelorn,$bored,$lovely,$cou)) {

                                        if ($twitter == null) {
                                            echo 'No insert';
                                        } else {
                                            echo '<a href="data.php?id='.$ID_hashtag.'" class="btn btn-warning btn-lg">กลับ >></a>';
                                        }
                                    }
                                } catch (PDOException $e) {
                                    echo $e->getMessage();
                                }

                            }
                            ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-28746062-1']);
    _gaq.push(['_trackPageview']);
    (function () {
        var ga = document.createElement('script');
        ga.type = 'text/javascript';
        ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0];
        s.parentNode.insertBefore(ga, s);
    })();
</script>
<!-- Scripts -->
<script src="https://bootswatch.com/bower_components/jquery/dist/jquery.min.js"></script>
<script src="https://bootswatch.com/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
</body>
</html>

<?php
ini_set('display_errors', 1);
require_once('vendor/j7mbo/twitter-api-php/TwitterAPIExchange.php');
require_once('TwitterAPI.php');

$TwitterAPI = new TwitterAPI();
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // echo $id.'<br>';
    $settings = array(
        'oauth_access_token' => "884377439555641348-zKmbhFELYaIzSXpi92KVnVRBhKfCY1Y",
        'oauth_access_token_secret' => "FB26obNdkCkO9pYqNk18yyQNLNvb5firjDfX7J17tOrOM",
        'consumer_key' => "gHyHpzIf4Zgz2gn0c8k19ghZi",
        'consumer_secret' => "7ijb6SLad2XmMoAYvizkXK6vQ86tIlxW4V4ZU8wS4zOcRVoRBq"
    );

    $url = 'https://api.twitter.com/1.1/statuses/show.json';
    $requestMethod = 'GET';
    $getfield = '?id=' . $id . '';
    $twitter = new TwitterAPIExchange($settings);
    $tweets = $twitter->setGetfield($getfield)
        ->buildOauth($url, $requestMethod)
        ->performRequest();
    // $hashtags =  $tweets->entities->hashtags[0]->text;
    $count = count($tweets->entities->hashtags);
    // echo $count;
    $arr = [];
    for ($i = 0; $i < $count; $i++) {
        $arr[$i] = [
            'hashtags' => $tweets->entities->hashtags[$i]->text,
        ];
    }
    // $q  = $tweets->id_str;
    // echo $q ;
}

if (isset($_POST['btn-signup'])) {

    $IdText = $_POST['IdText'];
    $text = $_POST['title'];
    $year = $_POST['year'];
    $hashtags = $_POST['hashtags'];

    try {
        if ($TwitterAPI->insertText($IdText, $text, $year, $hashtags)) {

            if ($TwitterAPI == null) {
                echo 'No insert';
            } else {
                header('Location:show.php?joined');
            }
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Detail Twitter API</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
<!-- <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-info" style="margin-top: 15px">
                <div class="panel-heading ">
                    <h3 class="panel-title">ชื่อ Twitter By : <?= $tweets->user->name ?> </h3>
                </div>
                <div class="panel-body">
                     <?php
//echo '<pre>';
//print_r($tweets);
?>
                </div>
            </div>
        </div>
    </div> -->
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <?php
            if (isset($_GET['joined'])){
                ?>
                <div class="alert alert-info">
                    <i class="glyphicon glyphicon-log-in"></i> &nbsp;
                    Successfully Data Twitter <a href='report.php'>คลิก >>>> </a>ดูข้อมูลการจัดส่ง
                </div>
                <?php
            }else{
            ?>
            <div class="panel panel-info" style="margin-top: 15px">
                <div class="panel-heading ">
                    <h3 class="panel-title">ชื่อ Twitter By : <?= $tweets->user->name ?></h3>
                </div>
                <div class="panel-body">
                    <form action='' method='post'>
                        <div class="form-group">
                            <label for="exampleInputEmail1">ID Text Twitter</label>
                            <input type="text" class="form-control" name="IdText" value="<?= $tweets->id_str; ?>"
                                   disabled>
                            <input type="hidden" class="form-control" name="IdText" value="<?= $tweets->id_str; ?>">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">ข้อความ Twitter</label>
                            <textarea class="form-control" name="title" rows="5"><?= $tweets->text; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Datetime Twitter</label>
                            <input type="text" class="form-control" name="year" value="<?= $tweets->created_at; ?>">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Hashtags Twitter</label>
                            <select class="form-control" name="hashtags">
                                <?php
                                for ($j = 0; $j < count($arr); $j++) {
                                    echo ' <option>#
                                                ' . $arr[$j]['hashtags'] . '
                                            </option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <button name="btn-signup" type="submit" class="form-control btn btn-success">เพิ่มข้อมูล
                            </button>
                        </div>
                    </form>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
</body>
</html>
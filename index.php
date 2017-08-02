<?php
ini_set('display_errors', 1);
require_once('vendor/j7mbo/twitter-api-php/TwitterAPIExchange.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Twitter API SEARCH</title>
    <!-- Latest compiled and minified CSS -->
<!--    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"-->
<!--          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">-->
    <link rel="stylesheet" href="https://bootswatch.com/spacelab/bootstrap.min.css" >
</head>
<body>
<div class="container">
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#">ระบบแนะนำอาหาร จาก Twitter</a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <form class="navbar-form navbar-right" role="search" action="" method="post">
                    <div class="form-group">
                        <input type="text" class="form-control"placeholder="ใส่ข้อมูล" name="kb"/>
                    </div>
                    <button type="submit" class="btn btn-default" >Submit</button>
                </form>
            </div>
        </div>
    </nav>
    <br>
    <div class="row">
        <div class="col-sm-12">
            <div class="col-sm-3">
                <div class="list-group">
                    <a href="#" class="list-group-item active">
                        จัดการข้อมูล
                    </a>
                    <a href="#" class="list-group-item">ระบบจัดการหลังบ้าน</a>
                    <a href="data.php" class="list-group-item">ข้อมูลจาก Twitter</a>
                </div>
            </div>
            <div class="col-sm-9">
                <div class="panel panel-info" style="margin-top: 15px">
                <div class="panel-heading ">
                    <h3 class="panel-title">ค้นหาคำจาก Twitter : <?php  if(isset($_POST['kb']) == null){ echo '';}else{echo $_POST['kb'];} ?> </h3>
                </div>
                <div class="panel-body">
                    <?php
                     if (isset($_POST['kb'])) {
                       /** Set access tokens here - see: https://dev.twitter.com/apps/ **/
                        $settings = array(
                            'oauth_access_token' => "884377439555641348-zKmbhFELYaIzSXpi92KVnVRBhKfCY1Y",
                            'oauth_access_token_secret' => "FB26obNdkCkO9pYqNk18yyQNLNvb5firjDfX7J17tOrOM",
                            'consumer_key' => "gHyHpzIf4Zgz2gn0c8k19ghZi",
                            'consumer_secret' => "7ijb6SLad2XmMoAYvizkXK6vQ86tIlxW4V4ZU8wS4zOcRVoRBq"
                        );
                        $url = 'https://api.twitter.com/1.1/search/tweets.json';
                        $requestMethod = 'GET';
                        // $getfield = '?q=#อาหาร&result_type=recent&count=100&include_entities=true';
                        $getfield = '?since_id=250126199840518145&q=#'.$_POST['kb'].'&result_type=mixed&count=100&include_entities=true';
                        $twitter = new TwitterAPIExchange($settings);
                        $tweets = $twitter->setGetfield($getfield)
                            ->buildOauth($url, $requestMethod)
                            ->performRequest();

//                        echo '<pre>';
//                        print_r($tweets);
                        if ($tweets == null) {
                            echo 'NO DATA Twitter APIs';
                        } else {
                            $res = $tweets->statuses;
                            $Hashtags = [];
                            foreach ($res as $key => $tweet) {
                                 echo '
                             <div class="media">
                                <div class="media-left">
                                    <img alt="64x64" class="media-object" data-src="holder.js/64x64" style="width: 64px; height: 64px;" src="' . $tweet->user->profile_image_url . '" data-holder-rendered="true">
                                </div>
                                <div class="media-body">
                                    <h4>Hashtags Twitter : '.$tweet->id_str.'</h4>
                                    ' . $tweet->text.'
                                    <br>
                                     <a href="show.php?id='.$tweet->id_str.'" target="_blank">View</a>
                                </div>
                            </div> <br> ';
                                $Hashtags[$key] = [
                                    'ID' => $tweet->id_str,
                                    'text' => $tweet->text,
                                ];
                            }
                        }
                     }
                    ?>
                </div>
            </div>
           
           </div>
        </div>
    </div>
</div>
<!-- Scripts -->
<!--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>-->
<script src="https://bootswatch.com/bower_components/jquery/dist/jquery.min.js"></script>
<script src="https://bootswatch.com/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

</body>
</html>
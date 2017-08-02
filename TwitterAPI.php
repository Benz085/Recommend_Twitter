<?php
require('connect.php');

class TwitterAPI
{

    function __construct()
    {
        $database = new DataBase();
        $con = $database->getDB();
        $this->db = $con;
    }

    public function redirect($url)
    {
        header("Location: $url");
    }

    public function runQuery($sql)
    {
        $stmt = $this->db->prepare($sql);
        return $stmt;
    }

    public function insertText($IdText, $text, $year, $hashtags)
    {
        try {
//                    echo $IdText ;echo  '<br>';
//                    echo $text ;echo  '<br>';
//                    echo $year ;echo  '<br>';
//                    echo $hashtags ;echo  '<br>';
            $sql = ("SELECT * FROM `hashtag`  WHERE (Text_hashtag=:Text_hashtag) LIMIT 1");
            $res = $this->db->prepare($sql);
            $res->execute(array(':Text_hashtag' => $hashtags));
            $data = $res->fetch(PDO::FETCH_ASSOC);
//                echo '<pre>';
//                print_r($data);


            if ($data['Text_hashtag'] == $hashtags) {
                $total = $data['total'] + 1;
                $ID_hash = $data['ID_hashtag'];
//                    echo $total;echo  '<br>';
//                    echo $ID_hash;
                $stmt = $this->db->prepare("INSERT INTO `text_twitter`(`IdText`, `text`, `year`, `ID_hashtag`)
                                        VALUES(:IdText, :text, :year,:hashtags)");
                $stmt->bindParam(':IdText', $IdText, PDO::PARAM_STR);
                $stmt->bindParam(':text', $text, PDO::PARAM_STR);
                $stmt->bindParam(':year', $year, PDO::PARAM_STR);
                $stmt->bindParam(':hashtags', $ID_hash, PDO::PARAM_STR);
                $stmt->execute();

                $stmt2 = $this->db->prepare("UPDATE `hashtag` SET `total`=:total WHERE ID_hashtag=:ID_hashtag");
                $stmt2->bindParam(':total', $total, PDO::PARAM_STR);
                $stmt2->bindParam(':ID_hashtag', $ID_hash, PDO::PARAM_STR);
                $stmt2->execute();

                if ($stmt == null) {
                    return false;
                } else {
                    return true;
                }
            } else {
                echo 'ไม่มีข้อมูล';
                $count = 1;
                $stmt = $this->db->prepare("INSERT INTO `hashtag`(`Text_hashtag`, `total`) 
                                        VALUES(:Text_hashtag,:total) ");
                $stmt->bindParam(':Text_hashtag', $hashtags, PDO::PARAM_STR);
                $stmt->bindParam(':total', $count, PDO::PARAM_STR);
                $stmt->execute();
                $lastid1 = $this->db->lastInsertId();


                $stmt2 = $this->db->prepare("INSERT INTO `text_twitter`(`IdText`, `text`, `year`, `ID_hashtag`) 
                                        VALUES(:IdText, :text, :year,:hashtags) ");
                $stmt2->bindParam(':IdText', $IdText, PDO::PARAM_STR);
                $stmt2->bindParam(':text', $text, PDO::PARAM_STR);
                $stmt2->bindParam(':year', $year, PDO::PARAM_STR);
                $stmt2->bindParam(':hashtags', $lastid1, PDO::PARAM_STR);
                $stmt2->execute();
                if ($stmt == null) {
                    return false;
                } else {
                    return true;
                }

            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function processTwitter($string)
    {
        setlocale(LC_ALL, 'fr_CA.utf8');
        $data = preg_replace('/[^0-9A-Za-zก-ฮ๐-๙]/', "", $string);
        $data = preg_replace(array("/\^/", "/%/", "/~/", "/#/", "/@/", "/:/", "/\)/", "/\(/", "/{/"), "", $string);
        if ($data == null) {
            return false;
        } else {
            return json_encode($data);
        }
    }

    public function opinion($strArr)
    {
        $count = count($strArr);
        $like = 0;
        $Indifferent = 0;
        for ($i = 0; $i < $count; $i++) {
            switch ($strArr[$i]) {
                case "ชอบ":
                    $strArr[$i];
                    ($like++);
                    break;
                case "ติด":
                    $strArr[$i];
                    ($like++);
                    break;
                case "สนุก":
                    $strArr[$i];
                    ($like++);
                    break;
                case "สนับสนุน":
                    $strArr[$i];
                    ($like++);
                    break;
                case "โดน":
                    $strArr[$i];
                    ($like++);
                    break;
                case "แชร์":
                    $strArr[$i];
                    ($like++);
                    break;
                case "อยาก":
                    $strArr[$i];
                    ($like++);
                    break;
                case "รัก":
                    $strArr[$i];
                    ($like++);
                    break;
                case "เพอร์":
                    $strArr[$i];
                    ($like++);
                    break;
                case "เฟ็ค":
                    $strArr[$i];
                    ($like++);
                    break;
                case "บอก":
                    $strArr[$i];
                    ($like++);
                    break;
                case "เท่":
                    $strArr[$i];
                    ($like++);
                    break;
                case "ดีมาก":
                    $strArr[$i];
                    ($like++);
                    break;
                case "ดี":
                    $strArr[$i];
                    ($like++);
                    break;
                case "น่ารัก":
                    $strArr[$i];
                    ($like++);
                    break;
                case "ไม่ชอบ":
                    $strArr[$i];
                    ($Indifferent++);
                    break;
                case "เฉย":
                    $strArr[$i];
                    ($Indifferent++);
                    break;
                case "น่าเบื่อ":
                    $strArr[$i];
                    ($Indifferent++);
                    break;
                case "ไม่ดี":
                    $strArr[$i];
                    ($Indifferent++);
                    break;
                case "เกรียด":
                    $strArr[$i];
                    ($Indifferent++);
                    break;
                case "อึกอัก":
                    $strArr[$i];
                    ($Indifferent++);
                    break;
                case "เดิม":
                    $strArr[$i];
                    ($Indifferent++);
                    break;
            }
        }

//        echo $like.'<br>';
//        echo $Indifferent.'<br>';
        $sum = $like + $Indifferent;
        $arr = [
            'like' => $like,
            'Indifferent' => $Indifferent,
            'count' => $sum
        ];
        //
        if ($like > $Indifferent) {
            $Much = 0;
        } else {
            $little = 1;
        }

        if ($arr == null) {
            return false;
        } else {
            return json_encode($arr);
        }

    }

    public function feeling($strArr)
    {
        $count = count($strArr);
        $love = 0;
        $secretly_in_love = 0;
        $lonelorn = 0;
        $bored = 0;
        $lovely = 0;
        for ($i = 0; $i < $count; $i++) {
            switch ($strArr[$i]) {
                case "รัก":
                    $strArr[$i];
                    ($love++);
                    break;
                case "คนรัก":
                    $strArr[$i];
                    ($love++);
                    break;
                case "บอกรัก":
                    $strArr[$i];
                    ($love++);
                    break;
                case "ใจ":
                    $strArr[$i];
                    ($love++);
                    break;
                case "ฝืน":
                    $strArr[$i];
                    ($lonelorn++);
                    break;
                case "ห้ามใจ":
                    $strArr[$i];
                    ($lonelorn++);
                    break;
                case "โทด":
                    $strArr[$i];
                    ($lonelorn++);
                    break;
                case "โกรธ":
                    $strArr[$i];
                    ($lonelorn++);
                    break;
                case "นอกใจ":
                    $strArr[$i];
                    ($lonelorn++);
                    break;
                case "เจ็บ":
                    $strArr[$i];
                    ($lonelorn++);
                    break;
                case "ช้ำ":
                    $strArr[$i];
                    ($lonelorn++);
                    break;
                case "เลิกกัน":
                    $strArr[$i];
                    ($lonelorn++);
                    break;
                case "หน้าเศร้า":
                    $strArr[$i];
                    ($lonelorn++);
                    break;
                case "อ่อนแอ":
                    $strArr[$i];
                    ($lonelorn++);
                    break;
                case "น้ำตา":
                    $strArr[$i];
                    ($lonelorn++);
                    break;
                case "เท":
                    $strArr[$i];
                    ($lonelorn++);
                    break;
                case "พัง":
                    $strArr[$i];
                    ($lonelorn++);
                    break;
                case "เศร้า":
                    $strArr[$i];
                    ($lonelorn++);
                    break;
                case "มาก":
                    $strArr[$i];
                    ($love++);
                    ($secretly_in_love++);
                    break;
                case "ดีใจ":
                    $strArr[$i];
                    ($love++);
                    ($secretly_in_love++);
                    break;
                case "เกรียด":
                    $strArr[$i];
                    ($lonelorn++);
                    break;
                case "ทำร้าย":
                    $strArr[$i];
                    ($lonelorn++);
                    break;
                case "เศร้าใจ":
                    $strArr[$i];
                    ($lonelorn++);
                    break;
                case "เสียใจ":
                    $strArr[$i];
                    ($lonelorn++);
                    break;
                case "อกหัก":
                    $strArr[$i];
                    ($lonelorn++);
                    break;
                case "ปวดใจ":
                    $strArr[$i];
                    ($lonelorn++);
                    break;
                case "ไม่ลืม":
                    $strArr[$i];
                    ($lonelorn++);
                    break;
                case "ลืม":
                    $strArr[$i];
                    ($lonelorn++);
                    break;
                case "ทิ้ง":
                    $strArr[$i];
                    ($lonelorn++);
                    break;
                case "หลอก":
                    $strArr[$i];
                    ($lonelorn++);
                    break;
                case "ที่ผ่านมา":
                    $strArr[$i];
                    ($lonelorn++);
                    break;
                case "พยายาม":
                    $strArr[$i];
                    ($secretly_in_love++);
                    break;
                case "หล่อ":
                    $strArr[$i];
                    ($secretly_in_love++);
                    break;
                case "เพื่อนสนิท":
                    $strArr[$i];
                    ($secretly_in_love++);
                    break;
                case "บอก":
                    $strArr[$i];
                    ($secretly_in_love++);
                    break;
                case "ชอบ":
                    $strArr[$i];
                    ($secretly_in_love++);
                    break;
                case "แอบ":
                    $strArr[$i];
                    ($secretly_in_love++);
                    break;
                case "เบื่อ":
                    $strArr[$i];
                    ($bored++);
                    break;
                case "ขมขื่น":
                    $strArr[$i];
                    ($bored++);
                    break;
                case "เหงา":
                    $strArr[$i];
                    ($lovely++);
                    break;
                case "คนเดียว":
                    $strArr[$i];
                    ($lovely++);
                    break;
                case "โสด":
                    $strArr[$i];
                    ($lovely++);
                    ($secretly_in_love++);
                    break;
                case "คนโสด":
                    $strArr[$i];
                    ($lovely++);
                    ($secretly_in_love++);
                    break;
                case "คิดถึง":
                    $strArr[$i];
                    ($love++);
                    ($secretly_in_love++);
                    ($lonelorn++);
                    ($lovely++);
                    break;
                case "เหนื่อย":
                    $strArr[$i];
                    ($bored++);
                    ($lovely++);
                    break;
                case "สวย":
                    $strArr[$i];
                    ($secretly_in_love++);
                    break;
                case "จีบ":
                    $strArr[$i];
                    ($secretly_in_love++);
                    break;
                case "ความเบื่อ":
                    $strArr[$i];
                    ($bored++);
                    break;
                case "ความรัก":
                    $strArr[$i];
                    ($love++);
                    break;
                case "ร้องไห้":
                    $strArr[$i];
                    ($lonelorn++);
                    break;
            }
        }
//        echo  $love;
//        echo $secretly_in_love ;
//        echo $lonelorn ;
//        echo $bored ;
//        echo $lovely ;
        $sum = '';
        if ($love >= $secretly_in_love and $love >= $lonelorn and $love >= $bored and $love >= $lovely) {
//            echo 'ความรัก'.$sum1 = 1;
            $sum = 1;
        } else if ($secretly_in_love >= $love and $secretly_in_love >= $lonelorn and $secretly_in_love >= $bored and $secretly_in_love >= $lovely) {
//            echo 'แอบรัก'.$sum2 = 2;
            $sum = 2;
        } else if ($lonelorn >= $love and $lonelorn >= $secretly_in_love and $lonelorn >= $bored and $lonelorn >= $lovely) {
//            echo 'อกหัก'.$sum3 = 3;
            $sum = 3;
        } else if ($bored >= $love and $bored >= $secretly_in_love and $bored >= $lonelorn and $bored >= $lovely) {
//            echo 'เบื่อ'.$sum4 = 4;
            $sum = 4;
        } else if ($lovely >= $love and $lovely >= $secretly_in_love and $lovely >= $bored and $lovely >= $lonelorn) {
//            echo 'เหงา'.$sum5 = 5;
            $sum = 5;
        }
        $count = $love + $secretly_in_love + $lonelorn + $bored + $lovely;
        $arr = [
            'love' => $love,
            'secretly_in_love' => $secretly_in_love,
            'lonelorn' => $lonelorn,
            'bored' => $bored,
            'lovely' => $lovely,
//            'sum' => $sum,
            'count2' => $count,
        ];

        if ($arr == null) {
            return false;
        } else {
            return json_encode($arr);
        }
    }

    public function insert_data($ID_hashtag, $IdText, $like, $Indifferent, $count, $love, $secretly_in_love, $lonelorn, $bored, $lovely,$count2)
    {

        try {
            $sql = ("SELECT * FROM `opinion`  WHERE (ID_hashtag=:ID_hashtag) LIMIT 1");
            $res1 = $this->db->prepare($sql);
            $res1->execute(array(':ID_hashtag' => $ID_hashtag));
            $data1 = $res1->fetch(PDO::FETCH_ASSOC);
//            echo '<pre>';
//            print_r($data1);
            $sql2 = ("SELECT * FROM `feeling`  WHERE (ID_hashtag=:ID_hashtag) LIMIT 1");
            $res2 = $this->db->prepare($sql2);
            $res2->execute(array(':ID_hashtag' => $ID_hashtag));
            $data2 = $res2->fetch(PDO::FETCH_ASSOC);
//            echo '<pre>';
//            print_r($data2);
            if (($data1['ID_hashtag'] == $ID_hashtag )and ($data2['ID_hashtag'] == $ID_hashtag)) {

                $stmt3 = $this->db->prepare("UPDATE `text_twitter` SET `isActive` = '1' WHERE `text_twitter`.`IdText` =:IdText;");
                $stmt3->bindParam(':IdText', $IdText, PDO::PARAM_STR);
                $stmt3->execute();

                $o1 = $data1['comein'] + $count;
                $o2 = $data1['like'] + $like;
                $o3 = $data1['Indifferent'] + $Indifferent;
                $stmt = $this->db->prepare("UPDATE `opinion` SET `comein`=:count,
                                                                 `like`=:like,
                                                                 `Indifferent`=:Indifferent 
                                                                 WHERE opinion.ID_hashtag=:ID_hashtag");
                $stmt->bindParam(':ID_hashtag', $ID_hashtag, PDO::PARAM_STR);
                $stmt->bindParam(':count', $o1, PDO::PARAM_STR);
                $stmt->bindParam(':like', $o2, PDO::PARAM_STR);
                $stmt->bindParam(':Indifferent', $o3, PDO::PARAM_STR);
                $stmt->execute();


                $f1 = $data2['comein'] + $count2;
                $f2 = $data2['love'] + $love;
                $f3 = $data2['secretly_in_love'] + $secretly_in_love ;
                $f4 = $data2['lonelorn'] + $lonelorn;
                $f5 = $data2['bored'] + $bored;
                $f6 = $data2['lovely'] + $lovely;
                $stmt2 = $this->db->prepare("UPDATE `feeling` SET `comein`=:count,
                                                                  `love`=:love,
                                                                  `secretly_in_love`=:secretly,
                                                                  `lonelorn`=:lonelorn,
                                                                  `bored`=:bored,
                                                                  `lovely`=:lovely
                                                                  WHERE feeling.ID_hashtag=:ID_hashtag");
                $stmt2->bindParam(':ID_hashtag',  $ID_hashtag, PDO::PARAM_STR);
                $stmt2->bindParam(':count', $f1 , PDO::PARAM_STR);
                $stmt2->bindParam(':love',  $f2, PDO::PARAM_STR);
                $stmt2->bindParam(':secretly', $f3 , PDO::PARAM_STR);
                $stmt2->bindParam(':lonelorn', $f4 , PDO::PARAM_STR);
                $stmt2->bindParam(':bored',  $f5, PDO::PARAM_STR);
                $stmt2->bindParam(':lovely', $f6 , PDO::PARAM_STR);
                $stmt2->execute();

                if ($stmt == null) {
                    return false;
                } else {
                    return true;
                }

            } else {
//                echo 'ไม่มีข้อมูล';

                $stmt = $this->db->prepare("INSERT INTO `opinion`(`ID_hashtag`, `comein`, `like`, `Indifferent`)
                                  VALUES(:ID_hashtag,:total,:like,:Indifferent) ");
                $stmt->bindParam(':ID_hashtag', $ID_hashtag, PDO::PARAM_STR);
                $stmt->bindParam(':total', $count, PDO::PARAM_STR);
                $stmt->bindParam(':like', $like, PDO::PARAM_STR);
                $stmt->bindParam(':Indifferent', $Indifferent, PDO::PARAM_STR);
                $stmt->execute();

                $stmt2 = $this->db->prepare("INSERT INTO `feeling`(`ID_hashtag`, `comein`, `love`, `secretly_in_love`, `lonelorn`, `bored`, `lovely`) 
                                  VALUES(:ID_hashtag,:count,:love,:secretly,:lonelorn,:bored,:lovely) ");
                $stmt2->bindParam(':ID_hashtag', $ID_hashtag, PDO::PARAM_STR);
                $stmt2->bindParam(':count', $count2, PDO::PARAM_STR);
                $stmt2->bindParam(':love', $love, PDO::PARAM_STR);
                $stmt2->bindParam(':secretly', $secretly_in_love, PDO::PARAM_STR);
                $stmt2->bindParam(':lonelorn', $lonelorn, PDO::PARAM_STR);
                $stmt2->bindParam(':bored', $bored, PDO::PARAM_STR);
                $stmt2->bindParam(':lovely', $lovely, PDO::PARAM_STR);
                $stmt2->execute();

                $stmt3 = $this->db->prepare("UPDATE `text_twitter` SET `isActive` = '1' WHERE `text_twitter`.`IdText` =:IdText;");
                $stmt3->bindParam(':IdText', $IdText, PDO::PARAM_STR);
                $stmt3->execute();

                if ($stmt == null) {
                    return false;
                } else {
                    return true;
                }

            }
        }catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

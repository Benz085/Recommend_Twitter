<?php
//คำสั่ง connect db เขียนเพิ่มเองนะ

$strExcelFileName="Music.xls";
header("Content-Type: application/x-msexcel; name=\"$strExcelFileName\"");
header("Content-Disposition: inline; filename=\"$strExcelFileName\"");
header("Pragma:no-cache");

require_once 'TwitterAPI.php';
$twitter = new  TwitterAPI();
$stmt = $twitter->runQuery("SELECT hashtag.ID_hashtag,hashtag.Text_hashtag,hashtag.URL,feeling.comein,feeling.love,feeling.secretly_in_love,feeling.lonelorn,feeling.bored,feeling.lovely FROM hashtag INNER JOIN feeling ON hashtag.ID_hashtag = feeling.ID_hashtag");
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"xmlns:x="urn:schemas-microsoft-com:office:excel"xmlns="http://www.w3.org/TR/REC-html40">

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>

<div id="SiXhEaD_Excel" align=center x:publishsource="Excel">
    <table x:str border=1 cellpadding=0 cellspacing=1 width=100% style="border-collapse:collapse">
        <tr>
            <td width="120" height="30" align="center" valign="middle" ><strong>URL</strong></td>
            <td width="100" align="center" valign="middle" ><strong>Comein</strong></td>
            <td width="100" align="center" valign="middle" ><strong>love</strong></td>
            <td width="100" align="center" valign="middle" ><strong>Secretly_in_love</strong></td>
            <td width="100" align="center" valign="middle" ><strong>Lonelorn</strong></td>
            <td width="100" align="center" valign="middle" ><strong>Bored</strong></td>
            <td width="100" align="center" valign="middle" ><strong>Lovely</strong></td>
        </tr>
        <?php
        foreach ($data as $res){
            ?>
        <tr>
            <td align="center" valign="middle"><?= $res['URL'] ?></td>
            <td align="center" valign="middle"><?= $res['comein'] ?></td>
            <td align="center" valign="middle"><?= $res['love'] ?></td>
            <td align="center" valign="middle"><?= $res['secretly_in_love'] ?></td>
            <td align="center" valign="middle"><?= $res['lonelorn'] ?></td>
            <td align="center" valign="middle"><?= $res['bored'] ?></td>
            <td align="center" valign="middle"><?= $res['lovely'] ?></td>
        </tr>
        <?php } ?>
    </table>
</div>
<script>
    window.onbeforeunload = function(){return false;};
    setTimeout(function(){window.close();}, 10000);
</script>
</body>
</html>
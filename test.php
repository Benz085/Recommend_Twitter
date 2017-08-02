<?php
$text = ' RT nattaboat กูเจอแล้วไอสัส ในที่สุดก็ฟังท่อนแร็ปเพลง ก่อนฤดูฝน รู้เรื่องสักที กราบบ วงนี้ ';
echo $text;
echo '<hr/>';
$time_start = microtime(true);
$text_to_segment = trim($text);
include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'thsplitlib-master/THSplitLib/segment.php');
$segment = new Segment();
$result = $segment->get_segment_array($text_to_segment);
echo implode(' | ', $result);
echo '<hr/>';
function convert($size)
{
    $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
    return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
}

$time_end = microtime(true);
$time = $time_end - $time_start;
echo '<br/><b>ประมวลผลใน: </b> ' . round($time, 4) . ' วินาที';
echo '<br/><b>รับประทานหน่วยความจำไป:</b> ' . convert(memory_get_usage());
echo '<br/><b>คำที่อาจจะตัดผิด:</b> ';
foreach ($result as $row) {
    if (mb_strlen($row) > 12) {
        echo $row . '<br/>';
    }
}
echo '<hr/>';
?>
<script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', 'UA-28746062-1']);
    _gaq.push(['_trackPageview']);

    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

</script>

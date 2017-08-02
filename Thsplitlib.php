<?php
include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'thsplitlib-master/THSplitLib/segment.php');

class Thsplitlib
{
    public function processText($text)
    {
//        $text = ' RT nattaboat กูเจอแล้วไอสัส ในที่สุดก็ฟังท่อนแร็ปเพลง ก่อนฤดูฝน รู้เรื่องสักที กราบบ วงนี้ ';
        $time_start = microtime(true);
        $text_to_segment = trim($text);
        $segment = new Segment();
        $result = $segment->get_segment_array($text_to_segment);
            echo implode(' | ', $result);
        function convert($size) {
            $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
            return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
        }
        $time_end = microtime(true);
        $time = $time_end - $time_start;
        echo '<br/><b>ประมวลผลใน: </b> '.round($time,4).' วินาที';
        echo '<br/><b>รับประทานหน่วยความจำไป:</b> ' . convert(memory_get_usage());
        echo '<br/><b>คำที่อาจจะตัดผิด:</b> ';

        foreach($result as $row)
        {
            if (mb_strlen($row) > 12)
            {
                echo $row.'<br/>';
            }
        }
        echo '<hr/>';
        if ($result == null)
        {
            return false;
        } else {
            return json_encode($result);
        }
    }
}
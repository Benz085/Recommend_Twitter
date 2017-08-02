<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml" xml:lang="en" lang="en"> 
    <head> 
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" /> 
        <title>ตัดคำภาษาไทยโดย PHP - THSplitLib</title>
    </head>
    <body>
    <style type="text/css">
        body{
            font-size:13px;
        }
    </style>
    <div style="float:left; width:400px;">
        <form method="post">
            <b>กรุณาใส่ประโยคที่ต้องการทดสอบได้ที่นี่ครับ: </b><br/>
            <textarea name="text_to_segment" cols="40" rows="50" style="width:340px;height:500px;"><?php echo isset($_POST['text_to_segment'])?  trim($_POST['text_to_segment']):'' ?></textarea>
            <br/>
            <input type="submit" value="กดที่นี่เพื่อตัดคำ" style="width:347px;height:40px;font-size:18px;background: #eee"/>
            
        </form>
    </div>
    <div style="float:left; width:400px;">
        <b>ดาวน์โหลด Lib ล่าสุด: <a href="http://bit.ly/xWyzO4" target="_blank">ดาวน์โหลด</a></b> 
            <?php
        $filename = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'THSplitLib.zip';
        if (file_exists($filename)) {
            echo '('.date ("F d Y H:i:s", filemtime($filename)).')';
        }
        ?>
        <br/>
        <b>ดาวน์โหลด Dictionary ล่าสุด: <a href="http://bit.ly/y30wzC" target="_blank">ดาวน์โหลด</a></b> 
        <?php
        $filename = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'dictionary.zip';
        if (file_exists($filename)) {
            echo '('.date ("F d Y H:i:s", filemtime($filename)).')';
        }
        ?>
        <br/><br/>
        <b>พัฒนาโดย: </b><br/> นายลิง หรือ moohooooo (<a href="mailto:suwichalala@gmail.com">suwichalala@gmail.com</a>)
        <br/><br/>
        
        <b>วิธีการใช้ Lib:</b><br/><br/>
        - ดาวโหลดเวอร์ชั่นล่าสุด จากด้านบน<br/>
        - เพื่อที่จะใช้งานใน PHP ได้ จะต้อง Include Lib ตัวนี้โดยที่ Include เฉพาะ segment.php (Segment จะทำการ include ตัวอื่นเอง)</br><br/>
        - ทดลองส่ง ค่า เข้าไป ใน Obj ของ Segment Class เช่น segment_obj->get_segment_array('คำที่ต้องการตัด')<br/><br/>
        - ค่าที่ได้ออกมา จะเป็น Array 1 มิติ ซึ่งสามารถเอาไปใช้ งานได้เลย หรือ ถ้าใครอยากได้ format สำหรับ Full Text Search ก็ implode(' ',$segment_result) ได้นะครับสำหรับ Mysql
        <br/><br/>
        <b>วิธีการใช้ Lib (ผ่าน Api):</b><br/><br/>
        - Link API : http://www.projecka.com/THSplitLib/webservice.php?string=คำที่ต้องการ (แบบ GET) หรือ แบบ POST ก็ได้<br/>
        - ค่าที่ส่งมาจะเป็น json ให้ Decode เองนะครับ หรือถ้าใช้ Javascript ก็จัดการเองได้เลย<br/>
        - ค่าที่ส่งมา จะมี ทั้ง ชุด Words และ Words count<br/><br/>
        * Lib ตัวนี้สำหรับ UTF-8 เท่านั้นครับ ใครที่ใช้ TIS-620 ต้อง Encode ก่อนใช้ส่งค่าเข้าเพื่อตัดคำครับ
		** หากเอาไปใช้งาน Commercial สามารถเอาไปใช้ได้นะครับ แต่ว่าฝากบอกผมนิดนึงครับ จะได้ทำสถิติ (~เป็นกำลังใจให้ตัวเองด้วย) 55+
        <br/>
        <a rel="license" href="http://creativecommons.org/licenses/by/3.0/"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by/3.0/88x31.png" /></a><br /><span xmlns:dct="http://purl.org/dc/terms/" property="dct:title">THSplitLib</span> by <a xmlns:cc="http://creativecommons.org/ns#" href="http://www.projecka.com/THSplitLib/" property="cc:attributionName" rel="cc:attributionURL">Suwicha Phuak-im</a> is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by/3.0/">Creative Commons Attribution 3.0 Unported License</a>.<br />Based on a work at <a xmlns:dct="http://purl.org/dc/terms/" href="http://www.projecka.com/THSplitLib/" rel="dct:source">www.projecka.com</a>.
        <br/>
        Dictionary (บางส่วน) refer to : <a href="http://www.sansarn.com/lexto/" target="_blank">http://www.sansarn.com/lexto/</a>
    </div>
    <div style="clear:both"></div>
    <div style="width:800px;">
        <?php
        if ($_POST) {
            
            $time_start = microtime(true);
            
            $text_to_segment = trim($_POST['text_to_segment']);
            echo $text_to_segment;
            echo '<hr/>';
            //echo '<b>ประโยคที่ต้องการตัดคือ: </b>' . $text_to_segment . '<br/>';
            include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'THSplitLib/segment.php');
            $segment = new Segment();
            //echo '<hr/>';
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
        }
        ?>

    </div>
</body>
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
</html>

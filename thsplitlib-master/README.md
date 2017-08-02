thsplitlib
==========

Lib ตัดคำภาษาไทย สำหรับ PHP

How to use
==========


  include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'THSplitLib/segment.php');

  $segment = new Segment();

  $result = $segment->get_segment_array("คำที่ต้องการตัด");

  echo implode(' | ', $result);
  
  จะได้ Output คือ คำ | ที่ | ต้องการ | ตัด


คุณสามารถนำไปใช้ได้สำหรับทุก Web Application ทุกชนิดที่คุณต้องการ
แต่หากนำไปใช้เพื่อเชิงพานิชย์ กรุณา บอกเราด้วยครับที่ suwichalala@gmail.com

หากมีข้อสงสัย หรือ สอบถามสามารถแจ้งได้ที่ suwichalala@gmail.com
ขอบคุณครับ

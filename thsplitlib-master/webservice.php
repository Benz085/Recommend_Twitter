<?php
if (!isset($_REQUEST['string'])) {
    echo json_encode(array());
    die();
}
$string = $_REQUEST['string'];
include(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'THSplitLib/segment.php');
$segment = new Segment();

$result['words'] = $segment->get_segment_array($string);
$result['words_count'] = count($result['words']);
//js_thai_encode($result);
header('Content-type: text/html; charset=utf-8');
echo json_encode($result);






<?php

require_once("../../include/config.php"); 
require_once("../../include/table_vars.php");

mysql_connect(DB_HOST, DB_USER_NAME, DB_PASSWORD) or die(mysql_error());
mysql_select_db(DB_NAME) or die(mysql_error());

$popupDetails = array();
$query = "SELECT arabic_text, image, link_type, link_id, link_text_arabic, arabic_title, external_link FROM ".TBL_POPUP."  WHERE 1 ORDER BY id ASC LIMIT 1";
$result = @mysql_query($query);
$popupDetails = mysql_fetch_row($result);

if($popupDetails['2']==""){ $popupDetails['2'] = ""; $popupDetails['3'] = ""; $popupDetails['4'] = ""; $popupDetails['6'] = ""; }
else{ 
        //$popupDetails['popup_type'] = $popupDetails['2'];  

        if($popupDetails['2']=="business") {
            $query2 = " SELECT bd.name, bdi.business_logo,(select IF(cd.parent_id = 0,cd.category_id,cd.parent_id)) as parent_category_id, cd.title FROM ".TBL_BUSINESS." bd
                        LEFT JOIN ".TBL_BUSINESS_INFO ." bdi ON bd.id = bdi.business_id
                        LEFT JOIN categories_desc cd ON bdi.dropdown_id = cd.category_id
                        WHERE bd.deleted='0' AND bdi.lng_id = 2 AND cd.lng_id = 2 AND bd.id = ".$popupDetails['3']." AND bd.status = 'A'";
            //echo $query2; exit;
            $result2 = @mysql_query($query2);
            $businessDetails = mysql_fetch_row($result2);

            //$popupDetails['popup_business_id'] = $popupDetails['3'];
            //$popupDetails['popup_business_name'] = $businessDetails['0'];
            $popupDetails['7'] = "http://192.168.10.2/rating/uploads/business/business_resize/350-350-".$businessDetails['1'];
            //$popupDetails['parent_category_id'] = $businessDetails['2'];
            //$popupDetails['parent_category_title'] = $businessDetails['3'];

//            $rtdata = getCaptions($popupDetails['3'],$body->lang_id);
//            $popupDetails['category_service1'] = $rtdata['service1'];
//            $popupDetails['category_service2'] = $rtdata['service2'];
//            $popupDetails['category_service3'] = $rtdata['service3'];
        }

        elseif($popupDetails['2']=="poll") {
//            $query3 = "SELECT q.question FROM ". TBL_QUE_MGMT . " qm, ". TBL_QUE . " q WHERE q.deleted='0' AND qm.question_id = q.question_id AND q.lng_id = ".$body->lang_id." AND q.question_id = ".$popupDetails['3']." "; 
//            //echo $query3; exit;
//            $result3 = @mysql_query($query3);
//            $pollDetails = mysql_fetch_row($result3);
//
//            $popupDetails['popup_poll_id'] = $popupDetails['3'];
//            $popupDetails['popup_poll_name'] = $pollDetails['0'];
              $popupDetails['7'] = "";
        }

        elseif($popupDetails['2']=="category") {
            $query4 = "SELECT cm.name, cd.category_logo, cd.service1, cd.service2, cd.service3 FROM ".TBL_BLOG_CATEGORIES." cm LEFT JOIN  ".TBL_BLOG_CAT_DESC." cd ON cm.id = cd.category_id WHERE deleted='0' AND flag='1' AND cd.lng_id = 2 AND cm.id = ".$popupDetails['3']." "; 
            //echo $query4; exit;
            $result4 = @mysql_query($query4);
            $categoryDetails = mysql_fetch_row($result4);

            //$popupDetails['popup_category_id'] = $popupDetails['3'];
            //$popupDetails['popup_category_title'] = $categoryDetails['0'];
            $popupDetails['7'] = "http://192.168.10.2/rating/uploads/category/category_resize/232-117-".$categoryDetails['1'];
            //$popupDetails['category_service1'] = $categoryDetails['2'];
            //$popupDetails['category_service2'] = $categoryDetails['3'];
            //$popupDetails['category_service3'] = $categoryDetails['4'];
        }
        
        elseif($popupDetails['2']=="external") {
            $popupDetails['7'] = "";
        }
}

//print_r($popupDetails); exit;
//http://192.168.10.2/rating/uploads/business/business_resize/350-350-

//http://192.168.10.2/rating/uploads/category/category_resize/232-117-


echo json_encode($popupDetails);


?>
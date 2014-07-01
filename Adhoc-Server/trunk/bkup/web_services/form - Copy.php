<html>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WebService for Scanner app</title>
<head>
<script src="jquery-1.6.1.min.js"></script>
    <script language="javascript">
    onload = function() {
        //check_measures1();
        //check_contact();
    }
</script>
  <script>
    $(document).ready(function(){

             /****************************************************************/
      		/******************** Get Today's Product  **********************/
      		/****************************************************************/

		  $('#btn_get_today_product').click(function(){

         	var json_header="{\"name\":\"get_today_product\"}";

				$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response){

					  $('#req_form_get_today_product').html(json_header);
					  $('#res_form_get_today_product').html(response);

				  }
				});
		 });

             /****************************************************************/
      		/******************** Get Product List **********************/
      		/****************************************************************/

		  $('#btn_get_all_product_list').click(function(){

         	var json_header="{\"name\":\"get_all_product_list\"}";

				$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response){

					  $('#req_form_get_all_product_list').html(json_header);
					  $('#res_form_get_all_product_list').html(response);

				  }
				});
		 });

             /****************************************************************/
      		/******************** Get Category List **********************/
      		/****************************************************************/

		  $('#btn_get_category_list').click(function(){

         	var json_header="{\"name\":\"get_category_list\"}";

				$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response){

					  $('#req_form_get_category_list').html(json_header);
					  $('#res_form_get_category_list').html(response);

				  }
				});
		 });

             /****************************************************************/
      		/******************** Get About Us page **********************/
      		/****************************************************************/

		  $('#btn_get_about_us_page').click(function(){
         	var json_header="{\"name\":\"get_about_us_page\"}";

				$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response){

					  $('#req_form_get_about_us_page').html(json_header);
					  $('#res_form_get_about_us_page').html(response);

				  }
				});
		 });

             /****************************************************************/
      		/******************** Get Contct Us page **********************/
      		/****************************************************************/

		  $('#btn_get_contact_us_page').click(function(){
         	var json_header="{\"name\":\"get_contact_us_page\"}";

				$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response){

					  $('#req_form_get_contact_us_page').html(json_header);
					  $('#res_form_get_contact_us_page').html(response);

				  }
				});
		 });

    	/****************************************************************/
  		/******************** Select User Categories ***************************/
  		/****************************************************************/

            $('#btn_form_user_categories').click(function(){
		 	//var json_body="{\"user_id\":\""+$('#user_id').val()+"\}"
		 	var json_body="{\"user_id\":\""+$('#category_user_id').val()+"\""+"}"
		    var json_header="{\"name\":\"user_categories\",\"body\":"+json_body+"}";

				$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response_logout)
                  {
				    $('#req_btn_form_user_categories').html(json_header);
				    $('#res_btn_form_user_categories').html(response_logout);
				  }
				});
		 });

    	/****************************************************************/
  		/******************** Update User Categories ***************************/
  		/****************************************************************/

            $('#btn_form_user_categories_update').click(function(){
		 	//var json_body="{\"user_id\":\""+$('#category_user_id').val()+"\""+"}"
		 	var json_body="{\"user_id\":\""+$('#category_update_user_id').val()+"\",\"category_update_id\":\""+$('#category_update_id').val()+"\"}"

		    var json_header="{\"name\":\"user_categories_update\",\"body\":"+json_body+"}";

				$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response_logout)
                  {
				    $('#req_btn_form_user_categories_update').html(json_header);
				    $('#res_btn_form_user_categories_update').html(response_logout);
				  }
				});
		 });

    	/****************************************************************/
  		/******************** View User Notifications *******************/
  		/****************************************************************/

            $('#btn_form_user_notification').click(function(){
		 	var json_body="{\"user_id\":\""+$('#notification_user_id').val()+"\""+"}"

		    var json_header="{\"name\":\"user_notification\",\"body\":"+json_body+"}";

				$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response_logout)
                  {
				    $('#req_btn_form_user_notification').html(json_header);
				    $('#res_btn_form_user_notification').html(response_logout);
				  }
				});
		 });

    	/****************************************************************/
  		/******************** Update User Notifications *****************/
  		/****************************************************************/

            $('#btn_form_user_notification_update').click(function(){
		 	//var json_body="{\"user_id\":\""+$('#user_id').val()+"\}"
		 	//var json_body="{\"user_id\":\""+$('#notification_update_user_id').val()+"\""+"}"
		 	var json_body="{\"user_id\":\""+$('#notification_update_user_id').val()+"\",\"notification_update_status\":\""+$('#notification_update_status').val()+"\"}"

		    var json_header="{\"name\":\"user_notification_update\",\"body\":"+json_body+"}";

				$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response_logout)
                  {
				    $('#req_btn_form_user_notification_update').html(json_header);
				    $('#res_btn_form_user_notification_update').html(response_logout);
				  }
				});
		 });

    	/****************************************************************/
  		/******************** Select Lauguage Variable  *****************/
  		/****************************************************************/

            $('#btn_form_language_variable').click(function(){
		 	var json_body="{\"input_language_variable\":\""+$('#input_language_variable').val()+"\""+"}"
		    var json_header="{\"name\":\"language_variable\",\"body\":"+json_body+"}";

				$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response_logout)
                  {
				    $('#req_btn_form_language_variable').html(json_header);
				    $('#res_btn_form_language_variable').html(response_logout);
				  }
				});
		 });


    	/****************************************************************/
  		/******************** User Register  ****************************/
  		/****************************************************************/

            $('#btn_form_user_register').click(function(){
		 	var json_body="{\"input_user_register\":\""+$('#input_user_register').val()+"\""+"}"
		    var json_header="{\"name\":\"user_register\",\"body\":"+json_body+"}";

				$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response_logout)
                  {
				    $('#req_btn_form_user_register').html(json_header);
				    $('#res_btn_form_user_register').html(response_logout);
				  }
				});
		 });

    	/****************************************************************/
  		/******************** Push Notification  ****************************/
  		/****************************************************************/

            $('#btn_form_push_notification').click(function(){
		 	var json_body="{\"push_notification_msg\":\""+$('#push_notification_msg').val()+"\""+"}"
		    var json_header="{\"name\":\"push_notification\",\"body\":"+json_body+"}";

				$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response_logout)
                  {
				    $('#req_btn_form_push_notification').html(json_header);
				    $('#res_btn_form_push_notification').html(response_logout);
				  }
				});
		 });

    	/****************************************************************/
  		/******************** Setting Time   ****************************/
  		/****************************************************************/

            $('#btn_form_setting_time').click(function(){
		 	//var json_body="{\"setting_time_msg\":\""+$('#setting_time_msg').val()+"\""+"}"
         	var json_header="{\"name\":\"setting_time\"}";

				$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response_logout)
                  {
				    $('#req_btn_form_setting_time').html(json_header);
				    $('#res_btn_form_setting_time').html(response_logout);
				  }
				});
		 });

});

  </script>
 <script>

 function check_measures1()
 {
   if(document.getElementById('check_measures').checked==true)
   {
       $('#check_measures').val('1');
   }
   else
   {
      $('#check_measures').val('0');
   }
 }
 function check_contact()
 {
   if(document.getElementById('get_contacted').checked==true)
   {
       $('#get_contacted').val('1');
   }
   else
   {
      $('#get_contacted').val('0');
   }

 }
 </script>
 <!--  -->
 <!--Inline CSS-->
 <style type="text/css">
.mainContainer {
	width: 100%;
	font-size: 12px;
	font-family: verdana;
}

.fl {
	float: left;
}

.w500 {
	width: 500px;
}

.clear {
	clear: both;
}

.box {
	border: 1px solid lightgray;
	-moz-border-radius: 4px;
	padding: 5px;
	margin: 20px;
    margin-left: 400px;

}

.box .header {
	background: none repeat scroll 0 0 #EEEEEE;
	border-top-left-radius: 4px;
	border-top-right-radius: 4px;
	color: grey;
	font-family: verdana;
	font-size: 14px;
	font-weight: bold;
	margin-bottom: 5px;
	padding: 5px;
	text-align: center;
}

.box .cont {
	padding: 5px;
}

.box .reqT,.box .resT {
	margin-bottom: 10px;
}
</style>
</head>

<body>
<div class="mainContainer" >
   		<div class="fl w500 box">
			<form name="form_get_today_products_detail" id="form_get_today_products_detail">
				<div class="header">1) Today's offer/product detail</div>
				<div class="cont">
				   <div class="reqcap" style="font-weight: bold">Request</div>
					<textarea class="reqT" id="req_form_get_today_product" rows="5"
						cols="58"></textarea>
					<div class="rescap" style="font-weight: bold">Response</div>
					<textarea class="resT" id="res_form_get_today_product" rows="5"
						cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btn_get_today_product" value="Execute" />
					</div>
				</div>
			</form>
		</div>

   		<div class="fl w500 box">
			<form name="form_get_all_products_detail" id="form_get_all_products_detail">
				<div class="header">2) List of all offers/product (only 4 except 1st)</div>
				<div class="cont">
				   <div class="reqcap" style="font-weight: bold">Request</div>
					<textarea class="reqT" id="req_form_get_all_product_list" rows="5"
						cols="58"></textarea>
					<div class="rescap" style="font-weight: bold">Response</div>
					<textarea class="resT" id="res_form_get_all_product_list" rows="5"
						cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btn_get_all_product_list" value="Execute" />
					</div>
				</div>
			</form>
		</div>

   		<div class="fl w500 box">
			<form name="form_get_categories_list" id="form_get_categories_list">
				<div class="header">3) Get Category List</div>
				<div class="cont">
				   <div class="reqcap" style="font-weight: bold">Request</div>
					<textarea class="reqT" id="req_form_get_category_list" rows="5"
						cols="58"></textarea>
					<div class="rescap" style="font-weight: bold">Response</div>
					<textarea class="resT" id="res_form_get_category_list" rows="5"
						cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btn_get_category_list" value="Execute" />
					</div>
				</div>
			</form>
		</div>

   		<div class="fl w500 box">
			<form name="form_get_about_us_page" id="form_get_about_us_page">
				<div class="header">4) Get About Us Page</div>
				<div class="cont">
				   <div class="reqcap" style="font-weight: bold">Request</div>
					<textarea class="reqT" id="req_form_get_about_us_page" rows="5"
						cols="58"></textarea>
					<div class="rescap" style="font-weight: bold">Response</div>
					<textarea class="resT" id="res_form_get_about_us_page" rows="5"
						cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btn_get_about_us_page" value="Execute" />
					</div>
				</div>
			</form>
		</div>

   		<div class="fl w500 box">
			<form name="form_get_contact_us_page" id="form_get_contact_us_page">
				<div class="header">5) Get Contact Us Page</div>
				<div class="cont">
				   <div class="reqcap" style="font-weight: bold">Request</div>
					<textarea class="reqT" id="req_form_get_contact_us_page" rows="5"
						cols="58"></textarea>
					<div class="rescap" style="font-weight: bold">Response</div>
					<textarea class="resT" id="res_form_get_contact_us_page" rows="5"
						cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btn_get_contact_us_page" value="Execute" />
					</div>
				</div>
			</form>
		</div>

        <div class="fl w500 box">
            <form name="form_user_categories" id="form_user_categories">
				<div class="header">6) Select User Categories</div>
				<div class="cont">
					<div style="font-weight: bold">User Id</div>
					<div>
						<input type="text" id="category_user_id" name="category_user_id" />
					</div>
					<div style="font-weight: bold" class="reqcap">Request</div>
					<textarea class="reqT" id="req_btn_form_user_categories"
						name="req_btn_form_user_categories" rows="5" cols="58"></textarea>
					<div style="font-weight: bold" class="rescap">Response</div>
					<textarea class="resT" id="res_btn_form_user_categories"
						name="res_btn_form_user_categories" rows="5" cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btn_form_user_categories" name="btn_form_user_categories"
							value="Execute" />
					</div>
				</div>
			</form>
		</div>

        <div class="fl w500 box">
            <form name="form_user_categories_update" id="form_user_categories_update">
				<div class="header">7) Update User Categories 1=add, 0=remove</div>
				<div class="cont">
					<div style="font-weight: bold">User Id</div>
					<div>
						<input type="text" id="category_update_user_id" name="category_update_user_id" />
					</div>
					<div style="font-weight: bold">Enter User Category Id</div>
					<div>
						<input type="text" id="category_update_id" name="category_update_id" />
					</div>
					<div style="font-weight: bold" class="reqcap">Request</div>
					<textarea class="reqT" id="req_btn_form_user_categories_update"
						name="req_btn_form_user_categories_update" rows="5" cols="58"></textarea>
					<div style="font-weight: bold" class="rescap">Response</div>
					<textarea class="resT" id="res_btn_form_user_categories_update"
						name="res_btn_form_user_categories_update" rows="5" cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btn_form_user_categories_update" name="btn_form_user_categories_update"
							value="Execute" />
					</div>
				</div>
			</form>
		</div>

        <div class="fl w500 box">
            <form name="form_user_notification" id="form_user_notification">
				<div class="header">8) Select Notification On/Off 1=on, 0=off</div>
				<div class="cont">
					<div style="font-weight: bold">User Id</div>
					<div>
						<input type="text" id="notification_user_id" name="notification_user_id" />
					</div>
					<div style="font-weight: bold" class="reqcap">Request</div>
					<textarea class="reqT" id="req_btn_form_user_notification"
						name="req_btn_form_user_notification" rows="5" cols="58"></textarea>
					<div style="font-weight: bold" class="rescap">Response</div>
					<textarea class="resT" id="res_btn_form_user_notification"
						name="res_btn_form_user_notification" rows="5" cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btn_form_user_notification" name="btn_form_user_notification"
							value="Execute" />
					</div>
				</div>
			</form>
		</div>

        <div class="fl w500 box">
            <form name="form_user_notification_update" id="form_user_notification_update">
				<div class="header">9) Update Notification On/Off 1=on, 0=off</div>
				<div class="cont">
					<div style="font-weight: bold">User Id</div>
					<div>
						<input type="text" id="notification_update_user_id" name="notification_update_user_id" />
					</div>
					<div style="font-weight: bold">Inout On/Off (1/0)</div>
					<div>
						<input type="text" id="notification_update_status" name="notification_update_status" />
					</div>
					<div style="font-weight: bold" class="reqcap">Request</div>
					<textarea class="reqT" id="req_btn_form_user_notification_update"
						name="req_btn_form_user_notification_update" rows="5" cols="58"></textarea>
					<div style="font-weight: bold" class="rescap">Response</div>
					<textarea class="resT" id="res_btn_form_user_notification_update"
						name="res_btn_form_user_notification_update" rows="5" cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btn_form_user_notification_update" name="btn_form_user_notification_update"
							value="Execute" />
					</div>
				</div>
			</form>
		</div>

        <div class="fl w500 box">
            <form name="form_language_variable" id="form_language_variable">
				<div class="header">10) Language Variable</div>
				<div class="cont">
					<div style="font-weight: bold">Language Variable</div>
					<div>
						<input type="text" id="input_language_variable" name="input_language_variable" />
					</div>
					<div style="font-weight: bold" class="reqcap">Request</div>
					<textarea class="reqT" id="req_btn_form_language_variable"
						name="req_btn_form_language_variable" rows="5" cols="58"></textarea>
					<div style="font-weight: bold" class="rescap">Response</div>
					<textarea class="resT" id="res_btn_form_language_variable"
						name="res_btn_form_language_variable" rows="5" cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btn_form_language_variable" name="btn_form_language_variable"
							value="Execute" />
					</div>
				</div>
			</form>
		</div>

        <div class="fl w500 box">
            <form name="form_user_register" id="form_user_register">
				<div class="header">11) User Register</div>
				<div class="cont">
					<div style="font-weight: bold">Registration Id</div>
					<div>
						<input type="text" id="input_user_register" name="input_user_register" />
					</div>
					<div style="font-weight: bold" class="reqcap">Request</div>
					<textarea class="reqT" id="req_btn_form_user_register"
						name="req_btn_form_user_register" rows="5" cols="58"></textarea>
					<div style="font-weight: bold" class="rescap">Response</div>
					<textarea class="resT" id="res_btn_form_user_register"
						name="res_btn_form_user_register" rows="5" cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btn_form_user_register" name="btn_form_user_register"
							value="Execute" />
					</div>
				</div>
			</form>
		</div>

        <div class="fl w500 box">
            <form name="form_push_notification" id="form_push_notification">
				<div class="header">12)Push Notification</div>
				<div class="cont">
					<div style="font-weight: bold">Notification Message</div>
					<div>
						<input type="text" id="push_notification_msg" name="push_notification_msg" size="75" maxlength="100" />
					</div>
					<div style="font-weight: bold" class="reqcap">Request</div>
					<textarea class="reqT" id="req_btn_form_push_notification"
						name="req_btn_form_push_notification" rows="5" cols="58"></textarea>
					<div style="font-weight: bold" class="rescap">Response</div>
					<textarea class="resT" id="res_btn_form_push_notification"
						name="res_btn_form_push_notification" rows="5" cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btn_form_push_notification" name="btn_form_push_notification"
							value="Execute" />
					</div>
				</div>
			</form>
		</div>

        <div class="fl w500 box">
            <form name="form_setting_time" id="form_setting_time">
				<div class="header">13) Setting Time</div>
				<div class="cont">
					<div style="font-weight: bold" class="reqcap">Request</div>
					<textarea class="reqT" id="req_btn_form_setting_time"
						name="req_btn_form_setting_time" rows="5" cols="58"></textarea>
					<div style="font-weight: bold" class="rescap">Response</div>
					<textarea class="resT" id="res_btn_form_setting_time"
						name="res_btn_form_setting_time" rows="5" cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btn_form_setting_time" name="btn_form_setting_time"
							value="Execute" />
					</div>
				</div>
			</form>
		</div>

</div>
</body>
</html>
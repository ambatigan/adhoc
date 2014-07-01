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
      		/******************** Get Product List **********************/
      		/****************************************************************/

		  $('#btn_get_all_product_list').click(function(){
            var json_body="{\"SyncDate\":\""+$('#SyncDate').val()+"\"}"
         	var json_header="{\"name\":\"get_all_product_list\",\"body\":"+json_body+"}";
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
    margin-left: 120px;

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
			<form name="form_get_all_products_detail" id="form_get_all_products_detail">
				<div class="header">1) List of all products (after previous Sync)</div>
				<div class="cont">
                    <div style="font-weight: bold">Sync Date</div>
					<div>
						<input type="text" id="SyncDate" name="SyncDate" /><small>(Date : YYYY-MM-DD HH:MM:SS)</small>
					</div>
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
				<div class="header">2) Get Category List</div>
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



</div>
</body>
</html>
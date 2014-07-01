<html>
<head>
<!--<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />-->

<meta http-equiv='Content-Type' content='Type=text/html; charset=utf-8'>

<script type="text/javascript" src="jquery-1.6.1.min.js"></script>

<script type="text/javascript">

    $(document).ready(function(){

	   $('.cont').find('div').css('font-weight','bold');

	   /****************************************************************/
 		/******************** FB Registration *************************************/
 		/****************************************************************/

           $('#btn_form_fb_registration').click(function(){

            var json_body="{\"firstname\":\""+$('#fname').val()+"\",\"lastname\":\""+$('#lname').val()+"\",\"emailid\":\""+$('#emailid').val()+"\",\"sex\":\""+$('#gender').val()+"\",\"birthdate\":\""+$('#bdate').val()+"\",\"profileimage\":\""+$('#image').val()+"\",\"hometown\":\""+$('#hometown').val()+"\"}";
		    var json_header="{\"name\":\"register\",\"body\":"+json_body+"}";


				$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response_logout)
                 {
				    $('#req_btn_form_registration').html(json_header);
				    $('#res_btn_form_registration').html(response_logout);
				  }
				});
		 });

         /****************************************************************/
  		/********************** Get All Photos ****************************/
  		/****************************************************************/

            $('#btn_form_allphotos').click(function(){
		 	var json_body="{\"start\":\""+$('#start_record').val()+"\",\"total_record\":\""+$('#total_records').val()+"\",\"searchText\":\""+$('#search_text').val()+"\",\"user_id\":\""+$('#get_photo_user_id').val()+"\"}";
		    var json_header="{\"name\":\"GetAllPhotos\",\"body\":"+json_body+"}";

				$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response)
                  {
				    $('#req_btn_form_allphotos').html(json_header);
				    $('#res_btn_form_allphotos').html(response);
				  }
				});

		 });

          /****************************************************************/

		/****************************************************************/
  		/******************** Add device token ****************************/
  		/****************************************************************/

            $('#btn_form_addtoken').click(function(){
		 	var json_body="{\"token\":\""+$('#devicetoken').val()+"\",\"user_id\":\""+$('#user_id').val()+"\"}"
		    var json_header="{\"name\":\"AddAccessToken\",\"body\":"+json_body+"}";

				$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response)
                  {
				    $('#req_btn_form_addtoken').html(json_header);
				    $('#res_btn_form_addtoken').html(response);
				  }
				});
		 });
		 /****************************************************************/


		 /****************************************************************/

		/****************************************************************/
  		/******************** Updatedevicetoken ****************************/
  		/****************************************************************/

            $('#btn_form_updatedevicetoken').click(function(){
		 	var json_body="{\"token\":\""+$('#device_token').val()+"\",\"badge\":\""+$('#badge_number').val()+"\"}"
		    var json_header="{\"name\":\"UpdateAccessToken\",\"body\":"+json_body+"}";

				$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response)
                  {
				    $('#req_btn_form_updatedevicetoken').html(json_header);
				    $('#res_btn_form_updatedevicetoken').html(response);
				  }
				});
		 });



		 /****************************************************************/


		/****************************************************************/
  		/********************** GetUserProfile ****************************/
  		/****************************************************************/

            $('#btn_form_GetUserProfile').click(function(){
			var json_body="{\"userid\":\""+$('#userid').val()+"\"}";
		 	var json_header="{\"name\":\"GetUserProfile\",\"body\":"+json_body+"}";

				$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response)
                  {
				    $('#req_btn_form_GetUserProfile').html(json_header);
				    $('#res_btn_form_GetUserProfile').html(response);
				  }
				});
		 });

		 /****************************************************************/



         /****************************************************************/
  		/********************** updateUserProfile ****************************/



		/****************************************************************/
  		/********************** updateUserProfile ****************************/

  		/****************************************************************/

            $('#btn_form_updateUserProfile').click(function(){
			var json_body="{\"userid\":\""+$('#update_user_id').val()+"\",\"username\":\""+$('#username').val()+"\",\"profile_image\":\""+$('#profile_image').val()+"\",\"last_name\":\""+$('#last_name').val()+"\",\"first_name\":\""+$('#first_name').val()+"\",\"hometown\":\""+$('#update_hometown').val()+"\"}";
		 	var json_header="{\"name\":\"updateUserProfile\",\"body\":"+json_body+"}";

				$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response)
                  {
				    $('#req_btn_form_updateUserProfile').html(json_header);
				    $('#res_btn_form_updateUserProfile').html(response);
				  }
				});
		 });

         	/****************************************************************/
  		/********************** Addlike ****************************/
  		/****************************************************************/

            $('#btn_form_Addlike').click(function(){
			var json_body="{\"Uid\":\""+$('#Addlike_user_id').val()+"\",\"Pid\":\""+$('#Addlike_photo_id').val()+"\"}";
		 	var json_header="{\"name\":\"Addlike\",\"body\":"+json_body+"}";

				$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response)
                  {
				    $('#req_btn_form_Addlike').html(json_header);
				    $('#res_btn_form_Addlike').html(response);
				  }
				});
		 });

         	/****************************************************************/
  		/********************** Get Photo Comments ****************************/
  		/****************************************************************/

            $('#btn_form_comment_list').click(function(){
			var json_body="{\"Pid\":\""+$('#comment_list_photo_id').val()+"\"}";
		 	var json_header="{\"name\":\"GetPhotoComments\",\"body\":"+json_body+"}";

				$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response)
                  {
				    $('#req_btn_form_comment_list').html(json_header);
				    $('#res_btn_form_comment_list').html(response);
				  }
				});
		 });

		 /****************************************************************/
         /****************************************************************/
  		/**********************Update Photo In Application ****************************/
  		/****************************************************************/


            $('#btn_form_update_photo').click(function(){
			var json_body="{\"Pid\":\""+$('#update_photo_photo_id').val()+"\",\"Caption\":\""+$('#update_photo_caption_name').val()+"\",\"ImageData\":\""+$('#update_photo_profile_image').val()+"\",\"Tag\":\""+$('#update_photo_tag').val()+"\"}";
		 	var json_header="{\"name\":\"updatePhoto\",\"body\":"+json_body+"}";
           
				$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response)
                  {
				    $('#req_btn_form_update_photo').html(json_header);
				    $('#res_btn_form_update_photo').html(response);
				  }
				});
		 });

         /****************************************************************/
  		/********************** Add Comment ****************************/
  		/****************************************************************/

            $('#btn_form_add_comment').click(function(){
		 	var json_body="{\"photoId\":\""+$('#photo_id').val()+"\",\"userId\":\""+$('#login_user_id').val()+"\",\"commentText\":\""+$('#comment_text').val()+"\"}";
		    var json_header="{\"name\":\"AddComment\",\"body\":"+json_body+"}";
				$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response)
                  {
				    $('#req_btn_form_add_comment').html(json_header);
				    $('#res_btn_form_add_comment').html(response);
				  }
				});
		 });


          /****************************************************************/
  		/********************** Flag Comment ****************************/
  		/****************************************************************/

        $('#btn_form_flag_comment').click(function(){
	 	var json_body="{\"commentId\":\""+$('#comment_id').val()+"\",\"createduserId\":\""+$('#created_user_id').val()+"\",\"loginUserId\":\""+$('#logged_in_user_id').val()+"\"}";
		    var json_header="{\"name\":\"FlagComment\",\"body\":"+json_body+"}";

				$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response)
                  {
				    $('#req_btn_form_flag_comment').html(json_header);

				    $('#res_btn_form_flag_comment').html(response);

				  }
				});
		 });


		 /****************************************************************/



         /****************************************************************/
  		/********************** Flag Photo ****************************/
  		/****************************************************************/

        $('#btn_form_flag_photo').click(function(){
	 	var json_body="{\"photoId\":\""+$('#photos_id').val()+"\",\"createduserId\":\""+$('#createduser_id').val()+"\",\"loginUserId\":\""+$('#loggedin_user_id').val()+"\"}";
		    var json_header="{\"name\":\"FlagPhoto\",\"body\":"+json_body+"}";

				$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response)
                  {
				    $('#req_btn_form_flag_photo').html(json_header);
				    $('#res_btn_form_flag_photo').html(response);

				  }
				});
		 });

          /********************** Photo Detail Web Service ****************************/
  		/****************************************************************/

        $('#btn_form_photo_detail').click(function(){
	 	var json_body="{\"photoId\":\""+$('#p_id').val()+"\"}";
		    var json_header="{\"name\":\"GetPhotoDetail\",\"body\":"+json_body+"}";

				$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response)
                  {
				    $('#req_btn_form_photo_detail').html(json_header);
				    $('#res_btn_form_photo_detail').html(response);

				  }
				});
		 });


		 /****************************************************************/
         
         
        /****************************************************************/
  		/********************** Add New Photo ****************************/
  		/****************************************************************/

            $('#btn_form_addphotos').click(function(){
			var json_body="{\"userId\":\""+$('#loginUserid').val()+"\",\"captionText\":\""+$('#caption_text').val()+"\",\"photoBase64String\":\""+$('#image_text').val()+"\",\"tags\":\""+$('#tags').val()+"\"}";
		 	var json_header="{\"name\":\"AddPhoto\",\"body\":"+json_body+"}";

				$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response)
                  {
				    $('#req_btn_form_addphotos').html(json_header);
				    $('#res_btn_form_addphotos').html(response);
				  }
				});
		 });

		 /****************************************************************/
         
         
         /****************************************************************/
  		/********************** List Of User's Advertisement ****************************/
  		/****************************************************************/
    
            $('#btn_form_useradvertisement').click(function(){
		 	var json_body="{\"start\":\""+$('#useradvertisement_start_record').val()+"\",\"total_record\":\""+$('#useradvertisement_total_records').val()+"\",\"user_id\":\""+$('#useradvertisement_user_id').val()+"\"}";
		    var json_header="{\"name\":\"ListOfUserAdvertisement\",\"body\":"+json_body+"}";
            
			
				$.ajax({
				  type: 'POST',
				  url: 'webservice.php',
				  data: "json="+json_header,
				  success: function(response)
                  {
				    $('#req_btn_form_useradvertisement').html(json_header);
				    $('#res_btn_form_useradvertisement').html(response);
				  }
				});
		 });

		 /****************************************************************/


	});


</script>

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
	min-height: 699px;
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
	<div class="mainContainer">
		<div class="fl w500 box">

			<form name="frm_fb_registration" id="frm_fb_registration">
				<div class="header">FB Registration</div>
				<div class="cont">
					<div style="font-weight: bold">First Name</div>
                   <input type="text" id="fname"  name="fname" />

                   <div style="font-weight: bold">Last Name</div>
                   <input type="text" id="lname"  name="lname" />

                   <div style="font-weight: bold">User EmailAddress</div>
                   <input type="text" id="emailid"  name="emailid" />

                   <div style="font-weight: bold">User birthDate</div>
                   <input type="text" id="bdate"  name="bdate" />

                   <div style="font-weight: bold">Sex</div>
                   <select name="gender" id="gender">
                   <option value="M">Male</option>
                   <option value="F">Female</option>
                   </select>
                   
                   <div style="font-weight: bold">Home Town</div>
                   <input type="text" id="hometown"  name="hometown" />

                   <div style="font-weight: bold">User Profile Image</div>
                   <input type="text" id="image"  name="image" />

					<div class="reqcap">Request</div>
					<textarea class="reqT" id="req_btn_form_registration" name="req_btn_form_registration" rows="5" cols="58"></textarea>
					<div class="rescap">Response</div>
					<textarea class="resT" id="res_btn_form_registration" name="res_btn_form_registration" rows="5" cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btn_form_fb_registration" name="btn_form_fb_registration" value="Execute" />
					</div>
				</div>
			</form>
		</div>

		<!------------------------------------------------------------------------------------------------------------------------------------------>
		<div class="fl w500 box">
			<div class="header">Get All Photos</div>
			<div class="cont">
				<div class="cont">
					<div>Start</div>
					<div><input type="text" id="start_record" name="start_record" value="" /></div>
					<div>Total record</div>
					<div><input type="text" id="total_records" name="total_records" value="" /></div>
                    
                    <div style="font-weight: bold">User id</div>
					<div><input type="text" id="get_photo_user_id" name="get_photo_user_id" /></div>
                    
                    <div>Search Text</div>
					<div><input type="text" id="search_text" name="search_text" value="" /></div>
                    
                    
                    

					<div class="reqcap">Request</div>
					<textarea class="reqT" id="req_btn_form_allphotos" rows="5" cols="58"></textarea>
					<div class="rescap">Response</div>
					<textarea class="resT" id="res_btn_form_allphotos" name="res_btn_form_allphotos" rows="5" cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btn_form_allphotos" name="btn_form_allphotos" value="Execute" />
					</div>
				</div>
			</div>
		</div>

           <!------------------------------------------------------------------------------------------------------------------------------------------>
		<div class="fl w500 box">
			<div class="header">Photo Detail Web Service</div>
			<div class="cont">
				<div class="cont">
					<div>Photo ID</div>
					<div><input type="text" id="p_id" name="p_id" value="" /></div>

					<div class="reqcap">Request</div>
					<textarea class="reqT" id="req_btn_form_photo_detail" name="req_btn_form_photo_detail" rows="5" cols="58"></textarea>
					<div class="rescap">Response</div>
					<textarea class="resT" id="res_btn_form_photo_detail" name="res_btn_form_flag_photo" rows="5" cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btn_form_photo_detail" name="btn_form_photo_detail" value="Execute" />
					</div>
				</div>
			</div>
		</div>

		<!------------------------------------------------------------------------------------------------------------------------------------------>

    	<div class="fl w500 box">

			<form name="form_comment_list" id="form_comment_list">
				<div class="header">Get Photo Comments</div>
				<div class="cont">
                    <div style="font-weight: bold">Photo id</div>
					<div><input type="text" id="comment_list_photo_id" name="comment_list_photo_id" /></div>
                   
					<div class="reqcap">Request</div>
					<textarea class="reqT" id="req_btn_form_comment_list" name="req_btn_form_comment_list" rows="5" cols="58"></textarea>
					<div class="rescap">Response</div>
					<textarea class="resT" id="res_btn_form_comment_list" name="res_btn_form_comment_list" rows="5" cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btn_form_comment_list" name="btn_form_comment_list" value="Execute" />
					</div>
				</div>
			</form>
		</div>

        	<!------------------------------------------------------------------------------------------------------------------------------------------>


		<div class="fl w500 box">
			<div class="header">Flag Photo</div>
			<div class="cont">
				<div class="cont">
					<div>Photo id</div>
					<div><input type="text" id="photos_id" name="photos_id" value="" /></div>
					<div>Created userID</div>
					<div><input type="text" id="createduser_id" name="createduser_id" value="" /></div>
                    <div>Login UserID</div>
					<div><input type="text" id="loggedin_user_id" name="loggedin_user_id" value="" /></div>

					<div class="reqcap">Request</div>
					<textarea class="reqT" id="req_btn_form_flag_photo" name="req_btn_form_flag_photo" rows="5" cols="58"></textarea>
					<div class="rescap">Response</div>
					<textarea class="resT" id="res_btn_form_flag_photo" name="res_btn_form_flag_photo" rows="5" cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btn_form_flag_photo" name="btn_form_flag_photo" value="Execute" />
					</div>
				</div>
			</div>
		</div>

        <!------------------------------------------------------------------------------------------------------------------------------------------>

        	<div class="fl w500 box">
			<div class="header">Flag Comment</div>
			<div class="cont">
				<div class="cont">
					<div>Comment id</div>
					<div><input type="text" id="comment_id" name="comment_id" value="" /></div>
					<div>Created userID</div>
					<div><input type="text" id="created_user_id" name="created_user_id" value="" /></div>
                    <div>Login UserID</div>
					<div><input type="text" id="logged_in_user_id" name="logged_in_user_id" value="" /></div>

					<div class="reqcap">Request</div>
					<textarea class="reqT" id="req_btn_form_flag_comment" name="req_btn_form_flag_comment" rows="5" cols="58"></textarea>
					<div class="rescap">Response</div>
					<textarea class="resT" id="res_btn_form_flag_comment" name="res_btn_form_flag_comment" rows="5" cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btn_form_flag_comment" name="btn_form_flag_comment" value="Execute" />
					</div>
				</div>
			</div>
		</div>
		<!------------------------------------------------------------------------------------------------------------------------------------------>

        	<div class="fl w500 box">

			<form name="form_GetUserProfile" id="form_GetUserProfile">
				<div class="header">GetUserProfile</div>
				<div class="cont">
					<div style="font-weight: bold">UserID</div>
					<div><input type="text" id="userid" name="userid" /></div>

					<div class="reqcap">Request</div>
					<textarea class="reqT" id="req_btn_form_GetUserProfile" name="req_btn_form_GetUserProfile" rows="5" cols="58"></textarea>
					<div class="rescap">Response</div>
					<textarea class="resT" id="res_btn_form_GetUserProfile" name="res_btn_form_GetUserProfile" rows="5" cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btn_form_GetUserProfile" name="btn_form_GetUserProfile" value="Execute" />
					</div>
				</div>
			</form>
		</div>


<!------------------------------------------------------------------------------------------------------------------------------------------>
		<div class="fl w500 box">


			<form name="form_updateUserProfile" id="form_updateUserProfile">
				<div class="header">updateUserProfile</div>
				<div class="cont">
					<div style="font-weight: bold">Login UserId</div>
					<div><input type="text" id="update_user_id" name="update_user_id" value="" /></div>

                    <div style="font-weight: bold">UserName</div>
					<div><input type="text" id="username" name="username" value="" /></div>

                    <div style="font-weight: bold">Profile Image</div>
					<div><input type="text" id="profile_image" name="profile_image" value="" /></div>
                    
                     <div style="font-weight: bold">First Name</div>
					<div><input type="text" id="first_name" name="first_name" value="" /></div>
                    
                     <div style="font-weight: bold">Last Name</div>
					<div><input type="text" id="last_name" name="last_name" value="" /></div>
                    
                     <div style="font-weight: bold">Home Town</div>
					<div><input type="text" id="update_hometown" name="update_hometown" value="" /></div>

					<div class="reqcap">Request</div>
					<textarea class="reqT" id="req_btn_form_updateUserProfile" name="req_btn_form_updateUserProfile" rows="5" cols="58"></textarea>
					<div class="rescap">Response</div>
					<textarea class="resT" id="res_btn_form_updateUserProfile" name="res_btn_form_updateUserProfile" rows="5" cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btn_form_updateUserProfile" name="btn_form_updateUserProfile" value="Execute" />
					</div>
				</div>
			</form>

		</div>

	  	<!------------------------------------------------------------------------------------------------------------------------------------------>

          <div class="fl w500 box">
			<div class="header">Add New Photo</div>
			<div class="cont">
				<div class="cont">
					<div>User Id</div>
					<div><input type="text" id="loginUserid" name="loginUserid" value="" /></div>
					<div>CaptionText On Image</div>
					<div><input type="text" id="caption_text" name="caption_text" value="" /></div>
                    <div>ImageData In Base64</div>
					<div><input type="text" id="image_text" name="image_text" value="" /></div>
                    <div>Tags</div>
					<div><input type="text" id="tags" name="tags" value="" /></div>
					<div class="reqcap">Request</div>
					<textarea class="reqT" id="req_btn_form_addphotos" rows="5" cols="58"></textarea>
					<div class="rescap">Response</div>
					<textarea class="resT" id="res_btn_form_addphotos" name="res_btn_form_addphotos" rows="5" cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btn_form_addphotos" name="btn_form_addphotos" value="Execute" />
					</div>
				</div>
			</div>
		</div>

         	<!------------------------------------------------------------------------------------------------------------------------------------------>

            <div class="fl w500 box">


			<form name="form_update_photo" id="form_update_photo">
				<div class="header">Update Photo</div>
				<div class="cont">
                    <div style="font-weight: bold">Photo id</div>
					<div><input type="text" id="update_photo_photo_id" name="update_photo_photo_id" /></div>
                    <div style="font-weight: bold">Caption Text on Image</div>
					<div><input type="text" id="update_photo_caption_name" name="update_photo_caption_name" /></div>
                    <div style="font-weight: bold">Profile Image</div>
					<div><input type="text" id="update_photo_profile_image" name="update_photo_profile_image" value="" /></div>
                    <div style="font-weight: bold">Tags</div>
					<div><input type="text" id="update_photo_tag" name="update_photo_tag" /></div>

					<div class="reqcap">Request</div>
					<textarea class="reqT" id="req_btn_form_update_photo" name="req_btn_form_update_photo" rows="5" cols="58"></textarea>
					<div class="rescap">Response</div>
					<textarea class="resT" id="res_btn_form_update_photo" name="res_btn_form_update_photo" rows="5" cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btn_form_update_photo" name="btn_form_update_photo" value="Execute" />
					</div>
				</div>
			</form>
		</div>

       <!------------------------------------------------------------------------------------------------------------------------------------------>

        <div class="fl w500 box">

			<form name="form_addtoken" id="form_addtoken">
				<div class="header">Add Token</div>
				<div class="cont">

					<div style="font-weight: bold">Device Token</div>
					<div><input type="text" id="devicetoken" name="devicetoken" size="50" /></div>
                    
                    <div style="font-weight: bold">User Id</div>
					<div><input type="text" id="user_id" name="user_id" size="50" /></div>
                    

                    <div class="reqcap">Request</div>
					<textarea class="reqT" id="req_btn_form_addtoken" name="req_btn_form_addtoken" rows="5" cols="58"></textarea>

					<div class="rescap">Response</div>
					<textarea class="resT" id="res_btn_form_addtoken" name="res_btn_form_addtoken" rows="5" cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btn_form_addtoken" name="btn_form_addtoken" value="Execute" />
					</div>
				</div>
			</form>
		</div>


		<!------------------------------------------------------------------------------------------------------------------------------------------>

		<div class="fl w500 box">

			<form name="form_updatedevicetoken" id="form_updatedevicetoken">
				<div class="header">Updatedevicetoken</div>
				<div class="cont">
					<div style="font-weight: bold">Device Token</div>
					<div><input type="text" id="device_token" name="device_token" /></div>

                    <div style="font-weight: bold">Badge Number</div>
					<div><input type="text" id="badge_number" name="badge_number" /></div>

					<div class="reqcap">Request</div>
					<textarea class="reqT" id="req_btn_form_updatedevicetoken" name="req_btn_form_updatedevicetoken" rows="5" cols="58"></textarea>
					<div class="rescap">Response</div>
					<textarea class="resT" id="res_btn_form_updatedevicetoken" name="res_btn_form_updatedevicetoken" rows="5" cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btn_form_updatedevicetoken" name="btn_form_updatedevicetoken" value="Execute" />
					</div>
				</div>
			</form>
		</div>


		<!------------------------------------------------------------------------------------------------------------------------------------------>

        	<div class="fl w500 box">
			<div class="header">Add Comment</div>
			<div class="cont">
				<div class="cont">
					<div>Photo id</div>
					<div><input type="text" id="photo_id" name="photo_id" value="" /></div>
					<div>Login user ID</div>
					<div><input type="text" id="login_user_id" name="login_user_id" value="" /></div>
                    <div>Comment text</div>
					<!--<div><input type="text" id="comment_text" name="comment_text" value="" /></div>-->
					<div><textarea rows="7" cols="30" id="comment_text" name="comment_text" ></textarea></div>

					<div class="reqcap">Request</div>
					<textarea class="reqT" id="req_btn_form_add_comment" name="req_btn_form_add_comment" rows="5" cols="58"></textarea>
					<div class="rescap">Response</div>
					<textarea class="resT" id="res_btn_form_add_comment" name="res_btn_form_add_comment" rows="5" cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btn_form_add_comment" name="btn_form_add_comment" value="Execute" />
					</div>
				</div>
			</div>
		</div>

<!------------------------------------------------------------------------------------------------------------------------------------------>
        	<div class="fl w500 box">

			<form name="form_Addlike" id="form_Addlike">
				<div class="header">Add Like</div>
				<div class="cont">
                    <div style="font-weight: bold">Photo id</div>
					<div><input type="text" id="Addlike_photo_id" name="Addlike_photo_id" /></div>
					<div style="font-weight: bold">User id</div>
					<div><input type="text" id="Addlike_user_id" name="Addlike_user_id" /></div>

					<div class="reqcap">Request</div>
					<textarea class="reqT" id="req_btn_form_Addlike" name="req_btn_form_Addlike" rows="5" cols="58"></textarea>
					<div class="rescap">Response</div>
					<textarea class="resT" id="res_btn_form_Addlike" name="res_btn_form_Addlike" rows="5" cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btn_form_Addlike" name="btn_form_Addlike" value="Execute" />
					</div>
				</div>
			</form>
		</div>
        
        
        <!------------------------------------------------------------------------------------------------------------------------------------------>
        	<div class="fl w500 box">

			<form name="form_useradvertisement" id="form_useradvertisement">
				<div class="header">List Of User's Advertisement</div>
				<div class="cont">
                    
					<div style="font-weight: bold">User id</div>
					<div><input type="text" id="useradvertisement_user_id" name="useradvertisement_user_id" /></div>
                    
                    <div>Start</div>
					<div><input type="text" id="useradvertisement_start_record" name="useradvertisement_start_record" value="" /></div>
					<div>Total record</div>
					<div><input type="text" id="useradvertisement_total_records" name="useradvertisement_total_records" value="" /></div>

					<div class="reqcap">Request</div>
					<textarea class="reqT" id="req_btn_form_useradvertisement" name="req_btn_form_useradvertisement" rows="5" cols="58"></textarea>
					<div class="rescap">Response</div>
					<textarea class="resT" id="res_btn_form_useradvertisement" name="res_btn_form_useradvertisement" rows="5" cols="58"></textarea>
					<div class="btn">
						<input type="button" id="btn_form_useradvertisement" name="btn_form_useradvertisement" value="Execute" />
					</div>
				</div>
			</form>
		</div>
      
    </div>

	<div class="clear"></div>


	<br />
	<br />
</body>
</html>

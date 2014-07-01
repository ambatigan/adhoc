<div class="login-form-box">
      <div class="login-header">
      	<!-- <a href="#"><img src="../images/theme_images/logo.png" alt="Logo" /></a> -->
        <h2>Aviary IOS App</h2>
      </div>
      <div class="login-form-content">
      	<h2>Administration Login</h2>
         <div style=" text-align: center;">
            <?php if (!empty($errorMsg)) {
					foreach ($errorMsg as $k => $d) { ?>
					    <font color="red" style="text-align: center;"><br /><?php echo $d ?><br /></font>
					<?php }
				} ?>
           </div>
          <form method="post" action="" name="login_form" id="login_form">
          <input name="LoginFormSubmit" type="hidden" value="1" />
            <div class="login-form">
             	<div class="login-form-field" title="Username">
               	<label>Username <span class="mandatory">*</span></label>
                  <input type="text" name="user_name" id="user_name" value="" maxlength="20" />
              </div>
              <div class="login-form-field" title="Password">
              	<label>Password <span class="mandatory">*</span></label>
                  <input type="password" name="password" id="password" value="" maxlength="20" />
              </div>
          </div>
          <div class="login-bot clearfix">
          	<!--<div class="forgot-psw"><a href="#" title="Forgot Password?">Forgot Password?</a></div>-->
              <div class="form-submit"><input type="submit" title="Login" value="Login" /></div>
          </div>
        </form>
    </div>
</div>

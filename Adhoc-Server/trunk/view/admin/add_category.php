<script>
    function cancelForm(val){
        var url = '<?php echo ADMIN_URL; ?>'+val;
        window.location = url;
    }
    // error-messages hide shows
    $(document).ready(function(){
        $(".close").click(function(){
            $(".alert-error").hide();
        });
    });
</script>
            <form name="category" id="category" method="post">
            <div class="main-content">
                    <div class="main-content-top">
                        	<div class="breadcrumb">
                            	<ul class="clearfix">
                                	<li><a href="." title="Home">Home</a><img src="../images/theme_images/bcarrow.png" alt="arrow" /></li>
                                    <li><a href="?page=category_list" title="<?php echo $heading;?>"><?php echo $heading;?></a><img src="../images/theme_images/bcarrow.png" alt="arrow" /></li>
                                    <li><?php echo $title;?></li>
                                </ul>
                            </div>
            				<div class="page-title"><h1><?php echo $title;?></h1></div>
                        </div>

                            <?php if (count($errorMsg)>0){?>
                            <div class="msg-content-box">
                                <div class="alert alert-error">
                                    <button type="button" class="close" data-dismiss="alert">x</button>
                                    <?php foreach ($errorMsg as $_errorMsg){
                                        echo $_errorMsg."<br />";
                                     }?>
                                </div>
                            </div>
                            <?php }?>

                    <div class="main-container">
                        	<div class="grid-data">
                            	<div class="add-new"><a href="<?php echo  ADMIN_URL ?>?page=category_list" title="View All Categories">View All Categories</a></div>
                                <table cellspacing="1" cellpadding="4" border="0" bgcolor="#e6ecf2" width="100%">
                                	<tbody bgcolor="#fff">
                                    	<tr>
                                        	<th><?php echo $title;?></th>
                                        </tr>
                                        <tr>
                                        	<td class="add-user-form-box">
                                            	<table width="100%" cellpadding="5" cellspacing="1" border="0">
                                                  <tr>
                                                    <td align="right"><span class="star">*&nbsp;</span>Name:</td>
                                                    <td><input type="text" value="<?php echo htmlspecialchars($cms[name]) ?>" class="validate[required]" name="name" style="width:198px;" tabindex="1" maxlength="35" ></td>
                                                   </tr>
                                                   <tr>
                                                    <td width="200" align="right"><span class="star">*&nbsp;</span>Grams/100ml:</td>
                                                    <td><input type="text" value="<?php echo htmlspecialchars($cms[grms_100ml]) ?>" class="validate[required]" name="grms_100ml" style="width:198px;" tabindex="1" maxlength="35" ></td>
                                                   </tr>
                                                  	<tr>
                                                        <td align="right">Status :</td>
                                                        <td>
                                                           <select id="status" name="status" style="width:208px;" tabindex="2">
                                                               <option <?php echo ($objCategory->status == 'A')?'selected="selected"':''?> value="A">Active</option>
                                                               <option <?php echo ($objCategory->status == 'I')?'selected="selected"':''?> value="I">Inactive</option>
                                                           </select>
                                                    	</td>
                                                    </tr>
                                                    
                                                </table>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="submit-btns clearfix">
                            	<input type="submit" value="Submit" title="Submit" class="inputbutton" name="blogFormSubmit" >
                                <input type="button" value="Cancel" title="Cancel" class="inputbutton" name="cancel" onclick="javascript:cancelForm('?page=category_list&amp;action=view')">
                            </div>
                        </div>
    </div>
    </form>
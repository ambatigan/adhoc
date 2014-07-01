<script>
    function cancelForm(val){
        var url = '<?php echo ADMIN_URL; ?>'+val;
        window.location = url;
    }
</script>
<script>  
    
    // error-messages hide shows
    $(document).ready(function(){
        $(".close").click(function(){

            $(".alert-success").hide();
            $(".alert-error").hide();
        });
    });
</script>
<div class="main-content">
    <div class="main-content-top">
        <div class="breadcrumb">
            <ul class="clearfix">
                <li><a href="<?php echo ADMIN_URL; ?>" title="Home">Home</a><img src="../images/theme_images/bcarrow.png" alt="arrow" /></li>
                <li><?php echo $heading; ?></li>

            </ul>
        </div>
        <div class="page-title"><h1><?php echo $title; ?></h1></div>
    </div>
    <form method="post" id="pushNotifyFrm" name="pushNotifyFrm" action="">

        <div class="main-container">

            <div class="msg-content-box">
                <?php if (count($errorMsg) > 0) { ?>
                    <div class="alert alert-error">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <?php
                        foreach ($errorMsg as $_errorMsg) {
                            echo $_errorMsg . '<br/>';
                        }
                        ?>
                    </div>
                <?php } ?>


                <?php if (count($successMsg) > 0) { ?>
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <?php
                        foreach ($successMsg as $successMsg) {
                            echo $successMsg . '<br/>';
                        }
                        ?>
                    </div>
                <?php } ?>
            </div>

            <div class="grid-data">
<!--                <div class="add-new"><a href="<?php echo  ADMIN_URL ?>?page=category_list" title="Add New">View All Categories</a></div>-->
                <table cellspacing="1" cellpadding="4" border="0" bgcolor="#e6ecf2" width="100%">
                    <tbody bgcolor="#fff">
                        <tr>
                            <th><?php echo $title; ?></th>
                        </tr>
                        <tr>
                            <td class="add-user-form-box">

                                <table width="100%" cellpadding="5" cellspacing="1" border="0">
                                    <tr>
                                        <td align="right" valign="top"><span class="star">*&nbsp;</span>Notification Message :</td>
                                        <td><textarea value="" name="notfication-message" id="notfication-message" class="validate[required]" style="width:198px;" ></textarea></td>
                                    </tr>
                                </table>

                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="submit-btns clearfix">
                <input type="submit" value="Notify Users" title="Notify Users" class="inputbutton" name="blogFormSubmit">
<!--                <input type="button" value="Cancel" title="Cancel" class="inputbutton" name="cancel" onclick="javascript:cancelForm('?page=push-notification&amp;action=view')">-->
            </div>
        </div>
    </form>
</div>
</form>
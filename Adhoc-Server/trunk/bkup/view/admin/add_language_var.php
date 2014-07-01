<script type="text/javascript" src="<?php echo CK_EDITOR_URL; ?>ckeditor.js"></script>
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

            $(".alert-error").hide();
        });
    });

</script>
<form name="add_language_var" id="add_language_var" method="post">
    <div class="main-content">
        <div class="main-content-top">
            <div class="breadcrumb">
                <ul class="clearfix">
                    <li><a href="<?php echo ADMIN_URL; ?>" title="Home">Home</a><img src="../images/theme_images/bcarrow.png" alt="arrow" /></li>
                    <li><a href="?page=language_var_list" title="Language Variable Management"><?php echo $heading; ?></a><img src="../images/theme_images/bcarrow.png" alt="arrow" /></li>
                    <li><?php echo $title; ?></li>
                </ul>
            </div>
            <div class="page-title"><h1><?php echo $title; ?></h1></div>
        </div>

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

        <div class="main-container">
            <div class="grid-data">
                <div class="add-new"><a href="<?= ADMIN_URL ?>?page=language_var_list" title="View All Language Var">View All Language Variable</a></div>
                <table cellspacing="1" cellpadding="4" border="0" bgcolor="#e6ecf2" width="100%">
                    <tbody bgcolor="#fff">
                        <tr>
                            <th><?php echo $title; ?></th>
                        </tr>
                        <tr>
                            <td class="add-user-form-box">
                                <table width="100%" cellpadding="5" cellspacing="1" border="0">
                                    <tr>
                                        <td align="right"><span class="star">*&nbsp;</span>Language Variable Name:</td>
                                        <td>
<!--                                            <b><?= $obj_language_var['name']; ?></b>-->
                                            <input type="text" value="<?= $obj_language_var['name']; ?>" class="validate[required]" name="name" style="width:198px;" tabindex="1" >
<!--                                            <input type="hidden" value="<?= $obj_language_var['name']; ?>" class="validate[required]" name="name" style="width:198px;" tabindex="1" >-->
                                        </td>

                                    </tr>

                                    <tr>
                                        <td colspan="2">
                                            <div class="tab-nav">
                                                <ul class="tab-headings clearfix">
                                                    <?php foreach ($sitelanguages as $lng) { ?>
                                                        <li><a href="Javascript:void(0);" rel="#<?php echo 'country3' . $lng['id']; ?>" class="selected"><?php echo $lng['language_name']; ?></a></li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                            <?php
                                            $tabindex = 3;
                                            foreach ($sitelanguages as $lng) {
                                                ?>
                                                <div id="<?php echo 'country3' . $lng['id']; ?>" class="tabcontent" style="display: block;">
                                                    <table cellpadding="5" cellspacing="1" border="0">


                                                        <tr>
                                                            <td align="right" valign="top"><span class="star">*&nbsp;</span>Language Text:</td>
                                                            <td><textarea cols="60" rows="5" class="validate[required]" name="txt[<?php echo $lng[id] ?>]" id="txt[<?php echo $lng[id] ?>]"><?php echo $obj_language_var['txt'][$lng['id']]; ?></textarea></td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            <? } ?>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="submit-btns clearfix">
                <input type="submit" value="Submit" title="Submit" class="inputbutton" name="LanguageVarFormSubmit">
                <input type="button" value="Cancel" title="Cancel" class="inputbutton" name="cancel" onclick="javascript:cancelForm('?page=language_var_list&amp;action=view')">
            </div>
        </div>
    </div>
</form>
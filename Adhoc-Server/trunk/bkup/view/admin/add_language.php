<script type="text/javascript">
function setDefault(v){
	if(v ==true)
		document.getElementById('defaultlanguage').value = '1';
	else
		document.getElementById('defaultlanguage').value = '0';
}
</script>
<form name="add_language" id="add_language" method="post" enctype="multipart/form-data">
   	<input id="defaultlanguage" type="hidden" name="defaultlanguage" value="<?if($objlanguage['set_default'] == 1) echo "1"; else echo "0";?>"/>
    <table width="100%" border="0" cellpadding="0" cellspacing="0" >
        <tr>
            <td>
                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                    <tr>
                      <td colspan="2" class="admtitlebg">
                      <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                          <td class="admtitle"><? echo $title;?></td>
                          <td align="right"><a href="./?page=language_list&action=view" class="admtitlelink" title="<?php echo index_lang::$viewlanguage; ?>"><?php echo index_lang::$viewlanguage; ?></a></td>
                      </tr>
                      </table>
                      </td>
                  </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="height:5px;"></td>
        </tr>
        <tr>
            <td valign="top">
    <table width="100%" cellspacing="4" cellpadding="2" border="0" class="tablebgcolor">
		<?php if(!empty($errorMsg)) {
		foreach($errorMsg as $k=>$d) { ?>
		<tr>
			<td align="left" colspan="2" class="redlabel"><?php echo $d ?></td>
		</tr>
		<?php }} ?>
		<?php if(!empty($successMsg)) {
			foreach($successMsg as $k=>$d) { ?>
		<tr>
			<td align="left" colspan="2" class="greenlabel"><?php echo $d ?></td>
		</tr>
		<?php }} ?>
        <tr>
            <td width="150" align="right"><span class="star">*&nbsp;</span><strong><?php echo index_lang::translate("Language Name");?> :</strong></td>
            <td>
                <input class="input validate[required]" id="language_name" name="language_name" tabindex="1" value="<?=$objlanguage['language_name']?>" maxlength="50" />
            </td>
        </tr>
		<tr>
            <td align="right"><span class="star">*&nbsp;</span><strong><?php echo index_lang::translate("Language Code");?> :</strong></td>
            <td>
                <input class="input validate[required]" id="lng_code" name="lng_code" tabindex="2" value="<?=$objlanguage['lng_code']?>" maxlength="2"/>
            </td>
        </tr>
        <tr>
            <td align="right"><span class="star">*&nbsp;</span><strong><?php echo index_lang::translate("Status");?> :</strong></td>
            <td>
                <select id="status" name="status" class="input validate[required] styled" tabindex="3">
							<option value=""><?php echo index_lang::translate("Select");?></option>
							<option value="1" <?php if(htmlspecialchars($objlanguage[status])==1) { ?> selected="selected" <?php } ?>><?php echo index_lang::translate("Active");?></option>
							<option value="2" <?php if(htmlspecialchars($objlanguage[status])==2) { ?> selected="selected" <?php } ?>><?php echo index_lang::translate("Inactive");?></option>
						</select>
            </td>
        </tr>
        <tr>
            <td align="right"><strong><?php echo index_lang::translate("Set Default");?> :</strong></td>
            <td>
                <input type="checkbox" onclick="setDefault(this.checked)" id="set_default" tabindex="4" <?if($objlanguage['set_default'] == 1){?>checked="checked" disabled="disabled"<?}?> name="set_default" value="1"/>
            </td>
        </tr>
		<tr>
			<td></td>
    		<td class="campos2" valign="top"><input name="LanguageFormSubmit" id="LanguageFormSubmit" tabindex="5" type="submit"  class="subbut" value="<?php echo index_lang::translate("Submit");?>" title="<?= index_lang::$submit ?>"  />&nbsp;&nbsp;
    		<input type="button" value="<?php echo index_lang::translate("Cancel");?>" title="<?= index_lang::$cancel ?>" class="subbut" tabindex="6" onclick="javascript:cancelForm('?page=language_list&amp;action=view')" name="btnCancel">
    		</td>
  		</tr>
	</table>
    </td>
  		</tr>
	</table>
</form>
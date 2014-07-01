<table width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td class="admtitlebg"><table cellspacing="0" cellpadding="0" border="0" width="100%">
                <tbody>
                    <tr>
                        <td class="admtitlebg"><?php echo 'Site Settings'; ?></td>
                    </tr>
                </tbody></table></td>
    </tr>
    <?php 
    if (!empty($successMsg)) {
    ?>
        <tr>
            <td width="100%" align="left"  colspan="10" class="greenlabel"><?php echo $successMsg ?></td>
        </tr>
    <?php } ?>
    <?php
    if (!empty($errorMsg)) {
        foreach ($errorMsg as $k => $d) {
    ?>
            <tr>
                <td width="100%" colspan="10" class="redlabel1"><?php echo $d ?></td>
            </tr>
    <?php }
    } ?>    
<tr>
    <td valign="top" class="admcontentpadd">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="gridarea">
            
           
           
            <tr>
                <th width="3%"><?php echo index_lang::$SrNo; ?></th>
                <th width="17%"><?php echo index_lang::$Title; ?></th>
                <th width="42%"><?php echo index_lang::$Description; ?></th>
                <th width="30%">Value</th>
                <th width="8%"><?php echo index_lang::$Title; ?></th>
            </tr>
<?php
$cnt = 0;
foreach ($config_vars as $cnf_vars) {
    $cnt++;
    if ($cnt % 2 == 0) {
        $cls = "nrlbg";
    } else {
        $cls = "altbg";
    }
?>
            <form method="post" name="config_vars" id="config_vars" action="">
                <tr class="<?php echo $cls?>" >
                    <td><b><?php echo $cnt ?></b></td>
                    <td><b><?php echo $cnf_vars['config_var_title'] ?></b></td>
                    <td><?php echo $cnf_vars['config_var_description'] ?></td>
                    <td>
<?php
            if ($cnf_vars['config_var_type'] == '1') {
?>
                        <input type="text" name="config_var_value" id="config_var_value" value="<?php echo  $cnf_vars['config_var_value'] ?>" size="50" />
                        <?php } ?>
                    </td>
                    <td><input type="submit" name="Submit" value="Update" class="inputbutton" /></td>
                </tr>
                <input type="hidden" name="config_var_no" value="<?php echo $cnt?>" />
                <input type="hidden" name="config_var_id" value="<?php echo $cnf_vars['config_var_id'] ?>" />
                <input type="hidden" name="config_var_type" value="<?php echo $cnf_vars['config_var_type'] ?>" />
                <input type="hidden" name="action" value="submit" />
            </form>
<?php } ?>
        </table>
    </td>
</tr>
<?php if (!empty($config_vars)) {
 ?>
            <tr style="border-left: none;">
                <td align="right" class="manu" style="border-left: none;">

<?php echo  "Showing" ?> <?php echo $starting_record ?> <?php echo  "to" ?> <?php echo $ending_record ?> <?php echo  "of" ?> <?php echo $total_records ?>
&nbsp;&nbsp;<?php echo $pagging ?></td>
            </tr>
<?php } ?>
</table>
<div class="wrap">
<div class="usces_admin">
<h1>Welcart Shop <?php _e('Backup','usces'); ?></h1>
<?php usces_admin_action_status(); ?>

<table class="form_table">
	<tr>
		<form action="" method="post" name="option_form" id="option_form">
	    <th width="150"><?php _e('Export', 'usces'); ?></th>
	    <td>
		<input name="usces_export" type="submit" class="button" value="<?php _e('Start Exporting', 'usces'); ?>" />
		</td>
		<td>&nbsp;</td>
		</form>
	</tr>
	<tr>
		<form action="" method="post" enctype="multipart/form-data" name="up_form" id="up_form">
	    <th width="150"><?php _e('Import', 'usces'); ?></th>
	    <td><input name="data" type="file" /></td>
		<td><input name="usces_import" type="submit" class="button" value="<?php _e('Start importing', 'usces'); ?>" /></td>
		</form>
	</tr>
</table>

<div class="chui">
<?php _e('Back-up functions is now under testing.', 'usces'); ?>
</div>
</div><!--usces_admin-->
</div><!--wrap-->
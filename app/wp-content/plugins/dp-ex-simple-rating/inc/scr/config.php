<?php
$def_options = $this->def_options;
$options = $this->get_plugin_setting();
?>
<div class="box-c dp_ex_conf">
<form method="post" action="#" name="dp_form" enctype="multipart/form-data">
<h4 class="dp_ex_conf_toggle_title dp_pointer icon-cog"><?php _e('Plugin Configuration', $this->text_domain); ?></h4>
<div class="dp_ex_slide_panel">
	<hr />
	<table class="dp_table1">
		<tbody>
			<tr>
				<th scope="row"><?php _e('Cookie expired', $this->text_domain); ?></th>
				<td><input type="text" value="<?php echo $options['expiretime']; ?>" name="expiretime" size=5 /> <?php _e('days', $this->text_domain); ?></td>
			</tr>
			<tr>
				<th scope="row"><?php _e('Target display', $this->text_domain); ?></th>
				<td><input type="checkbox"<?php if ((bool)$options['available_post']) echo " checked"; ?> name="available_post" id="available_post" value="true" /><label for="available_post" class="mg15px-r"><?php _e('Single Post', $this->text_domain); ?></label>
					<input type="checkbox"<?php if ((bool)$options['available_page']) echo " checked"; ?> name="available_page" id="available_page" value="true" /><label for="available_page"><?php _e('Single Page', $this->text_domain); ?></label></td>
			</tr>
			<tr>
				<th scope="row"><?php _e('Display position', $this->text_domain); ?></th>
				<td><input type="radio"<?php if ($options['position'] !== "bottom") echo " checked"; ?> name="position" id="position_top" value="top" /><label for="position_top" class="mg15px-r"><?php _e('Top of entry', $this->text_domain); ?></label>
				<input type="radio"<?php if ($options['position'] === "bottom") echo " checked"; ?> name="position" id="position_bottom" value="bottom" /><label for="position_bottom"><?php _e('Bottom of entry', $this->text_domain); ?></label></td>
			</tr>
			<tr>
				<th scope="row"><?php _e('Alignment', $this->text_domain); ?></th>
				<td><select name="alignment" size=1 style="width:160px;">
					<option value="left"<?php if ($options['alignment'] === "left") echo " selected" ?>><?php _e('Left', $this->text_domain); ?></option>
					<option value="center"<?php if ($options['alignment'] === "center") echo " selected" ?>><?php _e('Center', $this->text_domain); ?></option>
					<option value="right"<?php if ($options['alignment'] === "right") echo " selected" ?>><?php _e('Right', $this->text_domain); ?></option>
				</select></td>
			</tr>
			<tr>
				<th scope="row"><?php _e('Bad button', $this->text_domain); ?></th>
				<td><input type="checkbox"<?php if ((bool)$options['show_bad']) echo " checked"; ?> name="show_bad" id="show_bad_btn" value="true" /><label for="show_bad_btn"><?php _e('Show button', $this->text_domain); ?></label></td>
			</tr>
			<tr>
				<th scope="row"><?php _e('Like icon', $this->text_domain); ?></th>
				<td><input type="text" value="<?php echo $options['like_icon']; ?>" name="like_icon" size=20 /> <a href="https://digipress.digi-state.com/manual/icon-font-map/" target="_blank" class="icon-new-tab"><?php _e('Available icons', $this->text_domain); ?></a></td>
			</tr>
			<tr>
				<th scope="row"><?php _e('Bad icon', $this->text_domain); ?></th>
				<td><input type="text" value="<?php echo $options['bad_icon']; ?>" name="bad_icon" size=20 /> <a href="https://digipress.digi-state.com/manual/icon-font-map/" target="_blank" class="icon-new-tab"><?php _e('Available icons', $this->text_domain); ?></a></td>
			</tr>
			<tr>
				<th scope="row"><?php _e('Caption (Support HTML)', $this->text_domain); ?></th>
				<td><input type="text" value="<?php echo $options['rate_caption']; ?>" name="rate_caption" size=50 /></td>
			</tr>
			<tr>
				<th scope="row"><?php _e('Doughnut Chart', $this->text_domain); ?></th>
				<td><input type="checkbox"<?php if ((bool)$options['show_chart']) echo " checked"; ?> name="show_chart" id="show_chart" value="true" /><label for="show_chart" style="margin-right:10px;"><?php _e('Enable', $this->text_domain); ?></label>
					<label><?php _e('Link text',$this->text_domain); ?></label>: <input type="text" value="<?php echo $options['chart_btn_code']; ?>" name="chart_btn_code" size=28 />
					<div class="mg10px-top">
						<label><?php _e('Liked color',$this->text_domain); ?></label>: 
						<input type="text" name="liked_chart_color" value="<?php echo $options['liked_chart_color']; ?>" class="dp-color-field" data-default-color="<?php echo $def_options['liked_chart_color']; ?>" size=7 />
						<label class="mg5px-l"><?php _e('Bad color',$this->text_domain); ?></label>: 
						<input type="text" name="bad_chart_color" value="<?php echo $options['bad_chart_color']; ?>" class="dp-color-field" data-default-color="<?php echo $def_options['bad_chart_color']; ?>" size=7 />
					</div></td>
			</tr>
			<tr>
				<th scope="row"><?php _e('Show polls in archives', $this->text_domain); ?></th>
				<td><input type="checkbox"<?php if ((bool)$options['show_polls_top']) echo " checked"; ?> name="show_polls_top" id="show_polls_top" value="true" /><label for="show_polls_top" class="mg15px-r"><?php _e('Top page', $this->text_domain); ?></label>
					<input type="checkbox"<?php if ((bool)$options['show_polls_archive']) echo " checked"; ?> name="show_polls_archive" id="show_polls_archive" value="true" /><label for="show_polls_archive"><?php _e('Archive page', $this->text_domain); ?></label></td>
			</tr>
	</table>
<?php submit_button('', 'primary', 'dp_ex_simrat_options_save'); ?>
</div>
</form>
</div>
	
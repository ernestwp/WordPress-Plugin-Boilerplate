<?php namespace __plugin_namespace__; ?>

<div class="admin-settings-container">
	<div class="module-container">

		<!-- MODULE DETAILS START -->
		<?php
		foreach ( $this->modules_info as $class_name => $module_info ) {

			$class_name = esc_attr( $class_name );

			$checked = get_option( 'switch-' . $class_name, 'off' );

			if ( 'on' === $checked ) {
				$checked = ' checked="checked"';
			} else {
				$checked = '';
			}
			?>

			<div class="single-module"
				 id="<?php echo $class_name; ?>">

				<div class="module-title">

					<h4 class="module-title--heading">
						<?php echo $module_info->title; ?>
						<?php if ( $module_info->link ) { ?>
							<a class="module-title--doc"href="<?php echo $module_info->link; ?>"
							   class="button tiny secondary">DOCS</a>
						<?php } ?>
					</h4>

					<div class="module-title--switch switch api-form">
						<input class="switch-input"
							   id="switch-<?php echo $class_name; ?>"
							   type="checkbox"
							<?php echo $checked; ?>
							   data-endpoint="switch"
							   data-class="<?php echo $class_name; ?>"
							   name="switch-<?php echo $class_name; ?>">
						<label class="switch-paddle"
							   for="switch-<?php echo $class_name; ?>">
							<span class="switch-active" aria-hidden="true">ON</span>
							<span class="switch-inactive" aria-hidden="true">OFF</span>
						</label>
					</div>

				</div>

				<div class="module-$module_info">
					<p><?php echo $module_info->description; ?></p>
				</div>

				<div class="module-settings">

					<fieldset class="api-form">
						<legend>Settings</legend>
						<?php foreach ( $module_info->settings as $setting ) { ?>
							<?php if ( 'text' === $setting['type'] ) { ?>
								<label for="<?php $setting['name']; ?>"><?php echo $setting['label']; ?>
									<input id="<?php echo $setting['name']; ?>"
										   name="<?php echo $setting['name']; ?>"
										   value="<?php echo get_option( $class_name . '>' . $setting['name'], '' ); ?>"
										   type="<?php echo $setting['type']; ?>">
								</label>
							<?php } ?>

							<?php
							if ( 'checkbox' === $setting['type'] ) {
								$checked = get_option( $class_name . '>' . $setting['name'], '' );
								if ( 'on' === $checked ) {
									$check = 'checked="checked"';
								} else {
									$check = '';
								}
								?>
								<label for="<?php echo $setting['name']; ?>">
									<?php echo $setting['label']; ?>
									<input id="<?php echo $setting['name']; ?>"
										   name="<?php echo $setting['name']; ?>"
										<?php echo $check; ?>
										   type="<?php echo $setting['type']; ?>">
								</label>
							<?php } ?>

							<?php if ( 'helper' === $setting['type'] ) { ?>
								<p class="help-text"><?php echo $setting['label']; ?></p>
							<?php } ?>

							<?php if ( 'select' === $setting['type'] ) {
								$value = get_option( $class_name . '>' . $setting['name'], '' );
								?>
								<label><?php echo $setting['label']; ?>
									<select name="<?php echo $setting['name']; ?>"
											id="<?php echo sanitize_title( $setting['label'] ); ?>">
										<?php foreach ( $setting['options'] as $option_value => $option_name ) {
											if ( $option_value === $value ) {
												$select = 'selected';
											} else {
												$select = '';
											}
											?>
											<option <?php echo $select; ?>
													value="<?php echo $option_value; ?>"><?php echo $option_name; ?></option>
										<?php } ?>
									</select>
								</label>
							<?php } ?>

						<?php } ?>

						<button name="save-<?php echo $class_name; ?>"
								data-endpoint="save-settings"
								data-class="<?php echo $class_name; ?>"
								data-ignore="true"
								type="button"
								class="module-save-settings">Save Settings
						</button>

					</fieldset>
				</div>

			</div>
			<?php
		}
		?>
	</div>


</div>
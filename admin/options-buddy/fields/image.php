<?php
// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

/**
 * Used for Image field type
 */
class OptionsBuddy_Settings_Field_Image extends OptionsBuddy_Settings_Field {

	/**
	 * Renders the field.
	 *
	 * @param array $args args.
	 */
	public function render( $args ) {

		// atgtachment url.
		$value = esc_attr( $args['value'] );
		$size  = $this->get_size();

		// we need to show this imaage.
		if ( $value ) {
			$image = "<img src='{$value}' />";
		} else {

			$image = "<img src='' />";
		}

		$id = $args['option_key'];
		?>
        <div class='settings-image-placeholder'>
			<?php
			if ( $value ) {
				$class = 'settings-image-action-visible';
			}

			echo $image;
			?>
            <br/>
            <a href="#" class="delete-settings-image <?php echo $class; ?>"><?php _e( 'Remove' ); ?></a>
        </div>

		<?php
		echo "<input type='hidden' class='hidden-image-url' id='{$id}' name='{$id}' value='{$value}'/>";
		echo '<input type="button" class="button settings-upload-image-button" id="' . $id . '_button" value="Browse" data-id="' . $id . '" data-btn-title="Select" data-uploader-title="Select" />';

		echo '<span class="description">' . $this->get_desc() . '</span>';
	}
}

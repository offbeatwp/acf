<?php
namespace OffbeatWP\Acf\Fields\Acf;

use acf_field;
use Illuminate\Support\Collection;

class AcfThemeColorField extends acf_field {


	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type    function
	*  @date    5/03/2014
	*  @since   5.0.0
	*
	*  @param   n/a
	*  @return  n/a
	*/

	function initialize() {

		// vars
		$this->name     = 'offbeat_theme_color_field';
		$this->label    = __( 'Theme color', 'acf' );
		$this->category = 'OffbeatWP';

	}

	function render_field( $field ) {

		// vars
		$e  = '';
		$checked = null;

		$themeColors = config('themes.colors');

		if (empty($themeColors)) {
			echo __('No theme colors defined in `config/themes.php`', 'offbeatwp');
			return;
		}

		if ($themeColors instanceOf Collection) {
			$themeColors = $themeColors->all();
		}

		$ul = array(
			'class'             => 'acf-theme-colors',
		);

		// append to class
		$ul['class'] .= ' ' . $field['class'];
		$ul['class'] .= ' ' . 'acf-hl';

		// Determine selected value.
		$value = (string) $field['value'];

		// 1. Selected choice.
		if ( isset( $themeColors[ $value ] ) ) {
			$checked = (string) $value;
		}

		// Bail early if no choices.
		if ( empty( $themeColors ) ) {
			return;
		}

		// Hiden input.
		$e .= acf_get_hidden_input( array( 'name' => $field['name'] ) );

		// Open <ul>.
		$e .= '<ul ' . acf_esc_attr( $ul ) . '>';

		// Loop through choices.
		foreach ( $themeColors as $value => $label ) {
			$is_selected = false;

			// Ensure value is a string.
			$value = (string) $value;

			// Define input attrs.
			$attrs = array(
				'type'  => 'radio',
				'id'    => sanitize_title( $field['id'] . '-' . $value ),
				'name'  => $field['name'],
				'value' => $value,
			);

			// Check if selected.
			if ( esc_attr( $value ) === esc_attr( $checked ) ) {
				$attrs['checked'] = 'checked';
				$is_selected      = true;
			}

			// Check if is disabled.
			if ( isset( $field['disabled'] ) && acf_in_array( $value, $field['disabled'] ) ) {
				$attrs['disabled'] = 'disabled';
			}

			// append
			$e .= '<li class="acf-theme-color-field"><input ' . acf_esc_attr( $attrs ) . '/><label' . ( $is_selected ? ' class="selected"' : '' ) . ' for="' . $attrs['id'] . '"><span class="acf-theme-color-field__square bg-' . $value . '"></span><span class="acf-theme-color-field__label">' . acf_esc_html( $label ) . '</span></label></li>';
		}

		// Close <ul>.
		$e .= '</ul>';

		// Output HTML.
		echo $e;
	}

	function update_field( $field ) {
		return $field;
	}

	function load_value( $value, $post_id, $field ) {

		// must be single value
		if ( is_array( $value ) ) {

			$value = array_pop( $value );

		}

		// return
		return $value;
	}

	function input_admin_enqueue_scripts() {
		// style
		wp_enqueue_style( 'acf-theme-colors', get_template_directory_uri() . "/vendor/offbeatwp/acf/assets/css/acf-theme-colors.css", [], false );
	}
}
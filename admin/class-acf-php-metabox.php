<?php

class ACF_PHP_Metabox {

	/**
	 * @var array
	 */
	protected $options = array();

	/**
	 * Meta_Box constructor.
	 * @param array $options
	 */
	public function __construct( array $options = array() ) {
		if ( ! is_admin() || ! function_exists('acf_add_local_field_group') ) {
			return;
		}

		if ( ! empty( $options['key'] ) && ! empty( $options['title'] ) ) {
			$this->set_options( $options );
			$this->prepare_fields();
			$this->register();
		}
	}

	/**
	 * @param $options
	 */
	public function set_options( $options ) {
		$this->options = $options;
	}

	/**
	 * @return array
	 */
	public function get_options() {
		return $this->options;
	}

	/**
	 * @param $location
	 */
	public function set_location( $location ) {
		$this->options['location'] = $location;
	}

	/**
	 * @return mixed
	 */
	public function get_location() {
		return $this->options['location'];
	}

	/**
	 * @param $fields
	 */
	public function set_fields( $fields ) {
		$this->options['fields'] = $fields;
	}

	/**
	 * @return mixed
	 */
	public function get_fields() {
		return $this->options['fields'];
	}

	/**
	 * Set default options before the meta boxes are created
	 */
	protected function set_default_options() {
		$options = $this->get_options();

		// Set default options
		$options = array_merge( array(
			'label_placement' => 'left',
			'active' => 1,
			'location' => array(
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'post',
					),
				)
			),
			'fields' => array()
		), $options );

		$this->set_options( $options );
	}

	/**
	 * Set location
	 */
	protected function convert_location() {
		$location = $this->get_location();

		if ( is_string( $location ) ) {
			$location = explode( ' ', $location );

			$location = array(
				array(
					array(
						'param' => $location[0],
						'operator' => $location[1],
						'value' => $location[2],
					),
				),
			);
		}

		$this->set_location( $location );
	}

	/**
	 * Convert field (add key, name and optional settings to fields)
	 */
	protected function convert_field( $field_id, $field, $parent_id = null, $layout_id = null, $is_layout = false ) {
		$options = $this->get_options();

		// Set defaults if no options are given
		if ( is_int( $field_id ) && is_string( $field ) ) {
			$field_id = $field;
			$field = array();
		}

		// Check if the field is a sub field (repeater or flexible content field)
		if ( isset( $parent_id ) && $parent_id ) {
			$field['name'] = $field_id;
			// Check if the field is a sub field of a flexible content layout
			if ( isset( $layout_id ) && $layout_id ) {
				$field['key']  = 'field_' . $options['key'] . '_' . $parent_id . '_' . $layout_id . '_' . $field_id;
			} else {
				$field['key']  = 'field_' . $options['key'] . '_' . $parent_id . '_' . $field_id;
			}
		} else {
			$field['name'] = $field_id;
			$field['key']  = 'field_' . $options['key'] . '_' . $field_id;
		}

		// If it's a tab with no placement setting, set default placement
		if ( $field['type'] === 'tab' && ! isset( $field['placement'] ) ) {
			$field['placement'] = 'left';
		}

		// Set defaults
		if ( $is_layout ) {
			$field = array_merge( array(
				'display' => 'row',
			), $field );
		} else {
			$field = array_merge( array(
				'type' => 'text',
			), $field );
		}

		$field = array_merge( array(
			'label' => ucwords( str_replace( '_', ' ', $field_id ) ),
		), $field );

		return $field;
	}

	/**
	 * Prepare fields
	 */
	public function prepare_fields() {
		$this->set_default_options();
		$this->convert_location();

		$options = $this->get_options();
		$fields = $this->get_fields();

		// Add field name and key
		foreach ( $fields as $field_id => &$field ) {

			if ( isset( $field['sub_fields'] ) && is_array( $field['sub_fields'] ) ) {
				$sub_fields = $field['sub_fields'];

				foreach ( $sub_fields as $sub_field_id => &$sub_field ) {
					$sub_field = $this->convert_field( $sub_field_id, $sub_field, $field_id );
				}

				$field['sub_fields'] = $sub_fields;
			}

			if ( isset( $field['layouts'] ) && is_array( $field['layouts'] ) ) {
				$layouts = $field['layouts'];

				foreach ( $layouts as $layout_id => &$layout ) {
					if ( isset( $layouts[ $layout_id ]['sub_fields'] ) && is_array( $layouts[ $layout_id ]['sub_fields'] ) ) {
						$layout_sub_fields = $layouts[ $layout_id ]['sub_fields'];

						foreach ( $layout_sub_fields as $layout_sub_field_id => &$layout_sub_field ) {
							$layout_sub_field = $this->convert_field( $layout_sub_field_id, $layout_sub_field, $field_id, $layout_id );
						}

						$layouts[ $layout_id ]['sub_fields'] = $layout_sub_fields;
					}

					$layout = $this->convert_field( $layout_id, $layout, $field_id, null, true );
				}

				$field['layouts'] = $layouts;
			}

			$field = $this->convert_field( $field_id, $field );

		}

		$this->set_fields( $fields );
	}

	public function register() {
		$options = $this->get_options();

		acf_add_local_field_group( $options );
	}
}
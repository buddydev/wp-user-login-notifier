<?php
// Do not allow direct access over web.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'OptionsBuddy_Settings_Section' ) ) :
	class OptionsBuddy_Settings_Section {

		/**
		 * Section id.
		 *
		 * @var string
		 */
		private $id;

		/**
		 * Title.
		 *
		 * @var string section title
		 */
		private $title;

		/**
		 * Description.
		 *
		 * @var string Section description
		 */
		private $desc = '';

		/**
		 * Fields.
		 *
		 * @var array  of fields
		 */
		private $fields = array();

		/**
		 * Constructor.
		 *
		 * @param string $id Section Id
		 * @param string $title Section Title
		 * @param string $desc Section description
		 */
		public function __construct( $id, $title, $desc = '' ) {

			$this->id    = $id;
			$this->title = $title;
			$this->desc  = $desc;
		}

		/**
		 * Adds a field to this section
		 *
		 * We can use it to chain and add multiple fields in a go
		 *
		 * @param array $field field.
		 *
		 * @return OptionsBuddy_Settings_Section
		 */
		public function add_field( $field ) {

			// check if a field class with name OptionsBuddy_Settings_Field_$type exists, use it.
			$type = 'text';

			if ( isset( $field['type'] ) ) {
				$type = $field['type'];
			}//text/radio etc

			$class_name = 'OptionsBuddy_Settings_Field';
			// a field specific class can be declared as OptionsBuddy_Settings_Field_typeName.
			$field_class_name = $class_name . '_' . ucfirst( $type );

			if ( class_exists( $field_class_name ) && is_subclass_of( $field_class_name, $class_name ) ) {
				$class_name = $field_class_name;
			}


			// let us store the field.
			$this->fields[ $field['name'] ] = new $class_name( $field );

			return $this;
		}

		/**
		 * Adds Multiple Setting fields at one
		 *
		 * @return OptionsBuddy_Settings_Section
		 *
		 * @see OptionsBuddy_Settings_Section::add_field()
		 */
		public function add_fields( $fields ) {

			foreach ( $fields as $field ) {
				$this->add_field( $field );
			}

			return $this;
		}

		/**
		 * Override fields
		 *
		 * @param array $fields fields.
		 *
		 * @return OptionsBuddy_Settings_Section
		 */
		public function set_fields( $fields ) {
			// if set fields is called, first reset fiels.
			$this->reset_fields();

			$this->add_fields( $fields );

			return $this;
		}

		/**
		 * Resets fields
		 */
		public function reset_fields() {
			unset( $this->fields );
			$this->fields = array();

			return $this;
		}

		/**
		 * Sets section id.
		 *
		 * @param string $id section id.
		 */
		public function set_id( $id ) {
			$this->id = $id;
			return $this;
		}

		/**
		 * Sets section title.
		 *
		 * @param string $title title.
		 *
		 * @return $this
		 */
		public function set_title( $title ) {
			$this->title = $title;
			return $this;
		}

		/**
		 * Sets section description.
		 *
		 * @param string $desc description.
		 *
		 * @return $this
		 */
		public function set_description( $desc ) {
			$this->desc = $desc;
			return $this;
		}

		/**
		 * Returns the Section ID
		 *
		 * @return string Section ID
		 */
		public function get_id() {

			return $this->id;
		}

		/**
		 *  Returns Section title
		 *
		 * @return string Section title
		 */
		public function get_title() {
			return $this->title;
		}

		/**
		 * Returns Section Description
		 *
		 * @return string section description
		 */
		public function get_disc() {
			return $this->desc;
		}

		/**
		 * Return a multidimensional array of the setting fields Objects in this section
		 *
		 * @return OptionsBuddy_Settings_Field[]
		 */
		public function get_fields() {
			return $this->fields;
		}

		/**
		 * Retrieves field instance.
		 *
		 * @param string $name fiel name.
		 *
		 * @return OptionsBuddy_Settings_Field
		 */
		public function get_field( $name ) {

			return $this->fields[ $name ];
		}

	}

endif;

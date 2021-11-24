<?php

if ( ! defined( 'ABSPATH' ) ) {
	die; // If this file is called directly, abort.
}

if ( ! class_exists( 'Filterable_Portfolio_MetaBox_API' ) ) {

	class Filterable_Portfolio_MetaBox_API {

		/**
		 * @var string
		 */
		private $input_name = 'filterable_portfolio_meta';

		/**
		 * @var string
		 */
		private $nonce_name = '_fp_nonce';

		/**
		 * @var string
		 */
		private $nonce_action = 'filterable_portfolio_nonce';

		/**
		 * @var string
		 */
		private $table_class = 'form-table';

		/**
		 * Add a custom meta box
		 *
		 * @param array $meta_box Meta box input data
		 *
		 * @return void
		 */
		public function add( $meta_box ) {
			if ( ! is_array( $meta_box ) ) {
				return;
			}

			add_meta_box(
				$meta_box['id'],
				$meta_box['title'],
				array( $this, 'meta_box_callback' ),
				$meta_box['screen'],
				$meta_box['context'] ?: 'advanced',
				$meta_box['priority'] ?: 'high',
				$meta_box
			);
		}

		/**
		 * Create content for the custom meta box
		 *
		 * @param WP_Post $post
		 * @param array $meta_box
		 *
		 * @return void
		 */
		public function meta_box_callback( $post, $meta_box ) {
			if ( ! is_array( $meta_box['args'] ) ) {
				return;
			}

			$meta_box = $meta_box['args'];

			if ( isset( $meta_box['description'] ) && $meta_box['description'] != '' ) {
				echo sprintf( '<p class="description">%s</p>', $meta_box['description'] );
			}

			wp_nonce_field( $this->nonce_action, $this->nonce_name );

			$table = "";
			$table .= "<table class='" . $this->table_class . "'>";

			foreach ( $meta_box['fields'] as $field_id => $field ) {
				$std_value = isset( $field['std'] ) ? $field['std'] : '';
				$meta      = get_post_meta( $post->ID, $field['id'], true );
				$value     = $meta ? $meta : $std_value;
				$name      = '' . $this->input_name . '[' . $field['id'] . ']';
				$type      = isset( $field['type'] ) ? $field['type'] : 'text';

				$table .= "<tr>";
				$table .= sprintf( '<th><label for="%1$s">%2$s</label></th>', $field['id'],
					$field['name'] );
				$table .= "<td>";

				if ( method_exists( $this, $type ) ) {
					$table .= $this->$type( $field, $name, $value );
				} else {
					$table .= $this->text( $field, $name, $value );
				}

				if ( ! empty( $field['desc'] ) ) {
					$table .= sprintf( '<p class="description">%s</p>', $field['desc'] );
				}
				$table .= "</td>";
				$table .= "</tr>";
			}

			$table .= "</table>";
			echo $table;
		}

		/**
		 * text input field
		 *
		 * @param array $field
		 * @param string $name
		 * @param string $value
		 *
		 * @return string
		 */
		public function text( $field, $name, $value ) {
			return sprintf( '<input type="text" class="regular-text" value="%1$s" id="%2$s" name="%3$s">', $value,
				$field['id'], $name );
		}

		/**
		 * email input field
		 *
		 * @param array $field
		 * @param string $name
		 * @param string $value
		 *
		 * @return string
		 */
		public function email( $field, $name, $value ) {
			return sprintf( '<input type="email" class="regular-text" value="%1$s" id="%2$s" name="%3$s">', $value,
				$field['id'], $name );
		}

		/**
		 * password input field
		 *
		 * @param array $field
		 * @param string $name
		 * @param string $value
		 *
		 * @return string
		 */
		public function password( $field, $name, $value ) {
			return sprintf( '<input type="password" class="regular-text" value="" id="%2$s" name="%3$s">', $value,
				$field['id'], $name );
		}

		/**
		 * number input field
		 *
		 * @param array $field
		 * @param string $name
		 * @param string $value
		 *
		 * @return string
		 */
		public function number( $field, $name, $value ) {
			return sprintf( '<input type="number" class="regular-text" value="%1$s" id="%2$s" name="%3$s">', $value,
				$field['id'], $name );
		}

		/**
		 * url input field
		 *
		 * @param array $field
		 * @param string $name
		 * @param string $value
		 *
		 * @return string
		 */
		public function url( $field, $name, $value ) {
			return sprintf( '<input type="url" class="regular-text" value="%1$s" id="%2$s" name="%3$s">', $value,
				$field['id'], $name );
		}

		/**
		 * color input field
		 *
		 * @param array $field
		 * @param string $name
		 * @param string $value
		 *
		 * @return string
		 */
		public function color( $field, $name, $value ) {
			$default_color = ( isset( $field['std'] ) ) ? $field['std'] : "";

			return sprintf( '<input type="text" class="colorpicker" value="%1$s" id="%2$s" name="%3$s" data-default-color="%4$s">',
				$value, $field['id'], $name, $default_color );
		}

		/**
		 * date input field
		 *
		 * @param array $field
		 * @param string $name
		 * @param string $value
		 *
		 * @return string
		 */
		public function date( $field, $name, $value ) {
			$value = ! empty( $value ) ? date( "Y-m-d", strtotime( $value ) ) : '';

			return sprintf( '<input type="date" class="regular-text" value="%1$s" id="%2$s" name="%3$s">',
				$value, $field['id'], $name );
		}

		/**
		 * textarea input field
		 *
		 * @param array $field
		 * @param string $name
		 * @param string $value
		 *
		 * @return string
		 */
		public function textarea( $field, $name, $value ) {
			$rows = ( isset( $field['rows'] ) ) ? $field['rows'] : 5;
			$cols = ( isset( $field['cols'] ) ) ? $field['cols'] : 40;

			return sprintf( '<textarea id="%2$s" name="%3$s" rows="%4$s" cols="%5$s">%1$s</textarea>', $value,
				$field['id'], $name, $rows, $cols );
		}

		/**
		 * checkbox input field
		 *
		 * @param array $field
		 * @param string $name
		 * @param string $value
		 *
		 * @return string
		 */
		public function checkbox( $field, $name, $value ) {
			$checked = ( 'yes' == $value ) ? 'checked="checked"' : '';
			$table   = sprintf( '<input type="hidden" name="%1$s" value="no">', $name );
			$table   .= '<fieldset><legend class="screen-reader-text"><span>' . $field['name'] . '</span></legend>';
			$table   .= '<label for="' . $field['id'] . '">';
			$table   .= '<input type="checkbox" value="yes" id="' . $field['id'] . '" name="' . $name . '" ' . $checked . '>';
			$table   .= $field['name'] . '</label></fieldset>';

			return $table;
		}

		/**
		 * multi checkbox input field
		 *
		 * @param array $field
		 * @param string $name
		 * @param array $value
		 *
		 * @return string
		 */
		public function multi_checkbox( $field, $name, $value ) {
			$table = "<fieldset>";
			$_name = $name . "[]";

			$table .= sprintf( '<input type="hidden" name="%1$s" value="0">', $_name );
			foreach ( $field['options'] as $key => $label ) {
				$checked = ( in_array( $key, $value ) ) ? 'checked="checked"' : '';
				$table   .= '<label for="' . $key . '">';
				$table   .= '<input type="checkbox" id="' . $key . '" name="' . $_name . '" value="' . $key . '" ' . $checked . '>';
				$table   .= $label . '</label><br>';
			}
			$table .= "</fieldset>";

			return $table;
		}

		/**
		 * radio input field
		 *
		 * @param array $field
		 * @param string $name
		 * @param string $value
		 *
		 * @return string
		 */
		public function radio( $field, $name, $value ) {
			$table = '<fieldset><legend class="screen-reader-text"><span>' . $field['name'] . '</span></legend><p>';
			foreach ( $field['options'] as $key => $radio_label ) {
				$checked = ( $value == $key ) ? 'checked="checked"' : '';
				$table   .= '<label><input type="radio" value="' . $key . '" name="' . $name . '" ' . $checked . '>' . $radio_label . '</label><br>';
			}
			$table .= "</p></fieldset>";

			return $table;
		}

		/**
		 * select input field
		 *
		 * @param array $field
		 * @param string $name
		 * @param string $value
		 *
		 * @return string
		 */
		public function select( $field, $name, $value ) {
			$table = sprintf( '<select id="%1$s" name="%2$s">', $field['id'], $name );
			foreach ( $field['options'] as $key => $select_label ) {
				$selected = ( $value == $key ) ? 'selected="selected"' : '';
				$table    .= '<option value="' . $key . '" ' . $selected . '>' . $select_label . '</option>';
			}
			$table .= "</select>";

			return $table;
		}

		/**
		 * wp_editor input field
		 *
		 * @param array $field
		 * @param string $name
		 * @param string $value
		 *
		 * @return string
		 */
		public function wp_editor( $field, $name, $value ) {
			ob_start();
			echo "<div class='sp-wp-editor-container'>";
			wp_editor( $value, $field['id'], array(
				'textarea_name' => $name,
				'tinymce'       => false,
				'media_buttons' => false,
				'textarea_rows' => isset( $field['rows'] ) ? $field['rows'] : 6,
				'quicktags'     => array( "buttons" => "strong,em,link,img,ul,li,ol" ),
			) );
			echo "</div>";

			return ob_get_clean();
		}

		/**
		 * images input field
		 *
		 * @param array $field
		 * @param string $name
		 * @param string $value
		 *
		 * @return string
		 */
		public function images( $field, $name, $value ) {
			$btn_text = $value ? 'Edit Gallery' : 'Add Gallery';
			$value    = strip_tags( rtrim( $value, ',' ) );
			ob_start(); ?>
			<div class="gallery_images">
				<input type="hidden" value="<?php echo $value; ?>" id="<?php echo $field['id']; ?>"
					   name="<?php echo $name; ?>">
				<button id="fp_gallery_btn"
						class="button button-default"
						data-modal="MediaFramePost"
						data-create="<?php esc_attr_e( 'Create Gallery', 'filterable-portfolio' ); ?>"
						data-edit="<?php esc_attr_e( 'Edit Gallery', 'filterable-portfolio' ); ?>"
						data-progress="<?php esc_attr_e( 'Saving...', 'filterable-portfolio' ); ?>"
						data-save="<?php esc_attr_e( 'Save Gallery', 'filterable-portfolio' ); ?>"
				><?php echo $btn_text; ?></button>
				<br class="clear"><br>
				<ul class="fp_gallery_list gallery_images_list">
					<?php
					if ( $value ) {
						$thumbs = explode( ',', $value );
						foreach ( $thumbs as $thumb ) {
							echo '<li>' . wp_get_attachment_image( $thumb, array( 75, 75 ) ) . '</li>';
						}
					}
					?>
				</ul>
				<br class="clear">
			</div>
			<?php
			return ob_get_clean();
		}
	}
}

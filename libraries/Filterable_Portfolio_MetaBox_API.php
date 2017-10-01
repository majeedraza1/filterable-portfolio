<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Filterable_Portfolio_MetaBox_API' ) ) {

	class Filterable_Portfolio_MetaBox_API {

		/**
		 * Add a custom meta box
		 *
		 * @param array $meta_box Meta box input data
		 *
		 * @return bool
		 */
		public function add( $meta_box ) {
			if ( ! is_array( $meta_box ) ) {
				return false;
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
		 * @param  WP_Post $post
		 * @param  array $meta_box
		 *
		 * @return bool
		 */
		public function meta_box_callback( $post, $meta_box ) {
			if ( ! is_array( $meta_box['args'] ) ) {
				return false;
			}

			$meta_box = $meta_box['args'];

			if ( isset( $meta_box['description'] ) && $meta_box['description'] != '' ) {
				echo sprintf( '<p class="description">%s</p>', $meta_box['description'] );
			}

			wp_nonce_field( 'filterable_portfolio_nonce', '_fp_nonce' );

			$table = "";
			$table .= "<table class='form-table shapla-metabox-table'>";

			foreach ( $meta_box['fields'] as $field ) {
				$std_value = isset( $field['std'] ) ? $field['std'] : '';
				$meta      = get_post_meta( $post->ID, $field['id'], true );
				$value     = $meta ? $meta : $std_value;
				$name      = sprintf( 'filterable_portfolio_meta[%s]', $field['id'] );
				$type      = isset( $field['type'] ) ? $field['type'] : 'text';

				$table .= "<tr>";
				$table .= sprintf( '<th scope="row"><label for="%1$s">%2$s</label></th>', $field['id'], $field['name'] );
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
			$this->color_datepicker_script();
		}

		public function color_datepicker_script() {
			?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    $(".colorpicker").wpColorPicker();
                    $(".datepicker").datepicker({
                        changeMonth: true,
                        changeYear: true,
                        showAnim: "slideDown"
                    });
                });
            </script>
			<?php
		}

		/**
		 * text input field
		 *
		 * @param  array $field
		 * @param  string $name
		 * @param  string $value
		 *
		 * @return string
		 */
		private function text( $field, $name, $value ) {
			return sprintf( '<input type="text" class="regular-text" value="%1$s" id="%2$s" name="%3$s">', $value, $field['id'], $name );
		}

		/**
		 * email input field
		 *
		 * @param  array $field
		 * @param  string $name
		 * @param  string $value
		 *
		 * @return string
		 */
		private function email( $field, $name, $value ) {
			return sprintf( '<input type="email" class="regular-text" value="%1$s" id="%2$s" name="%3$s">', $value, $field['id'], $name );
		}

		/**
		 * password input field
		 *
		 * @param  array $field
		 * @param  string $name
		 * @param  string $value
		 *
		 * @return string
		 */
		private function password( $field, $name, $value ) {
			return sprintf( '<input type="password" class="regular-text" value="" id="%2$s" name="%3$s">', $value, $field['id'], $name );
		}

		/**
		 * number input field
		 *
		 * @param  array $field
		 * @param  string $name
		 * @param  string $value
		 *
		 * @return string
		 */
		private function number( $field, $name, $value ) {
			return sprintf( '<input type="number" class="regular-text" value="%1$s" id="%2$s" name="%3$s">', $value, $field['id'], $name );
		}

		/**
		 * url input field
		 *
		 * @param  array $field
		 * @param  string $name
		 * @param  string $value
		 *
		 * @return string
		 */
		private function url( $field, $name, $value ) {
			return sprintf( '<input type="url" class="regular-text" value="%1$s" id="%2$s" name="%3$s">', $value, $field['id'], $name );
		}

		/**
		 * color input field
		 *
		 * @param  array $field
		 * @param  string $name
		 * @param  string $value
		 *
		 * @return string
		 */
		private function color( $field, $name, $value ) {
			$default_color = ( isset( $field['std'] ) ) ? $field['std'] : "";

			return sprintf( '<input type="text" class="colorpicker" value="%1$s" id="%2$s" name="%3$s" data-default-color="%4$s">', $value, $field['id'], $name, $default_color );
		}

		/**
		 * date input field
		 *
		 * @param  array $field
		 * @param  string $name
		 * @param  string $value
		 *
		 * @return string
		 */
		private function date( $field, $name, $value ) {
			$value = empty( $value ) ? date( 'F d, Y', time() ) : $value;
			$value = date( "F d, Y", strtotime( $value ) );

			return sprintf( '<input type="text" class="regular-text datepicker" value="%1$s" id="%2$s" name="%3$s">', $value, $field['id'], $name );
		}

		/**
		 * textarea input field
		 *
		 * @param  array $field
		 * @param  string $name
		 * @param  string $value
		 *
		 * @return string
		 */
		private function textarea( $field, $name, $value ) {
			$rows = ( isset( $field['rows'] ) ) ? $field['rows'] : 5;
			$cols = ( isset( $field['cols'] ) ) ? $field['cols'] : 40;

			return sprintf( '<textarea id="%2$s" name="%3$s" rows="%4$s" cols="%5$s">%1$s</textarea>', $value, $field['id'], $name, $rows, $cols );
		}

		/**
		 * checkbox input field
		 *
		 * @param  array $field
		 * @param  string $name
		 * @param  string $value
		 *
		 * @return string
		 */
		private function checkbox( $field, $name, $value ) {
			$checked = ( 1 == $value ) ? 'checked="checked"' : '';
			$table   = sprintf( '<input type="hidden" name="%1$s" value="0">', $name );
			$table   .= sprintf( '<fieldset><legend class="screen-reader-text"><span>%1$s</span></legend><label for="%2$s"><input type="checkbox" value="1" id="%2$s" name="%4$s" %3$s>%1$s</label></fieldset>', $field['name'], $field['id'], $checked, $name );

			return $table;
		}

		/**
		 * multi checkbox input field
		 *
		 * @param  array $field
		 * @param  string $name
		 * @param  string $value
		 *
		 * @return string
		 */
		private function multi_checkbox( $field, $name, $value ) {
			$table           = "<fieldset>";
			$multicheck_name = $name . "[]";

			$table .= sprintf( '<input type="hidden" name="%1$s" value="0">', $multicheck_name );
			foreach ( $field['options'] as $key => $label ) {
				$multichecked = ( in_array( $key, $this->options[ $field['id'] ] ) ) ? 'checked="checked"' : '';
				$table        .= sprintf( '<label for="%1$s"><input type="checkbox" value="%1$s" id="%1$s" name="%2$s" %3$s>%4$s</label><br>', $key, $multicheck_name, $multichecked, $label );
			}
			$table .= "</fieldset>";

			return $table;
		}

		/**
		 * radio input field
		 *
		 * @param  array $field
		 * @param  string $name
		 * @param  string $value
		 *
		 * @return string
		 */
		private function radio( $field, $name, $value ) {
			$table = sprintf( '<fieldset><legend class="screen-reader-text"><span>%1$s</span></legend><p>', $field['name'] );

			foreach ( $field['options'] as $key => $radio_label ) {

				$radio_checked = ( $value == $key ) ? 'checked="checked"' : '';
				$table         .= sprintf( '<label><input type="radio" %1$s value="%2$s" name="%3$s">%4$s</label><br>', $radio_checked, $key, $name, $radio_label );
			}
			$table .= "</p></fieldset>";

			return $table;
		}

		/**
		 * select input field
		 *
		 * @param  array $field
		 * @param  string $name
		 * @param  string $value
		 *
		 * @return string
		 */
		private function select( $field, $name, $value ) {
			$table = sprintf( '<select id="%1$s" name="%2$s">', $field['id'], $name );
			foreach ( $field['options'] as $key => $select_label ) {
				$selected = ( $value == $key ) ? 'selected="selected"' : '';
				$table    .= sprintf( '<option value="%1$s" %2$s>%3$s</option>', $key, $selected, $select_label );
			}
			$table .= "</select>";

			return $table;
		}

		/**
		 * wp_editor input field
		 *
		 * @param  array $field
		 * @param  string $name
		 * @param  string $value
		 *
		 * @return string
		 */
		private function wp_editor( $field, $name, $value ) {
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
		 * file input field
		 *
		 * @param  array $field
		 * @param  string $name
		 * @param  string $value
		 *
		 * @return string
		 */
		private function file( $field, $name, $value ) {
			$multiple   = ( isset( $field['multiple'] ) ) ? true : false;
			$btn_browse = ( isset( $field['btn_browse'] ) ) ? $field['btn_browse'] : 'Browse';
			$btn_insert = ( isset( $field['btn_insert'] ) ) ? $field['btn_insert'] : 'Insert';
			$btn_id     = $field['id'] . '_button';
			ob_start(); ?>

            <input type="text" name="<?php echo $name; ?>" id="<?php echo $field['id']; ?>"
                   value="<?php echo $value; ?>" class="regular-text">
            <input type="button" class="button" id="<?php echo $btn_id; ?>" value="<?php echo $btn_browse; ?>">
            <script>
                jQuery(function ($) {
                    var frame,
                        isMultiple = "<?php echo $multiple; ?>";

                    $('#<?php echo $btn_id; ?>').on('click', function (e) {
                        e.preventDefault();

                        var options = {
                            state: 'insert',
                            frame: 'post',
                            multiple: isMultiple
                        };

                        frame = wp.media(options).open();

                        frame.menu.get('view').unset('gallery');
                        frame.menu.get('view').unset('featured-image');

                        frame.toolbar.get('view').set({
                            insert: {
                                style: 'primary',
                                text: '<?php echo $btn_insert; ?>',

                                click: function () {
                                    var models = frame.state().get('selection'),
                                        attachment_id = models.first().attributes.id,
                                        files = [];

                                    if (isMultiple) {
                                        models.map(function (attachment) {
                                            attachment = attachment.toJSON();
                                            files.push(attachment.id);
                                            attachment_id = files;
                                        });
                                    }

                                    $('#<?php echo $field['id']; ?>').val(attachment_id);

                                    frame.close();
                                }
                            }
                        });
                    });
                });
            </script>
			<?php return ob_get_clean();
		}

		/**
		 * images input field
		 *
		 * @param  array $field
		 * @param  string $name
		 * @param  string $value
		 *
		 * @return string
		 */
		public function images( $field, $name, $value ) {
			$btn_text = $value ? 'Edit Gallery' : 'Add Gallery';
			$value    = strip_tags( rtrim( $value, ',' ) );
			$output   = '';

			if ( $value ) {
				$thumbs = explode( ',', $value );
				foreach ( $thumbs as $thumb ) {
					$output .= '<li>' . wp_get_attachment_image( $thumb, array( 75, 75 ) ) . '</li>';
				}
			}

			$html = '';
			$html .= '<div class="shaplatools_gallery_images">';
			$html .= sprintf( '<input type="hidden" value="%1$s" id="fp_images_ids" name="%2$s">', $value, $name );
			$html .= sprintf( '<a href="#" id="fp_gallery_btn" class="button button-default">%s</a><br><br class="clear">', $btn_text );
			$html .= sprintf( '<ul class="fp_gallery_list">%s</ul><br class="clear">', $output );
			$html .= '</div>';

			return $html;
		}
	}
}


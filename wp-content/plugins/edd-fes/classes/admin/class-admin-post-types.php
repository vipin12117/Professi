<?php
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class FES_Admin_Post_Types extends FES_Render_Form {
	function __construct() {
		add_action( 'add_meta_boxes_fes-applications', array( $this, 'fes_applications_add_meta_boxes' ) );
	}
	public function fes_applications_add_meta_boxes() {
		global $post;
		add_meta_box( 'fes-applications-fields', __( 'Application Data', 'edd_fes' ), array($this, 'fes_applications_render_form'), 'fes-applications', 'normal', 'high' );
		remove_meta_box('submitdiv', 'fes-applications', 'side');
        remove_meta_box('slugdiv', 'fes-applications', 'normal');
	}
	function fes_applications_render_form() {
        global $post;

        $form_id = EDD_FES()->fes_options->get_option( 'fes-application-form');
		$form_vars     = $this->get_input_fields( $form_id );
		$form_settings = get_post_meta( $form_id, 'fes-form_settings', true );
		list( $user_vars, $taxonomy_vars, $meta_vars ) = $form_vars;

        if ( empty( $user_vars ) && empty( $meta_vars ) ) {
            _e( 'Application data missing!', 'edd_fes' );
            return;
        }
        ?>

        <table class="form-table fes-cf-table">
            <tbody>
                <?php
				$this->render_items( $user_vars, get_post_meta($post->ID, 'fes_user',true) , 'user', $form_id, $form_settings, true );
                $this->render_items( $meta_vars, get_post_meta($post->ID, 'fes_user',true) , 'user', $form_id, $form_settings, true );
                ?>
            </tbody>
        </table>
        <?php
    }

    function label( $attr, $post_id = 0) {
        ?>
        <?php echo $attr['label'] . $this->required_mark( $attr ); ?>
        <?php
    }

    function render_item_before( $form_field, $post_id = 0 ) {
        echo '<tr>';
        echo '<th><strong>';
        $this->label( $form_field );
        echo '</strong></th>';
        echo '<td>';
    }

    function render_item_after( $attr, $post_id = 0) {
        echo '</td>';
        echo '</tr>';
    }
}
new FES_Admin_Post_Types;
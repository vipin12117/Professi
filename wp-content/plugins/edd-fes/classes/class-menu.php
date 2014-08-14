<?php
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

// This is based off of work by bbPress and also EDD itself.
class FES_Menu {

	public $minimum_capability = 'manage_options';

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menus') );
		add_action( 'admin_head', array( $this, 'admin_head' ) );
		add_action( 'admin_init', array( $this, 'welcome'    ) );
	}

	public function admin_menus() {
		// About Page
		add_menu_page(
			__( 'EDD FES', 'edd_fes' ),
			__( 'EDD FES', 'edd_fes' ),
			$this->minimum_capability,
			'fes-about',
			array( $this, 'about_screen' ),'','25.01'
		);
		add_submenu_page( 'fes-about', 'Applications', 'Applications', 'manage_options', 'fes-applications', array( $this, 'fes_applications_page') );
		add_submenu_page( 'fes-about', 'Submission Form', 'Submission Form', 'manage_options', 'post.php?post=' . EDD_FES()->fes_options->get_option( 'fes-submission-form') . '&action=edit');
		add_submenu_page( 'fes-about', 'Profile Form', 'Profile Form', 'manage_options', 'post.php?post=' . EDD_FES()->fes_options->get_option( 'fes-profile-form') . '&action=edit');
		add_submenu_page( 'fes-about', 'Application Form', 'Application Form', 'manage_options', 'post.php?post=' . EDD_FES()->fes_options->get_option( 'fes-application-form') . '&action=edit');
	}

	/**
	 * Hide Individual Dashboard Pages
	 *
	 * @access public
	 * @since 1.4
	 * @return void
	 */
	public function admin_head() {
		// Badge for welcome page
		$badge_url = fes_assets_url . 'img/extensions2.jpg';
		?>
		<style type="text/css" media="screen">
		/*<![CDATA[*/
		.fes-badge {
			padding-top: 150px;
			height: 217px;
			width: 370px;
			color: #666;
			font-weight: bold;
			font-size: 14px;
			text-align: center;
			text-shadow: 0 1px 0 rgba(255, 255, 255, 0.8);
			margin: 0 -5px;
			background: url('<?php echo $badge_url; ?>') no-repeat;
		}

		.about-wrap .fes-badge {
			position: absolute;
			top: 0;
			right: 0;
		}

		.fes-welcome-screenshots {
			float: right;
			margin-left: 10px!important;
		}
		/*]]>*/
		</style>
		<?php
	}

	/**
	 * Render About Screen
	 *
	 * @access public
	 * @since 1.4
	 * @return void
	 */
	public function about_screen() {
		list( $display_version ) = explode( '-', fes_plugin_version );
		?>
		<div class="wrap about-wrap">
			<h1><?php printf( __( 'Welcome to EDD FES %s!', 'edd_fes' ), $display_version ); ?></h1>
			<div class="about-text"><?php printf( __( 'Thank you for updating to the latest version! <br />Easy Digital Downloads Frontend Submissions %s  <br /> is ready to make your online store faster, safer and better!', 'edd_fes' ), $display_version ); ?></div>
			<div class="fes-badge"></div>

			<h1>
			<?php _e( "What's New:", 'edd_fes' ); ?>
			</h1>

			<div class="changelog">
				<h3><?php _e( 'Changelog', 'edd_fes' );?></h3>

				<div class="feature-section">
					<li><?php _e( '* Feature: Added the ability for vendors to delete products', 'edd_fes' );?></li>
					<li><?php _e( '* Feature: Added the ability for vendors to edit products', 'edd_fes' );?></li>
					<li><?php _e( '* Feature: The application process is now an FES form', 'edd_fes' );?></li>
					<li><?php _e( '* Feature: Removed a ton of CSS and JS', 'edd_fes' );?></li>
					<li><?php _e( '* Feature: FES Formbuilder has been improved with better labels, more responsive css, and a new design', 'edd_fes' );?></li>
					<li><?php _e( '* Bug: Removed Add New button from FES forms page', 'edd_fes' );?></li>
					<li><?php _e( '* Bug: CSS classes are now consistent', 'edd_fes' );?></li>
					<li><?php _e( '* Bug: Fixed an issue with Author URLs getting hijacked for non-vendors', 'edd_fes' );?></li>
					<li><?php _e( '* Bug: Fixed an issue with 404s on author pages', 'edd_fes' );?></li>
					<li><?php _e( '* Bug: Improved the install/update script', 'edd_fes' );?></li>
					<li><?php _e( '* Bug: Fixed numerous undefined index errors', 'edd_fes' );?></li>
					<li><?php _e( '* Bug: Fixed improper and missing text domains', 'edd_fes' );?></li>
				</div>
			</div>
		</div>
		<?php
	}
		
	public function welcome() {
		global $edd_options;

		// Bail if no activation redirect
		if ( ! get_transient( '_edd_fes_activation_redirect' ) )
			return;

		// Delete the redirect transient
		delete_transient( '_edd_fes_activation_redirect' );

		// Bail if activating from network, or bulk
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) )
			return;

		wp_safe_redirect( admin_url( 'index.php?page=fes-about' ) ); exit;
	}
	

function fes_applications_page() {
    ?>
    <div class="wrap">

        <div id="icon-edit" class="icon32"><br/></div>
        <h2><?php _e('Vendor Applications', 'eddc'); ?></h2>

        <?php

        if( isset( $_GET['action'] ) && $_GET['action'] == 'edit' ) {
            include( EDDC_PLUGIN_DIR . 'includes/edit-commission.php' );
        } else {

            $applications_table = new EDD_FES_Applications_Table();

            //Fetch, prepare, sort, and filter our data...
            $applications_table->prepare_items();

            ?>
            <form id="fes-applications-filter" method="get">

                <input type="hidden" name="page" value="fes-applications" />
                <!-- Now we can render the completed list table -->
                <?php $applications_table->views() ?>

                <?php $applications_table->display() ?>
            </form>
           <?php
        }
        ?>
    </div>
    <?php

}
}
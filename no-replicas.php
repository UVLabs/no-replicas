<?php
/*
Plugin Name: No Replicas T-T
Plugin URI: https://soaringleads.com
Description:  A mu-plugin to auto disable backup, cloning and file manager plugins on a WordPress site.
Version: 1.0.0
Author: Uriahs Victor
Author URI: https://soaringleads.com
License:           GPL-2.0+
License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
Requires PHP:      7.4
*/

/**
 * Main class.
 * 
 * More plugins can be added to the nr_get_blacklisted_plugins() array. 
 * Alternatively, you can completely replace the list with a custom one.
 */
class NR_Deactivate {

	/**
	 * Class constructor.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'admin_init', array( $this, 'nr_deactivate_blacklisted_plugins' ) );
		$this->nr_disable_editor();
	}

	/**
	 * Disable the file editor for plugins and themes.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	private function nr_disable_editor(): void {
		if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
			define( 'DISALLOW_FILE_EDIT', true );
		}
	}

	/**
	 * Get current installed plugins.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	private function nr_get_installed_plugins() : array {
		$all_plugins = get_plugins();
		$normalize   = array();

		foreach ( $all_plugins as $registered_name => $plugin_data ) {
			$normalize[ $registered_name ] = $plugin_data['Name'];
		}

		return $normalize;
	}

	/**
	 * Get our blacklisted plugins.
	 *
	 * @return array
	 * @since 1.0.0
	 */
	private function nr_get_blacklisted_plugins() : array {

		return array(
			'Everest Backup', // https://wordpress.org/plugins/everest-backup
			'WP File Manager', // https://wordpress.org/plugins/wp-file-manager
			'FileBird Lite', // https://wordpress.org/plugins/filebird/
			'File Manager Advanced', // https://wordpress.org/plugins/file-manager-advanced
			'Duplicator', // https://wordpress.org/plugins/duplicator/
			'UpdraftPlus - Backup/Restore', // https://wordpress.org/plugins/updraftplus/
			'Backup Migration', // https://wordpress.org/plugins/backup-backup/
			'Backup', // https://wordpress.org/plugins/backup/
			'BackWPup', // https://wordpress.org/plugins/backwpup/
			'Backup Duplicator & Migration - WP STAGING', // https://wordpress.org/plugins/wp-staging/
			'WPvivid Backup Plugin', // https://wordpress.org/plugins/wpvivid-backuprestore/
			'WordPress Backup & Security Plugin - BlogVault', // https://wordpress.org/plugins/blogvault-real-time-backup/
			'Backup For WP', // https://wordpress.org/plugins/wp-database-backup/
			'All-in-One WP Migration', // https://wordpress.org/plugins/all-in-one-wp-migration/
		);
	}

	/**
	 * Prevent activation of blacklisted plugins.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function nr_deactivate_blacklisted_plugins() {
		$blacklist = $this->nr_get_blacklisted_plugins();
		$installed = $this->nr_get_installed_plugins();

		foreach ( $blacklist as $blacklisted ) {

			array_filter(
				$installed,
				function ( $value, $key ) use ( $blacklisted ) {

					if ( $blacklisted === $value ) {

						if ( is_plugin_active( $key ) ) {
							deactivate_plugins( $key );
							add_action( 'admin_notices', array( $this, 'nr_create_admin_notice' ) );
						}
					}
				},
				ARRAY_FILTER_USE_BOTH
			);
		}
	}

	/**
	 * Create admin notice when user tries to activate a blacklisted plugin.
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function nr_create_admin_notice() : void {

		 echo "
		 	<div class='notice notice-warning'>
             	<p style='font-size: 24px;'><strong>Sorry...</strong> You're not allowed to install this plugin. Please contact us if there's a particular reason you need it 🫣</p>
        	</div>
			 ";

	}

}
new NR_Deactivate();

<?php
/**
 * Meetup.com Importer for The Events Calendar Cron
 * @version 0.1.0
 * @package Meetup.com Importer for The Events Calendar
 */

class TMI_Cron {
	/**
	 * Parent plugin class
	 *
	 * @var   class
	 * @since NEXT
	 */
	protected $plugin = null;

	/**
	 * Constructor
	 *
	 * @since  NEXT
	 * @param  object $plugin Main plugin object.
	 * @return void
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->hooks();
	}

	/**
	 * Initiate our hooks
	 *
	 * @since  NEXT
	 * @return void
	 */
	public function hooks() {
		add_action( 'save_post_tec_meetup_import', array( $this, 'maybe_add_cron_job' ), 10, 3 );
		add_action( 'delete_post', array ( $this, 'delete_cron_job') );
	}

	/**
	 * Delete the cron job for this import.
	 *
	 * @since  NEXT
	 * @param  $post_id
	 * @return void
	 */
	public function delete_cron_job( $post_id ) {
		$post_type = get_post_type( $post_id );
		if ( $post_type === 'tec_meetup_import' ) {
			wp_clear_scheduled_hook(
				'tec_meetup_do_import',
				array( 'post_id' => $post_id )
			);
		}
	}

	/**
	 * If this is a new import being saved, set up a cron job for it.
	 *
	 * @since  NEXT
	 * @param  $post_id
	 * @param  $post
	 * @param  $update
	 * @return void
	 */
	public function maybe_add_cron_job( $post_id, $post, $update ) {
		if ( !$update ) {
			wp_schedule_event(
				time(),
				'twicedaily',
				'tec_meetup_do_import',
				array( 'post_id' => $post_id )
			);
		}
	}
}

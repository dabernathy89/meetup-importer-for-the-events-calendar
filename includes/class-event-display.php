<?php
/**
 * Event Importer for Meetup and The Events Calendar Event Display
 * @version 0.2.3
 * @package Event Importer for Meetup and The Events Calendar
 */

class TMI_Event_Display {
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
		add_filter( 'the_content', array( $this, 'maybe_add_meetup_link_to_event') );
	}

	/**
	 * Add the Meetup.com link to an event if the link text is set.
	 *
	 * @since  NEXT
	 * @return string
	 */
	public function maybe_add_meetup_link_to_event( $content = '' ) {
		if ( get_post_type() === 'tribe_events' ) {
			$link_html = $this->construct_link_html();
			$content = $this->add_link_to_content( $content, $link_html );
		}

		return $content;
	}

	public function add_link_to_content( $content = '', $link_html = '' ) {
		if ( $link_html ) {
			$position = apply_filters( 'tec_meetup_link_position', 'below' );
			if ( $position === 'below' ) {
				$content .= $link_html;
			} elseif ( $position === 'above' ) {
				$content = $link_html . $content;
			}
		}

		return $content;
	}

	/**
	 * Build link HTML from text and URL
	 *
	 * @since  NEXT
	 * @return string
	 */
	public function construct_link_html() {
		$html = '';
		$link_text = Tribe__Events__Importer__Options::getOption('meetup_link_text');
		$link = get_post_meta( get_the_ID(), '_tec_meetup_import_event_link', true );

		if ( $link_text && $link ) {
			$html = '<div class="meetup-link-wrap">';
			$html .= '<a target="_blank" href="' . esc_attr( $link ) . '">' . esc_html( $link_text ) . '</a>';
			$html .= '</div>';
			$html = apply_filters( 'tec_meetup_link_html', $html, $link_text, $link );
		}

		return $html;
	}
}

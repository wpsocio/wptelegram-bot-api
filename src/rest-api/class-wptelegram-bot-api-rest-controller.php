<?php
/**
 * Plugin bot API endpoint for WordPress REST API.
 *
 * @link       https://t.me/manzoorwanijk
 * @since      1.2.2
 *
 * @package    WPTelegram_Bot_API
 * @subpackage WPTelegram_Bot_API/rest-api
 */

/**
 * Class to handle the bot API endpoint.
 *
 * @since 1.2.2
 *
 * @package    WPTelegram_Bot_API
 * @subpackage WPTelegram_Bot_API/rest-api
 * @author     Manzoor Wani <@manzoorwanijk>
 */
class WPTelegram_Bot_API_REST_Controller extends WPTelegram_Bot_API_REST_Base_Controller {

	/**
	 * Constructor
	 *
	 * @since 1.2.2
	 */
	public function __construct() {
		$this->rest_base = '/(?P<method>[a-zA-Z]+)';
	}

	/**
	 * Register the routes.
	 *
	 * @since 1.2.2
	 */
	public function register_routes() {

		register_rest_route(
			$this->namespace,
			$this->rest_base,
			array(
				array(
					'methods'             => 'GET, POST',
					'callback'            => array( $this, 'handle_request' ),
					'permission_callback' => array( $this, 'permissions_for_request' ),
					'args'                => self::get_test_params(),
				),
			)
		);
	}

	/**
	 * Check request permissions.
	 *
	 * @since 1.2.2
	 *
	 * @param WP_REST_Request $request WP REST API request.
	 *
	 * @return bool
	 */
	public function permissions_for_request( $request ) {
		$permission = current_user_can( 'manage_options' );

		return apply_filters( 'wptelegram_bot_api_rest_permission', $permission, $request );
	}

	/**
	 * Handle the request.
	 *
	 * @since 1.2.2
	 *
	 * @param WP_REST_Request $request WP REST API request.
	 */
	public function handle_request( WP_REST_Request $request ) {

		$bot_token  = $request->get_param( 'bot_token' );
		$api_method = $request->get_param( 'method' );
		$api_params = $request->get_param( 'api_params' );

		$body = array();
		$code = 200;

		$bot_api = new WPTelegram_Bot_API( $bot_token );

		if ( empty( $api_params ) ) {
			$api_params = array();
		}

		$res = call_user_func( array( $bot_api, $api_method ), $api_params );

		if ( is_wp_error( $res ) ) {

			$body = array(
				'ok'          => false,
				'error_code'  => 500,
				'description' => $res->get_error_code() . ' - ' . $res->get_error_message(),
			);
			$code = $body['error_code'];

		} else {

			$body = $res->get_decoded_body();
			$code = $res->get_response_code();
		}

		return new WP_REST_Response( $body, $code );
	}

	/**
	 * Retrieves the query params for the settings.
	 *
	 * @since 1.2.2
	 *
	 * @return array Query parameters for the settings.
	 */
	public static function get_test_params() {
		return array(
			'bot_token'  => array(
				'type'              => 'string',
				'required'          => true,
				'sanitize_callback' => 'sanitize_text_field',
				'validate_callback' => array( __CLASS__, 'validate_param' ),
			),
			'api_params' => array(
				'type'              => 'object',
				'sanitize_callback' => array( __CLASS__, 'sanitize_param' ),
				'validate_callback' => 'rest_validate_request_arg',
			),
		);
	}

	/**
	 * Validate params.
	 *
	 * @since 1.2.2
	 *
	 * @param mixed           $value   Value of the param.
	 * @param WP_REST_Request $request WP REST API request.
	 * @param string          $key     Param key.
	 */
	public static function validate_param( $value, WP_REST_Request $request, $key ) {
		switch ( $key ) {
			case 'bot_token':
				$pattern = WPTelegram_Bot_API::BOT_TOKEN_REGEX;
				break;
		}

		return (bool) preg_match( $pattern, $value );
	}

	/**
	 * Sanitize params.
	 *
	 * @since 1.2.2
	 *
	 * @param mixed $input Value of the param.
	 */
	public static function sanitize_param( $input ) {
		if ( is_array( $input ) ) {
			foreach ( $input as $key => $value ) {
				$input[ sanitize_text_field( $key ) ] = self::sanitize_param( $value );
			}
		} else {
			$input = sanitize_text_field( $input );
		}

		return $input;
	}
}

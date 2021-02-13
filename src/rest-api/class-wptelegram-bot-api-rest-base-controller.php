<?php // phpcs:ignore -- class name hyphens
/**
 * WP REST API functionality of the plugin.
 *
 * @link       https://t.me/manzoorwanijk
 * @since      1.2.2
 *
 * @package    WPTelegram_Bot_API
 * @subpackage WPTelegram_Bot_API/rest-api
 */

/**
 * Base class for all the endpoints.
 *
 * @since 1.2.2
 *
 * @package    WPTelegram_Bot_API
 * @subpackage WPTelegram_Bot_API/rest-api
 * @author     Manzoor Wani <@manzoorwanijk>
 */
abstract class WPTelegram_Bot_API_REST_Base_Controller extends WP_REST_Controller {

	/**
	 * The namespace of this controller's route.
	 *
	 * @since 1.2.2
	 * @var string
	 */
	protected $namespace = 'wptelegram-bot/v1';

	/**
	 * The base of this controller's route.
	 *
	 * @var string
	 */
	protected $rest_base;
}

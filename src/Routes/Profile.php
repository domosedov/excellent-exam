<?php


namespace Domosed\EEC\Routes;


use WP_Error;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

class Profile extends WP_REST_Controller {
	public function __construct() {
		$this->namespace = EXCELLENT_EXAM_CORE_API_NAMESPACE;
		$this->rest_base = 'profiles';
	}

	public function register_routes() {
		register_rest_route( $this->namespace, $this->rest_base, [
			[
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'create_item' ),
				'permission_callback' => array( $this, 'create_item_permissions_check' ),
				'args'                => [
					'firstName' => [
						'type'     => 'string',
						'default'  => '',
						'required' => true
					]
				]
			]
		] );
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	public function create_item( $request ) {

		$firstName = $request->get_param( 'firstName' );
		$avatar = $request->get_file_params()['avatar'];

		if (empty($avatar['error'])) {
			$uploadedImageId = handleUploadImageFile($avatar, 20);
		}

		return rest_ensure_response( [ 'message' => $uploadedImageId ?? false, 'user' => $firstName ] );
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return true|WP_Error
	 */
	public function create_item_permissions_check( $request ) {
		return true;
	}
}
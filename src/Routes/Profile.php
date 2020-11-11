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
		register_rest_route($this->namespace, $this->rest_base, [
			[
				'methods' => WP_REST_Server::CREATABLE,
				'callback' => array($this, 'create_item'),
				'permission_callback' => array($this, 'create_item_permissions_check'),
				'args' => [
					'firstName' => [
						'type' => 'string',
						'default' => '',
						'required' => true
					]
				]
			]
		]);
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return false|WP_Error|WP_REST_Response
	 */
	public function create_item( $request  ) {

		$r = $request;
		$firstName = $request->get_param('firstName');
		$uploadedFile = $request->get_file_params()['avatar'];
		$parentPostId = 20;

		require_once(ABSPATH . 'wp-admin/includes/media.php');
		require_once(ABSPATH . 'wp-admin/includes/file.php');
		require_once(ABSPATH . 'wp-admin/includes/image.php');

		$uploadOverrides = array('test_form' => false);

		$file = wp_handle_upload($uploadedFile, $uploadOverrides);

		if (isset($file['error'])) {
			return false;
		}

		$url = $file['url'];
		$type = $file['type'];
		$file = $file['file'];
		$filename = basename($file);

		$object = array(
			'post_title' => $filename,
			'post_mime_type' => $type,
			'guid' => $url
		);

		$id = wp_insert_attachment($object, $file, $parentPostId);

		wp_update_attachment_metadata($id, wp_generate_attachment_metadata($id, $file));

		return rest_ensure_response(['message' => $id]);
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
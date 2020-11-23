<?php


namespace Domosed\EEC\Routes;


use WP_Error;
use WP_REST_Request;
use WP_REST_Server;

class Upload {
	public const ACCEPTED_FILE_TYPES = [ 'image/jpeg', 'image/png', 'image/gif' ];
	public const MAX_FILE_SIZE = 2097152; // 2 MB

	public function __construct() {
		$this->namespace = EXCELLENT_EXAM_CORE_API_NAMESPACE;
		$this->rest_base = 'upload';
	}

	/**
	 * @return void
	 */
	public function registerRoutes(): void {
		register_rest_route( $this->namespace, $this->rest_base . '/profiles', [
			[
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'uploadProfileFiles' ),
				'permission_callback' => array( $this, 'checkUploadProfileFilesPermission' ),
				'args'                => [
					'profileId' => [
						'type'        => 'integer',
						'default'     => 0,
						'description' => 'ID Профиля',
						'required'    => true
					]
				]
			]
		] );
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return bool
	 */
	public function checkUploadProfileFilesPermission( WP_REST_Request $request ): bool {
		return true;
		//TODO Add auth
	}

	/**
	 * @param WP_REST_Request $request
	 */
	public function uploadProfileFiles( WP_REST_Request $request ) {

		$profileId = postIsExists( $request->get_param( 'profileId' ) ) ? (int) $request->get_param( 'profileId' ) : 0;

		$avatar    = $request->get_file_params()['avatar'];
		$documents = $request->get_file_params()['documents'];

		if ( ! empty( $avatar ) ) {
			if ( is_wp_error( $this->validateFile( $avatar ) ) ) {
				return $this->validateFile( $avatar );
			}

			/*
			 * Upload Image
			 */
			$avatarId = handleUploadImageFile( $avatar, $profileId, true );
			if ( is_wp_error( $avatarId ) ) {
				return $avatarId;
			}

			/*
			 * Set Profile Avatar ID Meta
			 */
			if ( ! empty( $profileId ) ) {
				$isSuccess = setProfileAvatar( $profileId, $avatarId, true );
				if ( is_wp_error( $isSuccess ) ) {
					return $isSuccess;
				}
			}
		}

		if ( ! ( empty( $documents ) ) ) {

			$documents = normalizeMultipleFileUpload( $documents);

			$d = $documents;

		}


		return rest_ensure_response( [ 'profileId' => $profileId ] );
	}

	public function validateFile( $file ) {
		if ( ! in_array( $file['type'], self::ACCEPTED_FILE_TYPES, true ) ) {
			return new WP_Error( EXCELLENT_EXAM_CORE_PREFIX . 'route_error', 'Invalid image type' );
		}

		if ( $file['size'] > self::MAX_FILE_SIZE ) {
			return new WP_Error( EXCELLENT_EXAM_CORE_PREFIX . 'route_error', 'Invalid image size' );
		}

		return true;
	}

	public function validateFiles( $files ) {
		$isValid = true;

		foreach ( $files as $file ) {
			$isValid = $this->validateFile( $file );
		}

		return $isValid;
	}
}
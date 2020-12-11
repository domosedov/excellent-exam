<?php


namespace Domosed\EEC\Modules\common;


use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

class Upload {
	public const ACCEPTED_FILE_TYPES = [ 'image/jpeg', 'image/png', 'image/gif' ];
	public const MAX_FILE_SIZE = 2097152; // 2 MB
	/**
	 * @var string
	 */
	private $namespace;
	/**
	 * @var string
	 */
	private $rest_base;

	public function __construct() {
		$this->namespace = EEC_API_NAMESPACE;
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

		register_rest_route( $this->namespace, $this->rest_base, [
			[
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'myUpload' ),
				'permission_callback' => array( $this, 'myUploadPermission' )
			]
		] );
	}

	function myUpload( $request ) {
		$r = $request->get_file_params();



		return rest_ensure_response(['sdf' => 'sdf']);
	}

	function myUploadPermission( $request ) {
		return true;
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return bool
	 */
	public function checkUploadProfileFilesPermission( $request ): bool {
		return true;
		//TODO Add auth
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	public function uploadProfileFiles( WP_REST_Request $request ) {

		$profileId = entityIsExists( $request->get_param( 'profileId' ), EEC_PREFIX . 'profile' ) ? (int) $request->get_param( 'profileId' ) : 0;

		if ( empty( $profileId ) ) {
			return new WP_Error( EEC_PREFIX . 'route_error', 'Профиль не существует' );
		}

		$avatar    = $request->get_file_params()['avatar'];
		$documents = $request->get_file_params()['documents'];

		$response = [ 'message' => 'success' ];

		if ( empty( $documents ) && empty( $avatar ) ) {
			return new WP_Error( EEC_PREFIX . 'route_error', 'Empty Files' );
		}

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

			$isSuccess = setProfileAvatar( $profileId, $avatarId, true );
			if ( is_wp_error( $isSuccess ) ) {
				return $isSuccess;
			}

			$response['avatarId'] = $avatarId;

		}

		if ( ! ( empty( $documents ) ) ) {

			$documents = normalizeMultipleFileUpload( $documents );

			/*
			 * Check Files
			 */
			foreach ( $documents as $document ) {
				$allIsValid = $this->validateFile( $document );

				if ( is_wp_error( $allIsValid ) ) {
					return $allIsValid; // Error
				}
			}

			/*
			 * If All files valid -> upload
			 */
			$documentIds = [];
			foreach ( $documents as $document ) {
				$documentId = handleUploadImageFile( $document, $profileId, true );
				if ( is_wp_error( $documentId ) ) {
					continue; // Skip Invalid Files
				}

				$documentIds[] = $documentId;
				if ( empty( $documentIds ) ) {
					return new WP_Error( EEC_PREFIX . 'route_error', 'Invalid images' );
				}
				$documentsIsSet = setProfileDocuments( $profileId, $documentIds, true );
				if ( is_wp_error( $documentsIsSet ) ) {
					return $documentsIsSet; // Error
				}
			}

			$response['documentIds'] = $documentIds;

		}


		return rest_ensure_response( $response );
	}

	/**
	 * @param mixed $file
	 *
	 * @return bool|WP_Error
	 */
	public function validateFile( $file ) {
		if ( ! in_array( $file['type'], self::ACCEPTED_FILE_TYPES, true ) ) {
			return new WP_Error( EEC_PREFIX . 'route_error', 'Invalid image type' );
		}

		if ( $file['size'] > self::MAX_FILE_SIZE ) {
			return new WP_Error( EEC_PREFIX . 'route_error', 'Invalid image size' );
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
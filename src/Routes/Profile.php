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
				'args'                => $this->getCreateProfileArgs()
			]
		] );
	}

	public function getCreateProfileArgs() {
		return [
			'firstName'  => [
				'type'        => 'string',
				'description' => 'Имя',
				'default'     => '',
				'required'    => true,
				'sanitize_callback' => [$this, 'sanitizeArgs'],
				'validate_callback' => [$this, 'validateArgs']
			],
			'middleName' => [
				'type'        => 'string',
				'description' => 'Отчество',
				'default'     => '',
				'required'    => true,
				'sanitize_callback' => [$this, 'sanitizeArgs'],
				'validate_callback' => [$this, 'validateArgs']
			],
			'lastName'  => [
				'type'        => 'string',
				'description' => 'Фамилия',
				'default'     => '',
				'required'    => true,
				'sanitize_callback' => [$this, 'sanitizeArgs'],
				'validate_callback' => [$this, 'validateArgs']
			],
			'phone' => [
				'type'        => 'string',
				'description' => 'Телефон',
				'default'     => '',
				'required'    => true,
				'sanitize_callback' => [$this, 'sanitizeArgs'],
				'validate_callback' => [$this, 'validateArgs']
			],
			'email'  => [
				'type'        => 'string',
				'description' => 'Email',
				'default'     => '',
				'required'    => true,
				'sanitize_callback' => [$this, 'sanitizeArgs'],
				'validate_callback' => [$this, 'validateArgs']
			],
			'education' => [
				'type'        => 'string',
				'description' => 'Образование',
				'default'     => '',
				'required'    => true,
				'sanitize_callback' => [$this, 'sanitizeArgs'],
				'validate_callback' => [$this, 'validateArgs']
			],
			'birthYear'  => [
				'type'        => 'integer',
				'description' => 'Год рождения',
				'default'     => 0,
				'required'    => true,
				'sanitize_callback' => [$this, 'sanitizeArgs'],
				'validate_callback' => [$this, 'validateArgs']
			],
			'hourlyRate' => [
				'type'        => 'integer',
				'description' => 'Ставка в час',
				'default'     => 0,
				'required'    => true,
				'sanitize_callback' => [$this, 'sanitizeArgs'],
				'validate_callback' => [$this, 'validateArgs']
			],
			'experience'  => [
				'type'        => 'integer',
				'description' => 'Год начала деятельности',
				'default'     => 0,
				'required'    => true,
				'sanitize_callback' => [$this, 'sanitizeArgs'],
				'validate_callback' => [$this, 'validateArgs']
			],
			'city' => [
				'type'        => 'integer',
				'description' => 'ID город',
				'default'     => 0,
				'required'    => true,
				'sanitize_callback' => [$this, 'sanitizeArgs'],
				'validate_callback' => [$this, 'validateArgs']
			],
			'status' => [
				'type'        => 'integer',
				'description' => 'ID Статус',
				'default'     => 0,
				'required'    => true,
				'sanitize_callback' => [$this, 'sanitizeArgs'],
				'validate_callback' => [$this, 'validateArgs']
			],
			'places'  => [
				'type'        => 'array',
				'items' => [
					'type' => 'integer'
				],
				'description' => 'Ids Места занятий',
				'default'     => [],
				'required'    => true,
				'sanitize_callback' => [$this, 'sanitizeArgs'],
				'validate_callback' => [$this, 'validateArgs']
			],
			'subjects'  => [
				'type'        => 'array',
				'items' => [
					'type' => 'integer'
				],
				'description' => 'IDs Предметы',
				'default'     => [],
				'required'    => true,
				'sanitize_callback' => [$this, 'sanitizeArgs'],
				'validate_callback' => [$this, 'validateArgs']
			],
			'students'  => [
				'type'        => 'array',
				'items' => [
					'type' => 'integer'
				],
				'description' => 'IDs Категории учеников',
				'default'     => [],
				'required'    => true,
				'sanitize_callback' => [$this, 'sanitizeArgs'],
				'validate_callback' => [$this, 'validateArgs']
			],
			'metro' => [
				'type'        => 'integer',
				'description' => 'ID Метро',
				'default'     => 0,
				'required'    => false,
				'sanitize_callback' => [$this, 'sanitizeArgs'],
				'validate_callback' => [$this, 'validateArgs']
			],
			'area' => [
				'type'        => 'string',
				'description' => 'Район',
				'default'     => '',
				'required'    => false,
				'sanitize_callback' => [$this, 'sanitizeArgs'],
				'validate_callback' => [$this, 'validateArgs']
			],
			'description' => [
				'type'        => 'string',
				'description' => 'Дополнительно',
				'default'     => '',
				'required'    => false,
				'sanitize_callback' => [$this, 'sanitizeArgs'],
				'validate_callback' => [$this, 'validateArgs']
			],
		];
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	public function create_item( $request ) {

		$firstName = $request->get_param( 'firstName' );
		$avatar    = $request->get_file_params()['avatar'];

		if ( empty( $avatar['error'] ) ) {
			$uploadedImageId = handleUploadImageFile( $avatar, 20 );
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

	/**
	 * @param mixed $value
	 * @param WP_REST_Request $request
	 * @param string $param
	 * @return mixed
	 */
	public function sanitizeArgs($value, $request, $param) {
		return $value;
	}

	/**
	 * @param mixed $value
	 * @param WP_REST_Request $request
	 * @param string $param
	 * @return true|WP_Error
	 */
	public function validateArgs($value, $request, $param) {
		return true;
	}
}
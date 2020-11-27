<?php


namespace Domosed\EEC\Routes;


use WP_Error;
use WP_Query;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

class Vacancy extends WP_REST_Controller {

	public function __construct() {
		$this->namespace = EXCELLENT_EXAM_CORE_API_NAMESPACE;
		$this->rest_base = 'vacancies';
	}

	/**
	 * @return void
	 */
	public function register_routes(): void {
		register_rest_route( $this->namespace, $this->rest_base, [
			[
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'create_item' ),
				'permission_callback' => array( $this, 'create_item_permissions_check' ),
				'args'                => $this->getCreateVacancyArgs()
			],
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_items' ),
				'permission_callback' => array( $this, 'get_items_permissions_check' ),
//				'args'                => $this->getCreateVacancyArgs()
			]
		] );
	}

	public function getCreateVacancyArgs(): array {
		return [
			'firstName'         => [
				'type'              => 'string',
				'description'       => 'Имя',
				'default'           => '',
				'required'          => true,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
			'lastName'          => [
				'type'              => 'string',
				'description'       => 'Фамилия',
				'default'           => '',
				'required'          => true,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
			'phone'             => [
				'type'              => 'string',
				'description'       => 'Телефон',
				'default'           => '',
				'required'          => true,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
			'email'             => [
				'type'              => 'string',
				'description'       => 'Email',
				'default'           => '',
				'required'          => true,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
			'hourlyRate'        => [
				'type'              => 'integer',
				'description'       => 'Ставка в час',
				'default'           => 0,
				'required'          => false,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
			'city'              => [
				'type'              => 'integer',
				'description'       => 'ID город',
				'default'           => 0,
				'required'          => true,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
			'gender'            => [
				'type'              => 'integer',
				'description'       => 'ID Пол',
				'default'           => 0,
				'required'          => false,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
			'place'             => [
				'type'              => 'integer',
				'description'       => 'Id Место занятий',
				'default'           => 0,
				'required'          => true,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
			'subject'           => [
				'type'              => 'integer',
				'description'       => 'ID Предмет',
				'default'           => 0,
				'required'          => true,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
			'student'           => [
				'type'              => 'integer',
				'description'       => 'ID Категория ученика',
				'default'           => 0,
				'required'          => true,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
			'metro'             => [
				'type'              => 'integer',
				'description'       => 'ID Метро',
				'default'           => 0,
				'required'          => false,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
			'area'              => [
				'type'              => 'string',
				'description'       => 'Район',
				'default'           => '',
				'required'          => false,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
			'description'       => [
				'type'              => 'string',
				'description'       => 'Дополнительно',
				'default'           => '',
				'required'          => false,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
			'purpose'           => [
				'type'              => 'string',
				'description'       => 'Цель занятий',
				'default'           => '',
				'required'          => false,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
			'selectedProfileId' => [
				'type'              => 'integer',
				'description'       => 'Выбранный профиль',
				'default'           => '',
				'required'          => false,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
		];
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	public function create_item( $request ) {

		$args = [
			'firstName'         => $request->get_param( 'firstName' ),
			'lastName'          => $request->get_param( 'lastName' ),
			'email'             => $request->get_param( 'email' ),
			'phone'             => $request->get_param( 'phone' ),
			'area'              => $request->get_param( 'area' ),
			'purpose'           => $request->get_param( 'purpose' ),
			'description'       => $request->get_param( 'description' ),
			'hourlyRate'        => $request->get_param( 'hourlyRate' ),
			'cityTermId'        => $request->get_param( 'city' ),
			'genderTermId'      => $request->get_param( 'gender' ),
			'metroTermId'       => $request->get_param( 'metro' ),
			'placeTermId'       => $request->get_param( 'place' ),
			'subjectTermId'     => $request->get_param( 'subject' ),
			'studentTermId'     => $request->get_param( 'student' ),
			'selectedProfileId' => $request->get_param( 'selectedProfileId' ),
		];


		$newVacancyId = createVacancy( $args, true );

		if ( is_wp_error( $newVacancyId ) ) {
			return $newVacancyId;
		}

		return rest_ensure_response( [
			'message'   => 'success',
			'vacancyId' => $newVacancyId
		] );
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
	 *
	 * @return mixed
	 */
	public function sanitizeArgs( $value, $request, $param ) {
		switch ( $param ) {
			case 'firstName':
			case 'lastName':
			case 'area':
			case 'phone':
				return sanitize_text_field( $value );
			case 'email':
				return sanitize_email( $value );
			case 'purpose':
			case 'description':
				return sanitize_textarea_field( $value );
			case 'hourlyRate':
			case 'metro':
			case 'city':
			case 'selectedProfileId':
			case 'gender':
			case 'student':
			case 'subject':
			case 'place':
				return absint( $value );
			default:
				return $value;
		}
	}

	/**
	 * @param array $arr
	 *
	 * @return int[] Возвращает массив целых чисел или пустой массив
	 */
	public function sanitizeArrayOfInteger( array $arr ): array {
		if ( ! is_array( $arr ) ) {
			return [];
		}

		if ( empty( $arr ) ) {
			return [];
		}

		$output = array_filter( array_map( static function ( $item ) {
			return is_numeric( $item ) ? absint( $item ) : 0;
		}, $arr ), static function ( $item ) {
			return $item > 0;
		} );

		return ( count( $output ) === count( $arr ) ) ? $output : [];
	}

	/**
	 * @param mixed $value
	 * @param WP_REST_Request $request
	 * @param string $param
	 *
	 * @return true|WP_Error
	 */
	public function validateArgs( $value, WP_REST_Request $request, string $param ) {
		switch ( $param ) {
			case 'firstName':
			case 'lastName':
			case 'phone':
				//TODO ADD PHONE REGEXP
				return is_string( $value ) && ! empty( $value );
			case 'email':
				return is_email( $value ) && ! empty( $value );
			case 'hourlyRate':
				return ! empty( absint( $value ) );
			case 'city':
			case 'student':
			case 'subject':
				return $this->isValidTermId( $value, $param );
			case 'place':
			case 'gender':
			case 'metro':
				return empty( absint( $value ) ) ? true : $this->isValidTermId( $value, $param );
			case 'selectedProfileId':
				return empty( absint( $value ) ) ? true : ( get_post_type( $value ) === EXCELLENT_EXAM_CORE_PREFIX . 'profile' );
			default:
				return true;
		}
	}

	/**
	 * @param string|int $value Argument value
	 * @param string $param Argument key
	 *
	 * @return bool
	 */
	public function isValidTermId( $value, string $param ): bool {
		return in_array( absint( $value ), get_terms( [
			'fields'     => 'ids',
			'hide_empty' => false,
			'taxonomy'   => EXCELLENT_EXAM_CORE_PREFIX . $param
		] ), true );
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return void|WP_Error|WP_REST_Response
	 */
	public function get_items( $request ) {
		$args = [
			'post_type'   => EXCELLENT_EXAM_CORE_PREFIX . 'vacancy',
			'post_status' => 'any',
			'fields'      => 'ids'
		];

		$query = new WP_Query( $args );

		$result = $query->get_posts();

		return rest_ensure_response( array_map( static function ( $vacancyId ) {
			return getVacancy( $vacancyId );
		}, $result ) );
	}

	public function get_items_permissions_check( $request ) {
		return true;
	}
}
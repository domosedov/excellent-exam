<?php


namespace Domosed\EEC\Modules\Profiles;


use WP_Error;
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

class ProfilesController extends WP_REST_Controller {
	public const ACCEPTED_FILE_TYPES = [ 'image/jpeg', 'image/png', 'image/gif' ];
	public const MAX_FILE_SIZE = 2097152; // 2 MB
	public const DEFAULT_PER_PAGE = 10;
	private $profilesService;


	public function __construct( $profilesService ) {
		$this->profilesService = $profilesService;
		$this->namespace       = EEC_API_NAMESPACE;
		$this->restBase        = 'profiles';
	}

	/**
	 * @return void
	 */
	public function register_routes(): void {
		register_rest_route( $this->namespace, $this->restBase, [
			[
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'create_item' ),
				'permission_callback' => array( $this, 'create_item_permissions_check' ),
				'args'                => $this->getCreateProfileArgs()
			],
			[
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => [ $this->profilesService, 'getItems' ],
				'permission_callback' => [ $this->profilesService, 'getItemsPermissionsCheck' ],
				'args'                => $this->getReadProfilesArgs()
			]
		] );
	}

	public function getCreateProfileArgs(): array {
		return [
			'firstName'   => [
				'type'              => 'string',
				'description'       => 'Имя',
				'default'           => '',
				'required'          => true,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
			'middleName'  => [
				'type'              => 'string',
				'description'       => 'Отчество',
				'default'           => '',
				'required'          => true,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
			'lastName'    => [
				'type'              => 'string',
				'description'       => 'Фамилия',
				'default'           => '',
				'required'          => true,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
			'phone'       => [
				'type'              => 'string',
				'description'       => 'Телефон',
				'default'           => '',
				'required'          => true,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
			'email'       => [
				'type'              => 'string',
				'description'       => 'Email',
				'default'           => '',
				'required'          => true,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
			'education'   => [
				'type'              => 'string',
				'description'       => 'Образование',
				'default'           => '',
				'required'          => true,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
			'birthYear'   => [
				'type'              => 'integer',
				'description'       => 'Год рождения',
				'default'           => 0,
				'required'          => true,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
			'hourlyRate'  => [
				'type'              => 'integer',
				'description'       => 'Ставка в час',
				'default'           => 0,
				'required'          => true,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
			'experience'  => [
				'type'              => 'integer',
				'description'       => 'Год начала деятельности',
				'default'           => 0,
				'required'          => true,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
			'city'        => [
				'type'              => 'integer',
				'description'       => 'ID город',
				'default'           => 0,
				'required'          => true,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
			'status'      => [
				'type'              => 'integer',
				'description'       => 'ID Статус',
				'default'           => 0,
				'required'          => true,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
			'gender'      => [
				'type'              => 'integer',
				'description'       => 'ID Пол',
				'default'           => 0,
				'required'          => true,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
			'place'       => [
				'type'              => 'array',
				'items'             => [
					'type' => 'integer'
				],
				'description'       => 'Ids Места занятий',
				'default'           => [],
				'required'          => true,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
			'subject'     => [
				'type'              => 'array',
				'items'             => [
					'type' => 'integer'
				],
				'description'       => 'IDs Предметы',
				'default'           => [],
				'required'          => true,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
			'student'     => [
				'type'              => 'array',
				'items'             => [
					'type' => 'integer'
				],
				'description'       => 'IDs Категории учеников',
				'default'           => [],
				'required'          => true,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
			'metro'       => [
				'type'              => 'integer',
				'description'       => 'ID Метро',
				'default'           => 0,
				'required'          => false,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
			'area'        => [
				'type'              => 'string',
				'description'       => 'Район',
				'default'           => '',
				'required'          => false,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
			'description' => [
				'type'              => 'string',
				'description'       => 'Дополнительно',
				'default'           => '',
				'required'          => false,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
		];
	}

	public function getReadProfilesArgs(): array {
		return [
			'perPage' => [
				'type'              => 'integer',
				'description'       => 'Items for page',
				'default'           => self::DEFAULT_PER_PAGE,
				'required'          => false,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
			'page'    => [
				'type'              => 'integer',
				'description'       => 'Page number',
				'default'           => 1,
				'required'          => false,
				'sanitize_callback' => [ $this, 'sanitizeArgs' ],
				'validate_callback' => [ $this, 'validateArgs' ]
			],
			'filter'  => [
				'type'       => 'object',
				'properties' => [
					'subject' => [
						'description' => 'Предмет репетитора',
						'type'        => 'integer',
						'default'     => 0
					],
					'gender'  => [
						'description' => 'Пол репетитора',
						'type'        => 'integer',
						'default'     => 0
					],
					'place'   => [
						'description' => 'Место занятий',
						'type'        => 'integer',
						'default'     => 0
					],
					'city'    => [
						'description' => 'Город',
						'type'        => 'integer',
						'default'     => 0
					],
					'metro'   => [
						'description' => 'Метро',
						'type'        => 'integer',
						'default'     => 0
					],
					'rate'    => [
						'description' => 'Цена',
						'type'        => 'integer',
						'default'     => 0
					],
					'student' => [
						'description' => 'Категория ученика',
						'type'        => 'integer',
						'default'     => 0
					]
				]
			]
		];
	}

	/**
	 * @param WP_REST_Request $request
	 *
	 * @return WP_REST_Response|WP_Error
	 */
	public function create_item( $request ) {
		/*
		 * Mock user id
		 */
		$userId = 1;

		$args = [
			'ownerUserId'    => $userId,
			'firstName'      => $request->get_param( 'firstName' ),
			'middleName'     => $request->get_param( 'middleName' ),
			'lastName'       => $request->get_param( 'lastName' ),
			'email'          => $request->get_param( 'email' ),
			'phone'          => $request->get_param( 'phone' ),
			'area'           => $request->get_param( 'area' ),
			'education'      => $request->get_param( 'education' ),
			'description'    => $request->get_param( 'description' ),
			'birthYear'      => $request->get_param( 'birthYear' ),
			'hourlyRate'     => $request->get_param( 'hourlyRate' ),
			'experience'     => $request->get_param( 'experience' ),
			'cityTermId'     => $request->get_param( 'city' ),
			'genderTermId'   => $request->get_param( 'gender' ),
			'metroTermId'    => $request->get_param( 'metro' ),
			'statusTermId'   => $request->get_param( 'status' ),
			'placeTermIds'   => $request->get_param( 'place' ),
			'subjectTermIds' => $request->get_param( 'subject' ),
			'studentTermIds' => $request->get_param( 'student' ),
		];


		$newProfileId = createProfile( $userId, $args, true );

		if ( is_wp_error( $newProfileId ) ) {
			return $newProfileId;
		}

		return rest_ensure_response( [
			'message'   => 'success',
			'profileId' => $newProfileId
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
			case 'middleName':
			case 'lastName':
			case 'area':
			case 'phone':
				return sanitize_text_field( $value );
			case 'email':
				return sanitize_email( $value );
			case 'education':
			case 'description':
				return sanitize_textarea_field( $value );
			case 'birthYear':
			case 'hourlyRate':
			case 'experience':
			case 'metro':
			case 'city':
			case 'status':
			case 'gender':
				return absint( $value );
			case 'student':
			case 'subject':
			case 'place':
				return $this->sanitizeArrayOfInteger( $value );
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
			case 'middleName':
			case 'lastName':
			case 'education':
			case 'phone':
				//TODO ADD PHONE REGEXP
				return is_string( $value ) && ! empty( $value );
			case 'email':
				return is_email( $value ) && ! empty( $value );
			case 'birthYear':
			case 'hourlyRate':
			case 'experience':
				return ! empty( absint( $value ) );
			case 'city':
			case 'status':
			case 'gender':
				return $this->isValidTermId( $value, $param );
			case 'metro':
				return empty( absint( $value ) ) ? true : $this->isValidTermId( $value, $param );
			case 'student':
			case 'subject':
			case 'place':
				return $this->isValidTermIds( $value, $param );
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
			'taxonomy'   => EEC_PREFIX . $param
		] ), true );
	}

	public function isValidTermIds( $values, $param ): bool {
		if ( ! is_array( $values ) || empty( $values ) ) {
			return false;
		}

		$termIds = get_terms( [
			'fields'     => 'ids',
			'hide_empty' => false,
			'taxonomy'   => EEC_PREFIX . $param
		] );

		foreach ( $values as $value ) {
			if ( ! in_array( absint( $value ), $termIds, true ) ) {
				return false;
			}
		}

		return true;
	}

	public function getItemsPermissionsCheck( $request ) {
		return true;
	}

	public function getItems( $request ) {
		return rest_ensure_response( [ 'message' => 'success' ] );
	}

}
<?php

use Ramsey\Uuid\Uuid;

/**
 * Handle upload image
 * Generate sizes and attach image to relation object by id
 *
 * @param array $uploadedFile single element of $_FILES
 * @param int $relationshipId Uploaded Image ID
 *
 * @param bool $wpError Return WP_Error
 *
 * @return int|WP_Error Return ID on success or WP_Error on failure
 */
function handleUploadImageFile( $uploadedFile, $relationshipId = 0, $wpError = false ) {

	/*
	 * Require core lib
	 */
	require_once( ABSPATH . 'wp-admin/includes/media.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/image.php' );

	/*
	 * Disable test mode
	 */
	$uploadOverrides = array( 'test_form' => false );

	/*
	 * Upload File
	 */
	$file = wp_handle_upload( $uploadedFile, $uploadOverrides );

	if ( isset( $file['error'] ) ) {
		if ( $wpError ) {
			return new WP_Error( EXCELLENT_EXAM_CORE_PREFIX . 'functions_error', 'Не удалось обработать загрузку изображения.', $file['error'] );
		}

		return false;
	}


	$uuid     = Uuid::uuid4();
	$url      = $file['url'];
	$type     = $file['type'];
	$file     = $file['file'];
	$filename = 'image-' . $uuid->toString();

	$args = [
		'post_title'     => $filename,
		'post_name'      => $filename,
		'post_mime_type' => $type,
		'guid'           => $url
	];

	/*
	 * Insert an attachment
	 */
	$id = wp_insert_attachment( $args, $file, $relationshipId, $wpError );

	/*
	 * Generate attachment meta data and create image sub-sizes for images
	 */
	if ( $wpError ) {
		if ( ! is_wp_error( $id ) ) {
			wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $file ) );
		}
	} else if ( ! empty( $id ) ) {
		wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $file ) );
	}


	return $id;
}

/**
 * Create user profile
 *
 * @param int $userId User ID
 * @param array $metaArgs Meta inputs args
 * @param bool $wpError Возвращать ошибку в случае неудачи
 *
 * @return int|bool|WP_Error Return Profile Id on success or False or WP_Error on failure
 */
function createProfile( $userId, $metaArgs, $wpError = false ) {
	if ( ! userIsExists( $userId ) ) {
		if ( $wpError ) {
			return new WP_Error( EXCELLENT_EXAM_CORE_PREFIX . 'functions_error', 'Неверный ID пользователя' );
		}

		return false;
	}

	$errors = [];

	$requiredFields = [
		'ownerUserId',
		'firstName',
		'middleName',
		'lastName',
		'email',
		'phone',
		'education',
		'birthYear',
		'hourlyRate',
		'experience',
		'statusTermId',
		'cityTermId',
		'genderTermId',
		'placeTermIds',
		'subjectTermIds',
		'studentTermIds',
	];

	$uuid = Uuid::uuid4();

	$defaultMetaArgs = [
		'uuid'                  => $uuid->toString(),
		'ownerUserId'           => 0,
		'firstName'             => '',
		'middleName'            => '',
		'lastName'              => '',
		'email'                 => '',
		'phone'                 => '',
		'area'                  => '',
		'education'             => '',
		'description'           => '',
		'birthYear'             => 0,
		'hourlyRate'            => 0,
		'experience'            => 0,
		'cityTermId'            => 0,
		'genderTermId'          => 0,
		'metroTermId'           => 0,
		'statusTermId'          => 0,
		'placeTermIds'          => [],
		'subjectTermIds'        => [],
		'studentTermIds'        => [],
		'markTermIds'           => [],
		'avatarAttachmentId'    => 0,
		'documentAttachmentIds' => []
	];

	$args = wp_parse_args( $metaArgs, $defaultMetaArgs );

	/*
	 * Check required fields
	 */
	foreach ( $requiredFields as $fieldName ) {
		if ( empty( $args[ $fieldName ] ) ) {
			$errors[ $fieldName ] = $fieldName . ' is required';
		}
	}

	/*
	 * if errors return WP_Error
	 */
	if ( ! empty( $errors ) ) {
		if ( $wpError ) {
			return new WP_Error( EXCELLENT_EXAM_CORE_PREFIX . 'functions_error', 'Отсутсвуют следующие обязательные поля', $errors );
		}

		return false;

	}

	$defaultArgs = [
		'post_title'  => $args['firstName'] . ' ' . $args['lastName'],
		'post_type'   => EXCELLENT_EXAM_CORE_PREFIX . 'profile',
		'post_status' => 'pending',
	];

	return wp_insert_post( wp_parse_args( [
		'meta_input'  => $args,
		'post_author' => absint( $userId )
	], $defaultArgs ), $wpError );
}

/**
 * Check user is exists
 *
 * @param int|string $userId User ID
 *
 * @return bool
 */
function userIsExists( $userId ) {
	return ! ( get_userdata( absint( $userId ) ) === false );
}

if ( ! function_exists( 'setProfileAvatar' ) ) {
	/**
	 * Устанавливает аватар профиля
	 *
	 * @param int $profileId ID Профиля
	 * @param int $imageAttachmentId ID Изображения
	 *
	 * @param bool $wpError Возвращать ошибку в случае неудачи
	 *
	 * @return bool|WP_Error Возвращет true в случае успеха или WP_Error при неудаче
	 */
	function setProfileAvatar( $profileId, $imageAttachmentId, $wpError = false ) {
		if ( entityIsExists( $profileId, EXCELLENT_EXAM_CORE_PREFIX . 'profile' ) && entityIsExists( $imageAttachmentId, 'attachment' ) ) {
			if ( ! update_metadata( 'post', absint( $profileId ), 'avatarAttachmentId', absint( $imageAttachmentId ) ) ) {
				if ( $wpError ) {
					return new WP_Error( EXCELLENT_EXAM_CORE_PREFIX . 'functions_error', 'Не удалось установить avatarAttachmentId', [
						'profileId' => $profileId,
						'metaKey'   => 'avatarAttachmentId',
						'metaValue' => $imageAttachmentId
					] );
				}

				return false;
			}

			return true;
		}

		if ( $wpError ) {
			return new WP_Error( EXCELLENT_EXAM_CORE_PREFIX . 'functions_error', 'Профиль или изображение не существует', [
				'profileId'         => $profileId,
				'imageAttachmentId' => $imageAttachmentId
			] );
		}

		return false;
	}
} else {
	return new WP_Error( EXCELLENT_EXAM_CORE_PREFIX . 'functions_error', 'Не удалось определить функцию setProfileAvatar' );
}

if ( ! function_exists( 'setProfileDocuments' ) ) {
	/**
	 * Устанавливает документы профиля
	 *
	 * @param int $profileId ID Профиля
	 * @param int[] $imageAttachmentIds
	 * @param bool $wpError Возвращать ошибку в случае неудачи
	 *
	 * @return bool|WP_Error Возвращет true в случае успеха или WP_Error при неудаче
	 */
	function setProfileDocuments( int $profileId, array $imageAttachmentIds, $wpError = false ) {
		if ( entityIsExists( $profileId, EXCELLENT_EXAM_CORE_PREFIX . 'profile' ) && entitiesIsExists( $imageAttachmentIds, 'attachment' ) ) {
			if ( ! update_metadata( 'post', absint( $profileId ), 'documentAttachmentIds', $imageAttachmentIds ) ) {
				if ( $wpError ) {
					return new WP_Error( EXCELLENT_EXAM_CORE_PREFIX . 'functions_error', 'Не удалось установить documentAttachmentIds', [
						'profileId' => $profileId,
						'metaKey'   => 'documentAttachmentIds',
						'metaValue' => $imageAttachmentIds
					] );
				}

				return false;
			}

			return true;
		}

		if ( $wpError ) {
			return new WP_Error( EXCELLENT_EXAM_CORE_PREFIX . 'functions_error', 'Профиль или изображение не существует', [
				'profileId'             => $profileId,
				'documentAttachmentIds' => $imageAttachmentIds
			] );
		}

		return false;
	}
} else {
	return new WP_Error( EXCELLENT_EXAM_CORE_PREFIX . 'functions_error', 'Не удалось определить функцию setProfileAvatar' );
}

if ( ! function_exists( 'entityIsExists' ) ) {
	/**
	 * @param int $id {Post Type Object} Id
	 *
	 * @return bool
	 */
	function entityIsExists( $id, $postType ) {
		global $wpdb;

		$id = absint( $id );
		/*
		 * Check post_type
		 */
		$validPostType = in_array( 'eec_vacancy', get_post_types(), true );

		if ( ! empty( $id ) && $validPostType ) {

			$result = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT post_status FROM $wpdb->posts WHERE ID = %d AND post_type = %s",
					$id, $postType
				)
			);

			return ! is_null( $result );
		}

		return false;
	}
} else {
	return new WP_Error( EXCELLENT_EXAM_CORE_PREFIX . 'functions_error', 'Не удалось определить функцию entityIsExists' );
}

if ( ! function_exists( 'entitiesIsExists' ) ) {
	/**
	 * @param int[] $entityIds {Post Type Object} Id
	 *
	 * @param string $postType
	 *
	 * @return bool
	 */
	function entitiesIsExists( array $entityIds, $postType = 'post' ) {
		/*
		 * Get all entities
		 */
		$args = [
			'post_type'      => $postType,
			'posts_per_page' => - 1,
			'post_status'    => 'any',
			'fields'         => 'ids'
		];

		$query = new WP_Query( $args );

		/*
		 * Ids array
		 */
		$allEntityIds = $query->get_posts();

		foreach ( $entityIds as $entityId ) {
			if ( ! in_array( $entityId, $allEntityIds, true ) ) {
				return false;
			}
		}

		return true;
	}
} else {
	return new WP_Error( EXCELLENT_EXAM_CORE_PREFIX . 'functions_error', 'Не удалось определить функцию entitiesIsExists' );
}

if ( ! function_exists( 'normalizeMultipleFileUpload' ) ) {
	function normalizeMultipleFileUpload( $files ) {
		$totalFiles = count( $files['name'] );
		$output     = [];

		for ( $i = 0; $i < $totalFiles; $i ++ ) {
			$output[] = [
				'name'     => $files['name'][ $i ],
				'type'     => $files['type'][ $i ],
				'tmp_name' => $files['tmp_name'][ $i ],
				'error'    => $files['error'][ $i ],
				'size'     => $files['size'][ $i ],
			];
		}

		return $output;
	}
}

if ( ! function_exists( 'createVacancy' ) ) {
	/**
	 * Create vacancy
	 *
	 * @param array $metaArgs Meta inputs args
	 * @param bool $wpError Возвращать ошибку в случае неудачи
	 *
	 * @return int|bool|WP_Error Return Profile Id on success or False or WP_Error on failure
	 */
	function createVacancy( $metaArgs, $wpError = false ) {
		$errors = [];

		$requiredFields = [
			'firstName',
			'lastName',
			'email',
			'phone',
			'cityTermId',
			'placeTermId',
			'subjectTermId',
			'studentTermId',
		];

		$uuid = Uuid::uuid4();

		$defaultMetaArgs = [
			'uuid'                => $uuid->toString(),
			'firstName'           => '',
			'lastName'            => '',
			'email'               => '',
			'phone'               => '',
			'area'                => '',
			'description'         => '',
			'purpose'             => '',
			'hourlyRate'          => 0,
			'cityTermId'          => 0,
			'genderTermId'        => 0,
			'metroTermId'         => 0,
			'selectedProfileId'   => 0,
			'executorProfileId'   => 0,
			'candidateProfileIds' => [],
			'placeTermId'         => 0,
			'subjectTermId'       => 0,
			'studentTermId'       => 0,
			'lessonIsScheduled'   => false,
			'lessonIsCompleted'   => false,
			'isCompleted'         => false,
			'confirmIsRequired'   => false,
		];

		$args = wp_parse_args( $metaArgs, $defaultMetaArgs );

		/*
		 * Check required fields
		 */
		foreach ( $requiredFields as $fieldName ) {
			if ( empty( $args[ $fieldName ] ) ) {
				$errors[ $fieldName ] = $fieldName . ' is required';
			}
		}

		/*
		 * if errors return WP_Error
		 */
		if ( ! empty( $errors ) ) {
			if ( $wpError ) {
				return new WP_Error( EXCELLENT_EXAM_CORE_PREFIX . 'functions_error', 'Отсутсвуют следующие обязательные поля', $errors );
			}

			return false;

		}

		$defaultArgs = [
			'post_title'  => get_term( $args['subjectTermId'] )->name . ': ' . $args['firstName'] . ' ' . $args['lastName'],
			'post_type'   => EXCELLENT_EXAM_CORE_PREFIX . 'vacancy',
			'post_status' => 'pending',
		];

		return wp_insert_post( wp_parse_args( [
			'meta_input' => $args,
		], $defaultArgs ), $wpError );
	}
}

if ( ! function_exists( 'getUserProfileId' ) ) {
	/**
	 * Возвращает ID профиля
	 *
	 * @param WP_User|int $user Объект пользователя или его ID
	 *
	 * @return int
	 */
	function getUserProfileId( $user ) {
		$userId = 0;

		if ( $user instanceof WP_User ) {
			$userId = $user->ID;
		} elseif ( is_numeric( $user ) && ! empty( absint( $user ) ) ) {
			$userId = (int) $user;
		}

		global $wpdb;

		$profileId = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %d ORDER BY meta_id DESC",
				'ownerUserId', $userId
			)
		);

		if ( ! empty( $profileId ) ) {
			return (int) $profileId;
		}

		return 0;
	}
} else {
	return new WP_Error( EXCELLENT_EXAM_CORE_PREFIX . 'functions_error', 'Не удалось определить функцию getUserProfileId' );
}

if ( ! function_exists( 'getImageUrls' ) ) {
	/**
	 * Получает ассоциативный массив изображения
	 *
	 * @param int|string $imageId
	 *
	 * @return array Image URLs
	 */
	function getImageUrls( $imageId ) {
		$imageId = absint( $imageId );

		if ( entityIsExists( $imageId, 'attachment' ) ) {
			return [
				'thumbnail' => wp_get_attachment_image_url( 56, 'thumbnail' ),
				'medium'    => wp_get_attachment_image_url( 56, 'medium' ),
				'large'     => wp_get_attachment_image_url( 56, 'large' ),
			];
		}

		return [];
	}
} else {
	return new WP_Error( EXCELLENT_EXAM_CORE_PREFIX . 'functions_error', 'Не удалось определить функцию getImageUrls' );
}

function normalizeDate( $date ) {
	return date( 'd.m.Y', strtotime( $date ) );
}

if ( ! function_exists( 'getProfile' ) ) {
	function getProfile( $profileId ) {
		global $wpdb;
		$output = [];
		if ( entityIsExists( $profileId, EXCELLENT_EXAM_CORE_PREFIX . 'profile' ) ) {


			$profile = get_post( $profileId );

			$profileMeta = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT meta_key, meta_value as 'value' FROM $wpdb->postmeta WHERE post_id = %d",
					$profileId
				),
				OBJECT_K
			);

			$output['id']         = $profile->ID;
			$output['authorId']   = (int) $profile->post_author;
			$output['postStatus'] = $profile->post_status;
			$output['created']    = normalizeDate( $profile->post_date );
			$output['modified']   = normalizeDate( $profile->post_modified );

			$output['uuid']        = $profileMeta['uuid']->value ?? '';
			$output['firstName']   = $profileMeta['firstName']->value ?? '';
			$output['middleName']  = $profileMeta['middleName']->value ?? '';
			$output['lastName']    = $profileMeta['lastName']->value ?? '';
			$output['email']       = $profileMeta['email']->value ?? '';
			$output['phone']       = $profileMeta['phone']->value ?? '';
			$output['area']        = $profileMeta['area']->value ?? '';
			$output['education']   = $profileMeta['education']->value ?? '';
			$output['description'] = $profileMeta['description']->value ?? '';
			$output['ownerUserId'] = ! empty( $profileMeta['ownerUserId'] ) ? (int) $profileMeta['ownerUserId']->value : 0;
			$output['birthYear']   = ! empty( $profileMeta['birthYear'] ) ? (int) $profileMeta['birthYear']->value : 0;
			$output['hourlyRate']  = ! empty( $profileMeta['hourlyRate'] ) ? (int) $profileMeta['hourlyRate']->value : 0;
			$output['experience']  = ! empty( $profileMeta['experience'] ) ? (int) $profileMeta['experience']->value : 0;

			$output['city']   = ! empty( $profileMeta['cityTermId'] ) ? getTermName( $profileMeta['cityTermId']->value ) : '';
			$output['gender'] = ! empty( $profileMeta['genderTermId'] ) ? getTermName( $profileMeta['genderTermId']->value ) : '';
			$output['metro']  = ! empty( $profileMeta['metroTermId'] ) ? getTermName( $profileMeta['metroTermId']->value ) : '';
			$output['status'] = ! empty( $profileMeta['statusTermId'] ) ? getTermName( $profileMeta['statusTermId']->value ) : '';

			$output['places']   = ( ! empty( $profileMeta['placeTermIds'] ) && is_serialized( $profileMeta['placeTermIds']->value ) ) ?
				getTermNames( maybe_unserialize( $profileMeta['placeTermIds']->value ) ) : '';
			$output['subjects'] = ( ! empty( $profileMeta['subjectTermIds'] ) && is_serialized( $profileMeta['subjectTermIds']->value ) ) ?
				getTermNames( maybe_unserialize( $profileMeta['subjectTermIds']->value ) ) : '';
			$output['students'] = ( ! empty( $profileMeta['studentTermIds'] ) && is_serialized( $profileMeta['studentTermIds']->value ) ) ?
				getTermNames( maybe_unserialize( $profileMeta['studentTermIds']->value ) ) : '';
			$output['marks']    = ( ! empty( $profileMeta['markTermIds'] ) && is_serialized( $profileMeta['markTermIds']->value ) ) ?
				getTermNames( maybe_unserialize( $profileMeta['markTermIds']->value ) ) : '';

			//TODO If empty return placeholder avatar
			$output['avatar'] = ! empty( $profileMeta['avatarAttachmentId'] ) ? getImageUrls( $profileMeta['avatarAttachmentId']->value ) : [];

			$output['documents'] = ( ! empty( $profileMeta['documentAttachmentIds'] ) && is_serialized( $profileMeta['documentAttachmentIds']->value ) ) ?
				array_map( static function ( $imageId ) {
					return getImageUrls( $imageId );
				}, maybe_unserialize( $profileMeta['documentAttachmentIds']->value ) ) : [];
		}

		return $output;
	}
} else {
	return new WP_Error( EXCELLENT_EXAM_CORE_PREFIX . 'functions_error', 'Не удалось определить функцию getProfile' );
}

if ( ! function_exists( 'getVacancy' ) ) {
	function getVacancy( $vacancyId ) {
		global $wpdb;
		$output = [];

		if ( entityIsExists( $vacancyId, EXCELLENT_EXAM_CORE_PREFIX . 'vacancy' ) ) {


			$vacancy = get_post( $vacancyId );

			$vacancyMeta = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT meta_key, meta_value as 'value' FROM $wpdb->postmeta WHERE post_id = %d",
					$vacancyId
				),
				OBJECT_K
			);

			$output['id']         = $vacancy->ID;
			$output['postStatus'] = $vacancy->post_status;
			$output['created']    = normalizeDate( $vacancy->post_date );
			$output['modified']   = normalizeDate( $vacancy->post_modified );

			$output['uuid']        = $vacancyMeta['uuid']->value ?? '';
			$output['firstName']   = $vacancyMeta['firstName']->value ?? '';
			$output['lastName']    = $vacancyMeta['lastName']->value ?? '';
			$output['email']       = $vacancyMeta['email']->value ?? '';
			$output['phone']       = $vacancyMeta['phone']->value ?? '';
			$output['area']        = $vacancyMeta['area']->value ?? '';
			$output['description'] = $vacancyMeta['description']->value ?? '';
			$output['purpose']     = $vacancyMeta['purpose']->value ?? '';
			$output['hourlyRate']  = ! empty( $vacancyMeta['hourlyRate'] ) ? (int) $vacancyMeta['hourlyRate']->value : 0;

			$output['city']        = ! empty( $vacancyMeta['cityTermId'] ) ? getTermName( $vacancyMeta['cityTermId']->value ) : '';
			$output['gender']      = ! empty( $vacancyMeta['genderTermId'] ) ? getTermName( $vacancyMeta['genderTermId']->value ) : '';
			$output['metro']       = ! empty( $vacancyMeta['metroTermId'] ) ? getTermName( $vacancyMeta['metroTermId']->value ) : '';
			$output['place']       = ! empty( $vacancyMeta['placeTermId'] ) ? getTermName( $vacancyMeta['placeTermId']->value ) : '';
			$output['subject']     = ! empty( $vacancyMeta['subjectTermId'] ) ? getTermName( $vacancyMeta['subjectTermId']->value ) : '';
			$output['studentTerm'] = ! empty( $vacancyMeta['studentTermId'] ) ? getTermName( $vacancyMeta['studentTermId']->value ) : '';

			$output['selectedProfileId']   = ! empty( $vacancyMeta['selectedProfileId'] ) ? (int) $vacancyMeta['selectedProfileId']->value : 0;
			$output['executorProfileId']   = ! empty( $vacancyMeta['executorProfileId'] ) ? (int) $vacancyMeta['executorProfileId']->value : 0;
			$output['candidateProfileIds'] = ( ! empty( $vacancyMeta['candidateProfileIds'] ) && is_serialized( $vacancyMeta['candidateProfileIds']->value ) ) ?
				maybe_unserialize( $vacancyMeta['candidateProfileIds']->value ) : [];

			$output['lessonIsScheduled'] = ! empty( $vacancyMeta['lessonIsScheduled'] ) ? (bool) $vacancyMeta['lessonIsScheduled']->value : false;
			$output['lessonIsCompleted'] = ! empty( $vacancyMeta['lessonIsCompleted'] ) ? (bool) $vacancyMeta['lessonIsCompleted']->value : false;
			$output['isCompleted']       = ! empty( $vacancyMeta['isCompleted'] ) ? (bool) $vacancyMeta['isCompleted']->value : false;
			$output['confirmIsRequired'] = ! empty( $vacancyMeta['confirmIsRequired'] ) ? (bool) $vacancyMeta['confirmIsRequired']->value : false;

		}

		return $output;
	}
} else {
	return new WP_Error( EXCELLENT_EXAM_CORE_PREFIX . 'functions_error', 'Не удалось определить функцию getVacancy' );
}

/**
 * @param int|string $termId
 *
 * @return string Term Name
 */
function getTermName( $termId ) {
	global $wpdb;

	$result = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT name FROM $wpdb->terms WHERE term_id = %d",
			absint( $termId )
		)
	);

	if ( ! empty( $result ) ) {
		return $result;
	}

	return '';
}

/**
 * @param int[] $termIds
 *
 * @return string
 */
function getTermNames( $termIds ) {
	global $wpdb;

	if ( empty( $termIds ) || ! is_array( $termIds ) ) {
		return '';
	}

	$terms = implode( ', ', $termIds );

	$result = $wpdb->get_results(
		"SELECT term_id, name FROM $wpdb->terms WHERE term_id IN ( " . $terms . ")", OBJECT_K
	);

	if ( ! empty( $result ) ) {
		return implode( ', ', array_map( static function ( $term ) {
			return $term->name;
		}, $result ) );
	}

	return '';
}

if ( ! function_exists( 'getProfileVacanciesBy' ) ) {
	/**
	 * @param string $field
	 * @param int $profileId
	 *
	 * @return int[]
	 */
	function getProfileVacanciesBy( $field, $profileId ) {
		$acceptedFields = [ 'selectedProfileId', 'executorProfileId' ];

		if ( in_array( $field, $acceptedFields ) && entityIsExists( $profileId, EXCELLENT_EXAM_CORE_PREFIX . 'profile' ) ) {
			global $wpdb;

			$sql = <<<SQL
				SELECT post_id FROM $wpdb->postmeta 
				WHERE meta_key = %s AND meta_value = %d
			SQL;

			$result = $wpdb->get_col(
				$wpdb->prepare(
					$sql,
					$field, $profileId
				)
			);

			if ( empty( $result ) ) {
				return [];
			}

			return array_map( static function ( $item ) {
				return (int) $item;
			}, $result );
		}

		return [];
	}
} else {
	return new WP_Error( EXCELLENT_EXAM_CORE_PREFIX . 'functions_error', 'Не удалось определить функцию getProfileVacanciesBy' );
}


<?php

use Ramsey\Uuid\Uuid;

/**
 * Handle upload image
 * Generate sizes and attach image to relation object by id
 *
 * @param array $uploadedFile single element of $_FILES
 * @param int $relationshipId Uploaded Image ID
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
		} else {
			return false;
		}
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
	} else {
		if ( ! empty( $id ) ) {
			wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $file ) );
		}
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
		} else {
			return false;
		}
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
		'cityTermId',
		'placeTermIds',
		'subjectTermIds',
		'studentsTermIds',
	];

	$uuid = Uuid::uuid4();

	$defaultMetaArgs = [
		'uuid'            => $uuid->toString(),
		'ownerUserId'     => 0,
		'firstName'       => '',
		'middleName'      => '',
		'lastName'        => '',
		'email'           => '',
		'phone'           => '',
		'area'            => '',
		'education'       => '',
		'description'     => '',
		'birthYear'       => 0,
		'hourlyRate'      => 0,
		'experience'      => 0,
		'cityTermId'      => 0,
		'metroTermId'     => 0,
		'statusTermId'    => 0,
		'placeTermIds'    => [],
		'subjectTermIds'  => [],
		'studentsTermIds' => [],
		'markTermIds'     => [],
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
		} else {
			return false;
		}

	}

	$defaultArgs = [
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
	$userId = get_userdata( absint( $userId ) );

	if ( empty( $userId ) || is_wp_error( $userId ) ) {
		return false;
	}

	return true;
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
		if ( postIsExists( $profileId ) && postIsExists( $imageAttachmentId ) ) {
			if ( ! update_metadata( 'post', absint($profileId), 'avatarAttachmentId', absint($imageAttachmentId) ) ) {
				if ( $wpError ) {
					return new WP_Error( EXCELLENT_EXAM_CORE_PREFIX . 'functions_error', 'Не удалось установить avatarAttachmentId', [
						'profileId' => $profileId,
						'metaKey'   => 'avatarAttachmentId',
						'metaValue' => $imageAttachmentId
					] );
				} else {
					return false;
				}
			}

			return true;
		} else {
			if ( $wpError ) {
				return new WP_Error( EXCELLENT_EXAM_CORE_PREFIX . 'functions_error', 'Профиль или изображение не существует', [
					'profileId'         => $profileId,
					'imageAttachmentId' => $imageAttachmentId
				] );
			}

			return false;
		}
	}
} else {
	return new WP_Error( EXCELLENT_EXAM_CORE_PREFIX . 'functions_error', 'Не удалось определить функцию setProfileAvatar' );
}

if ( ! function_exists( 'postIsExists' ) ) {
	/**
	 * @param int $id {Post Type Object} Id
	 *
	 * @return bool
	 */
	function postIsExists( $id ) {
		return get_post( absint( $id ) ) ? true : false;
	}
} else {
	return new WP_Error( EXCELLENT_EXAM_CORE_PREFIX . 'functions_error', 'Не удалось определить функцию postIsExists' );
}
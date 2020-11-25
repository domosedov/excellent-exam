<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://domosedov.info
 * @since      1.0.0
 *
 * @package    Excellent_Exam_Core
 * @subpackage Excellent_Exam_Core/public
 */

use Domosed\EEC\Routes\Profile;
use Domosed\EEC\Routes\Upload;

/**
 * The functionality of the plugin.
 *
 * @package    Excellent_Exam_Core
 * @subpackage Excellent_Exam_Core/public
 * @author     Aleksandr Grigorii <domosedov.dev@gmail.com>
 */
class Excellent_Exam_Core_Hooks {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register Custom Post Types
	 * @return WP_Error|void
	 * @since 1.0.0
	 * @wp-hook init
	 */
	public function registerCustomPostTypes() {
		$errors = [];

		/*
		 * CPT: Profile
		 */
		$profileLabels = [
			'name'                     => 'Профили репетиторов',
			'singular_name'            => 'Профиль репетитора',
			'menu_name'                => 'Профили репетиторов',
			'all_items'                => 'Все профили репетиторов',
			'add_new'                  => 'Добавить новый',
			'add_new_item'             => 'Добавить новый профиль репетитора',
			'edit_item'                => 'Редактировать профиль репетитора',
			'new_item'                 => 'Новый профиль репетитора',
			'view_item'                => 'Просмотреть профиль репетитора',
			'view_items'               => 'Просмотреть профили репетиторов',
			'search_items'             => 'Найти профиль репетитора',
			'not_found'                => 'Профиль репетитора не найден',
			'not_found_in_trash'       => 'В корзине профиль репетитора не найден',
			'parent'                   => 'Главный профиль репетитора',
			'featured_image'           => 'Изображение профиля репетитора',
			'set_featured_image'       => 'Установить изображение профиля репетитора',
			'remove_featured_image'    => 'Удалить изображение профиля репетитора',
			'use_featured_image'       => 'Использовать изображение профиля репетитора',
			'archives'                 => 'Архив репетиторов',
			'insert_into_item'         => 'Вставить в профиль репетитора',
			'uploaded_to_this_item'    => 'Загрузить для этого профиля репетитора',
			'filter_items_list'        => 'Фильтровать список профилей репетиторов',
			'items_list_navigation'    => 'Навигация по списку профилей репетиторов',
			'items_list'               => 'Список профилей репетиторов',
			'attributes'               => 'Атрибуты',
			'name_admin_bar'           => 'Новый профиль репетитора',
			'item_published'           => 'Профиль репетитора опубликован',
			'item_published_privately' => 'Профиль репетитора опубликован приватно',
			'item_reverted_to_draft'   => 'Профиль репетитора перемещен в черновики',
			'item_scheduled'           => 'Профиль репетитора запланирован для публикации',
			'item_updated'             => 'Профиль репетитора обновлен',
			'parent_item_colon'        => 'Главный профиль репетитора'
		];

		$profileArgs = [
			'label'            => 'Профили репетиторов',
			'labels'           => $profileLabels,
			'public'           => false,
			'show_ui'          => true,
			'has_archive'      => false,
			'show_in_menu'     => true,
			'delete_with_user' => false,
			'hierarchical'     => false,
			'query_var'        => false,
			'menu_icon'        => 'dashicons-id-alt',
			'supports'         => [ 'title', 'author' ]
		];

		$profileType = register_post_type( EXCELLENT_EXAM_CORE_PREFIX . 'profile', $profileArgs );

		if ( is_wp_error( $profileType ) ) {
			$errors['profile'] = 'Не удалось зарегистрировать Profile CPT';
		}

		/*
		 * CPT: Vacancy
		 */
		$vacancyLabels = [
			'name'                     => 'Заявки',
			'singular_name'            => 'Заявка',
			'menu_name'                => 'Заявки',
			'all_items'                => 'Все заявки',
			'add_new'                  => 'Добавить новую',
			'add_new_item'             => 'Добавить новую заявку',
			'edit_item'                => 'Редактировать заявку',
			'new_item'                 => 'Новая заявка',
			'view_item'                => 'Просмотреть заявку',
			'view_items'               => 'Просмотреть заявки',
			'search_items'             => 'Найти заявку',
			'not_found'                => 'Заявка не найдена',
			'not_found_in_trash'       => 'В корзине заявка не найдена',
			'parent'                   => 'Главная заявка',
			'featured_image'           => 'Изображение заявки',
			'set_featured_image'       => 'Установить изображение заявка',
			'remove_featured_image'    => 'Удалить изображение заявки',
			'use_featured_image'       => 'Использовать изображение заявки',
			'archives'                 => 'Архив заявок',
			'insert_into_item'         => 'Вставить в заявку',
			'uploaded_to_this_item'    => 'Загрузить для этой заявки',
			'filter_items_list'        => 'Фильтровать список заявок',
			'items_list_navigation'    => 'Навигация по списку заявок',
			'items_list'               => 'Список заявок',
			'attributes'               => 'Атрибуты',
			'name_admin_bar'           => 'Новая заявка',
			'item_published'           => 'Заявка опубликована',
			'item_published_privately' => 'Заявка опубликована приватно',
			'item_reverted_to_draft'   => 'Заявка перемещена в черновики',
			'item_scheduled'           => 'Заявка запланирована для публикации',
			'item_updated'             => 'Заявка обновлена',
			'parent_item_colon'        => 'Главная заявка'
		];

		$vacancyArgs = [
			'label'            => 'Заявки',
			'labels'           => $vacancyLabels,
			'public'           => false,
			'show_ui'          => true,
			'has_archive'      => false,
			'show_in_menu'     => true,
			'delete_with_user' => false,
			'hierarchical'     => false,
			'query_var'        => false,
			'menu_icon'        => 'dashicons-pressthis',
			'supports'         => [ 'title' ]
		];

		$vacancyType = register_post_type( EXCELLENT_EXAM_CORE_PREFIX . 'vacancy', $vacancyArgs );

		if ( is_wp_error( $vacancyType ) ) {
			$errors['vacancy'] = 'Не удалось зарегистрировать Vacancy CPT';
		}

		/*
		 * CPT: Message
		 */
		$messageLabels = [
			'name'                     => 'Сообщения',
			'singular_name'            => 'Сообщение',
			'menu_name'                => 'Сообщения',
			'all_items'                => 'Все сообщения',
			'add_new'                  => 'Добавить новое',
			'add_new_item'             => 'Добавить новое сообщение',
			'edit_item'                => 'Редактировать сообщение',
			'new_item'                 => 'Новое сообщение',
			'view_item'                => 'Просмотреть сообщение',
			'view_items'               => 'Просмотреть сообщения',
			'search_items'             => 'Найти сообщение',
			'not_found'                => 'Сообщение не найдено',
			'not_found_in_trash'       => 'В корзине сообщение не найдено',
			'parent'                   => 'Главная сообщение',
			'featured_image'           => 'Изображение сообщения',
			'set_featured_image'       => 'Установить изображение сообщения',
			'remove_featured_image'    => 'Удалить изображение сообщения',
			'use_featured_image'       => 'Использовать изображение сообщения',
			'archives'                 => 'Архив сообщений',
			'insert_into_item'         => 'Вставить в сообщение',
			'uploaded_to_this_item'    => 'Загрузить для этого сообщения',
			'filter_items_list'        => 'Фильтровать список сообщений',
			'items_list_navigation'    => 'Навигация по списку сообщений',
			'items_list'               => 'Список сообщений',
			'attributes'               => 'Атрибуты',
			'name_admin_bar'           => 'Новое сообщение',
			'item_published'           => 'Сообщение опубликовано',
			'item_published_privately' => 'Сообщение опубликовано приватно',
			'item_reverted_to_draft'   => 'Сообщение перемещено в черновики',
			'item_scheduled'           => 'Сообщение запланировано для публикации',
			'item_updated'             => 'Сообщение обновлено',
			'parent_item_colon'        => 'Главная сообщение'
		];

		$messageArgs = [
			'label'            => 'Сообщения',
			'labels'           => $messageLabels,
			'public'           => false,
			'show_ui'          => true,
			'has_archive'      => false,
			'show_in_menu'     => true,
			'delete_with_user' => false,
			'hierarchical'     => false,
			'query_var'        => false,
			'menu_icon'        => 'dashicons-email-alt2',
			'supports'         => [ 'title', 'editor', 'author' ]
		];

		$messageType = register_post_type( EXCELLENT_EXAM_CORE_PREFIX . 'message', $messageArgs );

		if ( is_wp_error( $messageType ) ) {
			$errors['message'] = 'Не удалось зарегистрировать Message CPT';
		}

		/*
		 * CPT: Feedback
		 */
		$feedbackLabels = [
			'name'                     => 'Отзывы',
			'singular_name'            => 'Отзыв',
			'menu_name'                => 'Отзывы',
			'all_items'                => 'Все отзывы',
			'add_new'                  => 'Добавить новый',
			'add_new_item'             => 'Добавить новый отзыв',
			'edit_item'                => 'Редактировать отзыв',
			'new_item'                 => 'Новый отзыв',
			'view_item'                => 'Просмотреть отзыв',
			'view_items'               => 'Просмотреть отзывы',
			'search_items'             => 'Найти отзывы',
			'not_found'                => 'Отзыв не найден',
			'not_found_in_trash'       => 'В корзине отзыв не найден',
			'parent'                   => 'Главная отзыв',
			'featured_image'           => 'Изображение отзыва',
			'set_featured_image'       => 'Установить изображение отзыва',
			'remove_featured_image'    => 'Удалить изображение отзыва',
			'use_featured_image'       => 'Использовать изображение отзыва',
			'archives'                 => 'Архив отзывов',
			'insert_into_item'         => 'Вставить в отзыв',
			'uploaded_to_this_item'    => 'Загрузить для этого отзыва',
			'filter_items_list'        => 'Фильтровать список отзывов',
			'items_list_navigation'    => 'Навигация по списку отзывов',
			'items_list'               => 'Список отзывов',
			'attributes'               => 'Атрибуты',
			'name_admin_bar'           => 'Новый отзыв',
			'item_published'           => 'Отзыв опубликован',
			'item_published_privately' => 'Отзыв опубликован приватно',
			'item_reverted_to_draft'   => 'Отзыв перемещен в черновики',
			'item_scheduled'           => 'Отзыв запланирован для публикации',
			'item_updated'             => 'Отзыв обновлен',
			'parent_item_colon'        => 'Главный отзыв'
		];

		$feedbackArgs = [
			'label'            => 'Отзывы',
			'labels'           => $feedbackLabels,
			'public'           => false,
			'show_ui'          => true,
			'has_archive'      => false,
			'show_in_menu'     => true,
			'delete_with_user' => false,
			'hierarchical'     => false,
			'query_var'        => false,
			'menu_icon'        => 'dashicons-thumbs-up',
			'supports'         => [ 'title', 'editor' ]
		];

		$feedbackType = register_post_type( EXCELLENT_EXAM_CORE_PREFIX . 'feedback', $feedbackArgs );

		if ( is_wp_error( $feedbackType ) ) {
			$errors['feedback'] = 'Не удалось зарегистрировать Feedback CPT';
		}

		/*
		 * If errors return WP_Error
		 */
		if ( ! empty( $errors ) ) {
			return new WP_Error( EXCELLENT_EXAM_CORE_PREFIX . 'plugin_hooks_error', 'Ошибка registerCustomPostTypes', $errors );
		}
	}

	/**
	 * Register Custom Taxonomies
	 * @return WP_Error|void
	 * @since 1.0.0
	 * @wp-hook init
	 */
	public function registerCustomTaxonomies() {
		$errors = [];

		/*
		 * Taxonomy: Metro
		 */
		$metroLabels = [
			'name'                       => 'Станции метро',
			'singular_name'              => 'Станция метро',
			'menu_name'                  => 'Станции метро',
			'all_items'                  => 'Все станции метро',
			'edit_item'                  => 'Редактировать станцию метро',
			'view_item'                  => 'Просмотреть станцию метро',
			'update_item'                => 'Обновить станцию метро',
			'add_new_item'               => 'Добавить новую станцию метро',
			'new_item_name'              => 'Новая станция метро',
			'parent_item'                => 'Главная станция метро',
			'parent_item_colon'          => 'Главная станция метро',
			'search_items'               => 'Найти станцию метро',
			'popular_items'              => 'Популярные станции метро',
			'separate_items_with_commas' => 'Разделить станции метро запятой',
			'add_or_remove_items'        => 'Добавить или удалить станции метро',
			'choose_from_most_used'      => 'Выбрать среди популярных',
			'not_found'                  => 'Станции метро не найдена',
			'no_terms'                   => 'Нет станций метро',
			'items_list_navigation'      => 'Навигация по списку станций метро',
			'items_list'                 => 'Список станции метро',
		];

		$metroArgs = [
			'label'             => 'Станции метро',
			'labels'            => $metroLabels,
			'public'            => false,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'show_admin_column' => false,
			'has_archive'       => false,
			'hierarchical'      => false,
			'query_var'         => false,
			'capabilities'      => [
				'manage_terms' => 'manage_categories',
				'edit_terms'   => 'manage_categories',
				'delete_terms' => 'manage_categories',
				'assign_terms' => 'manage_categories',
			]
		];

		$metroTaxonomy = register_taxonomy( EXCELLENT_EXAM_CORE_PREFIX . 'metro', [
			EXCELLENT_EXAM_CORE_PREFIX . 'profile',
			EXCELLENT_EXAM_CORE_PREFIX . 'vacancy'
		], $metroArgs );

		if ( is_wp_error( $metroTaxonomy ) ) {
			$errors['metro'] = 'Не удалось зарегистрировать metro taxonomy';
		}

		/*
		 * Taxonomy: City
		 */
		$cityLabels = [
			'name'                       => 'Города',
			'singular_name'              => 'Город',
			'menu_name'                  => 'Города',
			'all_items'                  => 'Все города',
			'edit_item'                  => 'Редактировать город',
			'view_item'                  => 'Просмотреть город',
			'update_item'                => 'Обновить город',
			'add_new_item'               => 'Добавить новый город',
			'new_item_name'              => 'Новое название города',
			'parent_item'                => 'Главный город',
			'parent_item_colon'          => 'Главный город',
			'search_items'               => 'Найти города',
			'popular_items'              => 'Популярные города',
			'separate_items_with_commas' => 'Разделить города запятой',
			'add_or_remove_items'        => 'Добавить или удалить город',
			'choose_from_most_used'      => 'Выбрать среди популярных',
			'not_found'                  => 'Город не найден',
			'no_terms'                   => 'Нет города',
			'items_list_navigation'      => 'Навигация по списку городов',
			'items_list'                 => 'Список городов',
		];

		$cityArgs = [
			'label'             => 'Города',
			'labels'            => $cityLabels,
			'public'            => false,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'show_admin_column' => false,
			'has_archive'       => false,
			'hierarchical'      => false,
			'query_var'         => false,
			'capabilities'      => [
				'manage_terms' => 'manage_categories',
				'edit_terms'   => 'manage_categories',
				'delete_terms' => 'manage_categories',
				'assign_terms' => 'manage_categories',
			]
		];

		$cityTaxonomy = register_taxonomy( EXCELLENT_EXAM_CORE_PREFIX . 'city', [
			EXCELLENT_EXAM_CORE_PREFIX . 'profile',
			EXCELLENT_EXAM_CORE_PREFIX . 'vacancy'
		], $cityArgs );

		if ( is_wp_error( $cityTaxonomy ) ) {
			$errors['city'] = 'Не удалось зарегистрировать city taxonomy';
		}

		/*
		 * Taxonomy: Subject
		 */
		$subjectLabels = [
			'name'                       => 'Предметы',
			'singular_name'              => 'Предмет',
			'menu_name'                  => 'Предметы',
			'all_items'                  => 'Все предметы',
			'edit_item'                  => 'Редактировать предмет',
			'view_item'                  => 'Просмотреть предмет',
			'update_item'                => 'Обновить предмет',
			'add_new_item'               => 'Добавить новый предмет',
			'new_item_name'              => 'Новое название предмета',
			'parent_item'                => 'Главный предмет',
			'parent_item_colon'          => 'Главный предмет',
			'search_items'               => 'Найти предметы',
			'popular_items'              => 'Популярные предметы',
			'separate_items_with_commas' => 'Разделить предметы запятой',
			'add_or_remove_items'        => 'Добавить или удалить предметы',
			'choose_from_most_used'      => 'Выбрать среди популярных',
			'not_found'                  => 'Предмет не найден',
			'no_terms'                   => 'Нет предметов',
			'items_list_navigation'      => 'Навигация по списку предметов',
			'items_list'                 => 'Список предметов',
		];

		$subjectArgs = [
			'label'             => 'Предметы',
			'labels'            => $subjectLabels,
			'public'            => false,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'show_admin_column' => false,
			'has_archive'       => false,
			'hierarchical'      => false,
			'query_var'         => false,
			'capabilities'      => [
				'manage_terms' => 'manage_categories',
				'edit_terms'   => 'manage_categories',
				'delete_terms' => 'manage_categories',
				'assign_terms' => 'manage_categories',
			]
		];

		$subjectTaxonomy = register_taxonomy( EXCELLENT_EXAM_CORE_PREFIX . 'subject', [
			EXCELLENT_EXAM_CORE_PREFIX . 'profile',
			EXCELLENT_EXAM_CORE_PREFIX . 'vacancy'
		], $subjectArgs );

		if ( is_wp_error( $subjectTaxonomy ) ) {
			$errors['subject'] = 'Не удалось зарегистрировать subject taxonomy';
		}

		/*
		 * Taxonomy: Student
		 */
		$studentLabels = [
			'name'                       => 'Категории учеников',
			'singular_name'              => 'Категория ученика',
			'menu_name'                  => 'Категории учеников',
			'all_items'                  => 'Все категории учеников',
			'edit_item'                  => 'Редактировать категорию ученика',
			'view_item'                  => 'Просмотреть категорию ученика',
			'update_item'                => 'Обновить категорию ученика',
			'add_new_item'               => 'Добавить новую категорию ученика',
			'new_item_name'              => 'Новое название категории ученика',
			'parent_item'                => 'Главная категория ученика',
			'parent_item_colon'          => 'Главная категория ученика',
			'search_items'               => 'Найти категорию ученика',
			'popular_items'              => 'Популярные категории учеников',
			'separate_items_with_commas' => 'Разделить категории учеников запятой',
			'add_or_remove_items'        => 'Добавить или удалить категорию ученика',
			'choose_from_most_used'      => 'Выбрать среди популярных',
			'not_found'                  => 'Категория ученика не найдена',
			'no_terms'                   => 'Нет категории ученика',
			'items_list_navigation'      => 'Навигация по списку категории учеников',
			'items_list'                 => 'Список категории учеников',
		];

		$studentArgs = [
			'label'             => 'Категории учеников',
			'labels'            => $studentLabels,
			'public'            => false,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'show_admin_column' => false,
			'has_archive'       => false,
			'hierarchical'      => false,
			'query_var'         => false,
			'capabilities'      => [
				'manage_terms' => 'manage_categories',
				'edit_terms'   => 'manage_categories',
				'delete_terms' => 'manage_categories',
				'assign_terms' => 'manage_categories',
			]
		];

		$studentTaxonomy = register_taxonomy( EXCELLENT_EXAM_CORE_PREFIX . 'student', [
			EXCELLENT_EXAM_CORE_PREFIX . 'profile',
			EXCELLENT_EXAM_CORE_PREFIX . 'vacancy'
		], $studentArgs );

		if ( is_wp_error( $studentTaxonomy ) ) {
			$errors['student'] = 'Не удалось зарегистрировать student taxonomy';
		}

		/*
		 * Taxonomy: Place
		 */
		$placeLabels = [
			'name'                       => 'Места занятий',
			'singular_name'              => 'Место занятий',
			'menu_name'                  => 'Места занятий',
			'all_items'                  => 'Все места занятий',
			'edit_item'                  => 'Редактировать место занятий',
			'view_item'                  => 'Просмотреть места занятий',
			'update_item'                => 'Обновить место занятий',
			'add_new_item'               => 'Добавить новое место занятий',
			'new_item_name'              => 'Новое название места занятий',
			'parent_item'                => 'Главное место занятий',
			'parent_item_colon'          => 'Главное место занятий',
			'search_items'               => 'Найти места занятий',
			'popular_items'              => 'Популярные места занятий',
			'separate_items_with_commas' => 'Разделить места занятий запятой',
			'add_or_remove_items'        => 'Добавить или удалить место занятий',
			'choose_from_most_used'      => 'Выбрать среди популярных',
			'not_found'                  => 'Место занятий не найдена',
			'no_terms'                   => 'Нет места занятий',
			'items_list_navigation'      => 'Навигация по списку мест занятий',
			'items_list'                 => 'Список мест занятий',
		];

		$placeArgs = [
			'label'             => 'Места занятий',
			'labels'            => $placeLabels,
			'public'            => false,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'show_admin_column' => false,
			'has_archive'       => false,
			'hierarchical'      => false,
			'query_var'         => false,
			'capabilities'      => [
				'manage_terms' => 'manage_categories',
				'edit_terms'   => 'manage_categories',
				'delete_terms' => 'manage_categories',
				'assign_terms' => 'manage_categories',
			]
		];

		$placeTaxonomy = register_taxonomy( EXCELLENT_EXAM_CORE_PREFIX . 'place', [
			EXCELLENT_EXAM_CORE_PREFIX . 'profile',
			EXCELLENT_EXAM_CORE_PREFIX . 'vacancy'
		], $placeArgs );

		if ( is_wp_error( $placeTaxonomy ) ) {
			$errors['place'] = 'Не удалось зарегистрировать place taxonomy';
		}

		/*
		 * Taxonomy: Gender
		 */
		$genderLabels = [
			'name'                       => 'Пол репетитора',
			'singular_name'              => 'Пол человека',
			'menu_name'                  => 'Пол человека',
			'all_items'                  => 'Все значения пол человека',
			'edit_item'                  => 'Редактировать пол',
			'view_item'                  => 'Просмотреть пол',
			'update_item'                => 'Обновить пол',
			'add_new_item'               => 'Добавить новый пол',
			'new_item_name'              => 'Новое название знаяения пол',
			'parent_item'                => 'Главный пол',
			'parent_item_colon'          => 'Главный пол',
			'search_items'               => 'Найти пол человека',
			'popular_items'              => 'Популярный пол человека',
			'separate_items_with_commas' => 'Разделить значения "пол человека" запятой',
			'add_or_remove_items'        => 'Добавить или удалить пол человека',
			'choose_from_most_used'      => 'Выбрать среди популярных',
			'not_found'                  => 'Пол человека не найден',
			'no_terms'                   => 'Нет значений "пол человека"',
			'items_list_navigation'      => 'Навигация по списку значений "пол человека"',
			'items_list'                 => 'Список значений "пол человека"',
		];

		$genderArgs = [
			'label'             => 'Пол человека',
			'labels'            => $genderLabels,
			'public'            => false,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'show_admin_column' => false,
			'has_archive'       => false,
			'hierarchical'      => false,
			'query_var'         => false,
			'capabilities'      => [
				'manage_terms' => 'manage_categories',
				'edit_terms'   => 'manage_categories',
				'delete_terms' => 'manage_categories',
				'assign_terms' => 'manage_categories',
			]
		];

		$genderTaxonomy = register_taxonomy( EXCELLENT_EXAM_CORE_PREFIX . 'gender', [
			EXCELLENT_EXAM_CORE_PREFIX . 'profile',
			EXCELLENT_EXAM_CORE_PREFIX . 'vacancy'
		], $genderArgs );

		if ( is_wp_error( $genderTaxonomy ) ) {
			$errors['gender'] = 'Не удалось зарегистрировать gender taxonomy';
		}

		/*
		 * Taxonomy: Tutor Status
		 */
		$statusLabels = [
			'name'                       => 'Статусы репетиторов',
			'singular_name'              => 'Статус репетитора',
			'menu_name'                  => 'Статусы репетиторов',
			'all_items'                  => 'Все статусы репетиторов',
			'edit_item'                  => 'Редактировать статус репетитора',
			'view_item'                  => 'Просмотреть статусы репетиторов',
			'update_item'                => 'Обновить статус репетитора',
			'add_new_item'               => 'Добавить новый статус репетитора',
			'new_item_name'              => 'Новое название статуса репетитора',
			'parent_item'                => 'Главный статус репетитора',
			'parent_item_colon'          => 'Главный статус репетитора',
			'search_items'               => 'Найти статусы репетиторов',
			'popular_items'              => 'Популярные статусы репетиторов',
			'separate_items_with_commas' => 'Разделить статусы репетиторов запятой',
			'add_or_remove_items'        => 'Добавить или удалить статусы репетиторов',
			'choose_from_most_used'      => 'Выбрать среди популярных',
			'not_found'                  => 'Статусы репетиторов не найдены',
			'no_terms'                   => 'Нет статусов репетиторов',
			'items_list_navigation'      => 'Навигация по списку статусов репетиторов',
			'items_list'                 => 'Список статусов репетиторов',
		];

		$statusArgs = [
			'label'             => 'Статусы репетиторов',
			'labels'            => $statusLabels,
			'public'            => false,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'show_admin_column' => false,
			'has_archive'       => false,
			'hierarchical'      => false,
			'query_var'         => false,
			'capabilities'      => [
				'manage_terms' => 'manage_categories',
				'edit_terms'   => 'manage_categories',
				'delete_terms' => 'manage_categories',
				'assign_terms' => 'manage_categories',
			]
		];

		$statusTaxonomy = register_taxonomy( EXCELLENT_EXAM_CORE_PREFIX . 'status', [
			EXCELLENT_EXAM_CORE_PREFIX . 'profile',
			EXCELLENT_EXAM_CORE_PREFIX . 'vacancy'
		], $statusArgs );

		if ( is_wp_error( $statusTaxonomy ) ) {
			$errors['status'] = 'Не удалось зарегистрировать status taxonomy';
		}

		/*
		 * Taxonomy: Tutor Mark
		 */
		$markLabels = [
			'name'                       => 'Метки репетиторов',
			'singular_name'              => 'Метка репетитора',
			'menu_name'                  => 'Метки репетиторов',
			'all_items'                  => 'Все метки репетиторов',
			'edit_item'                  => 'Редактировать метку репетитора',
			'view_item'                  => 'Просмотреть метки репетиторов',
			'update_item'                => 'Обновить метку репетитора',
			'add_new_item'               => 'Добавить новую метку репетитора',
			'new_item_name'              => 'Новое название метки репетитора',
			'parent_item'                => 'Главная метка репетитора',
			'parent_item_colon'          => 'Главная метка репетитора',
			'search_items'               => 'Найти метки репетиторов',
			'popular_items'              => 'Популярные метки репетиторов',
			'separate_items_with_commas' => 'Разделить метки репетиторов запятой',
			'add_or_remove_items'        => 'Добавить или удалить метку репетиторов',
			'choose_from_most_used'      => 'Выбрать среди популярных',
			'not_found'                  => 'Метки репетиторов не найдены',
			'no_terms'                   => 'Нет меток репетиторов',
			'items_list_navigation'      => 'Навигация по списку меток репетиторов',
			'items_list'                 => 'Список меток репетиторов',
		];

		$markArgs = [
			'label'             => 'Метки репетиторов',
			'labels'            => $markLabels,
			'public'            => false,
			'show_ui'           => true,
			'show_in_menu'      => true,
			'show_admin_column' => false,
			'has_archive'       => false,
			'hierarchical'      => false,
			'query_var'         => false,
			'capabilities'      => [
				'manage_terms' => 'manage_categories',
				'edit_terms'   => 'manage_categories',
				'delete_terms' => 'manage_categories',
				'assign_terms' => 'manage_categories',
			]
		];

		$markTaxonomy = register_taxonomy( EXCELLENT_EXAM_CORE_PREFIX . 'mark', [
			EXCELLENT_EXAM_CORE_PREFIX . 'profile',
			EXCELLENT_EXAM_CORE_PREFIX . 'vacancy'
		], $markArgs );

		if ( is_wp_error( $markTaxonomy ) ) {
			$errors['mark'] = 'Не удалось зарегистрировать mark taxonomy';
		}

		/*
		 * If errors return WP_Error
		 */
		if ( ! empty( $errors ) ) {
			return new WP_Error( EXCELLENT_EXAM_CORE_PREFIX . 'plugin_hooks_error', 'Ошибка registerCustomTaxonomies', $errors );
		}
	}

	/**
	 * Register Custom Meta
	 * @return void
	 * @since 1.0.0
	 * @wp-hook init
	 */
	public function registerCustomMeta() {
		$this->registerProfileMeta();
		$this->registerVacancyMeta();
		$this->registerMessageMeta();
		$this->registerFeedbackMeta();
	}

	/**
	 * @return WP_Error|void
	 */
	private function registerProfileMeta() {
		$errors = [];

		$objectType = EXCELLENT_EXAM_CORE_PREFIX . 'profile';

		/*
		 * Text fields
		 */

		/*
		 * uuid
		 */
		$uuidArgs = [
			'object_subtype'    => EXCELLENT_EXAM_CORE_PREFIX . 'profile',
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => false,
			'default'           => '',
			'sanitize_callback' => [ $this, 'sanitizeMeta' ]
		];

		$uuid = register_meta( $objectType, 'uuid', $uuidArgs );

		if ( ! $uuid ) {
			$errors['uuid'] = 'Не удалось зарегистрировать мета-поле uuid';
		}

		/*
		 * First Name
		 */
		$firstNameArgs = [
			'object_subtype'    => EXCELLENT_EXAM_CORE_PREFIX . 'profile',
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => false,
			'default'           => '',
			'sanitize_callback' => [ $this, 'sanitizeMeta' ]
		];

		$firstName = register_meta( $objectType, 'firstName', $firstNameArgs );

		if ( ! $firstName ) {
			$errors['firstName'] = 'Не удалось зарегистрировать мета-поле firstName';
		}

		/*
		 * Middle Name
		 */
		$middleNameArgs = [
			'object_subtype'    => EXCELLENT_EXAM_CORE_PREFIX . 'profile',
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => false,
			'default'           => '',
			'sanitize_callback' => [ $this, 'sanitizeMeta' ]
		];

		$middleName = register_meta( $objectType, 'middleName', $middleNameArgs );

		if ( ! $middleName ) {
			$errors['middleName'] = 'Не удалось зарегистрировать мета-поле middleName';
		}

		/*
		 * Last Name
		 */
		$lastNameArgs = [
			'object_subtype'    => EXCELLENT_EXAM_CORE_PREFIX . 'profile',
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => false,
			'default'           => '',
			'sanitize_callback' => [ $this, 'sanitizeMeta' ]
		];

		$lastName = register_meta( $objectType, 'lastName', $lastNameArgs );

		if ( ! $lastName ) {
			$errors['lastName'] = 'Не удалось зарегистрировать мета-поле lastName';
		}

		/*
		 * Email
		 */
		$emailArgs = [
			'object_subtype'    => EXCELLENT_EXAM_CORE_PREFIX . 'profile',
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => false,
			'default'           => '',
			'sanitize_callback' => [ $this, 'sanitizeMeta' ]
		];

		$email = register_meta( $objectType, 'email', $emailArgs );

		if ( ! $email ) {
			$errors['email'] = 'Не удалось зарегистрировать мета-поле email';
		}

		if ( ! empty( $errors ) ) {
			return new WP_Error( EXCELLENT_EXAM_CORE_PREFIX . 'plugin_hooks_error', 'Ошибка registerProfileMeta', $errors );
		}

		/*
		 * Phone
		 */
		$phoneArgs = [
			'object_subtype'    => EXCELLENT_EXAM_CORE_PREFIX . 'profile',
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => false,
			'default'           => '',
			'sanitize_callback' => [ $this, 'sanitizeMeta' ]
		];

		$phone = register_meta( $objectType, 'phone', $phoneArgs );

		if ( ! $phone ) {
			$errors['phone'] = 'Не удалось зарегистрировать мета-поле phone';
		}

		/*
		 * Area
		 */
		$areaArgs = [
			'object_subtype'    => EXCELLENT_EXAM_CORE_PREFIX . 'profile',
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => false,
			'default'           => '',
			'sanitize_callback' => [ $this, 'sanitizeMeta' ]
		];

		$area = register_meta( $objectType, 'area', $areaArgs );

		if ( ! $area ) {
			$errors['area'] = 'Не удалось зарегистрировать мета-поле area';
		}

		/*
		 * Education
		 */
		$educationArgs = [
			'object_subtype'    => EXCELLENT_EXAM_CORE_PREFIX . 'profile',
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => false,
			'default'           => '',
			'sanitize_callback' => [ $this, 'sanitizeMeta' ]
		];

		$education = register_meta( $objectType, 'education', $educationArgs );

		if ( ! $education ) {
			$errors['education'] = 'Не удалось зарегистрировать мета-поле education';
		}

		/*
		 * Description
		 */
		$descriptionArgs = [
			'object_subtype'    => EXCELLENT_EXAM_CORE_PREFIX . 'profile',
			'type'              => 'string',
			'single'            => true,
			'show_in_rest'      => false,
			'default'           => '',
			'sanitize_callback' => [ $this, 'sanitizeMeta' ]
		];

		$description = register_meta( $objectType, 'description', $descriptionArgs );

		if ( ! $description ) {
			$errors['description'] = 'Не удалось зарегистрировать мета-поле description';
		}

		/*
		 * Integer fields
		 */

		/*
		 * Birth Year
		 */
		$birthYearArgs = [
			'object_subtype'    => EXCELLENT_EXAM_CORE_PREFIX . 'profile',
			'type'              => 'integer',
			'single'            => true,
			'show_in_rest'      => false,
			'default'           => 0,
			'sanitize_callback' => [ $this, 'sanitizeMeta' ]
		];

		$birthYear = register_meta( $objectType, 'birthYear', $birthYearArgs );

		if ( ! $birthYear ) {
			$errors['birthYear'] = 'Не удалось зарегистрировать мета-поле birthYear';
		}

		/*
		 * Hourly rate
		 */
		$hourlyRateArgs = [
			'object_subtype'    => EXCELLENT_EXAM_CORE_PREFIX . 'profile',
			'type'              => 'integer',
			'single'            => true,
			'show_in_rest'      => false,
			'default'           => 0,
			'sanitize_callback' => [ $this, 'sanitizeMeta' ]
		];

		$hourlyRate = register_meta( $objectType, 'hourlyRate', $hourlyRateArgs );

		if ( ! $hourlyRate ) {
			$errors['hourlyRate'] = 'Не удалось зарегистрировать мета-поле hourlyRate';
		}

		/*
		 * Experience
		 */
		$experienceArgs = [
			'object_subtype'    => EXCELLENT_EXAM_CORE_PREFIX . 'profile',
			'type'              => 'integer',
			'single'            => true,
			'show_in_rest'      => false,
			'default'           => 0,
			'sanitize_callback' => [ $this, 'sanitizeMeta' ]
		];

		$experience = register_meta( $objectType, 'experience', $experienceArgs );

		if ( ! $experience ) {
			$errors['experience'] = 'Не удалось зарегистрировать мета-поле experience';
		}

		/*
		 * Term Fields
		 */

		/*
		 * City term Id
		 */
		$cityTermIdArgs = [
			'object_subtype'    => EXCELLENT_EXAM_CORE_PREFIX . 'profile',
			'type'              => 'integer',
			'single'            => true,
			'show_in_rest'      => false,
			'default'           => 0,
			'sanitize_callback' => [ $this, 'sanitizeMeta' ]
		];

		$cityTermId = register_meta( $objectType, 'cityTermId', $cityTermIdArgs );

		if ( ! $cityTermId ) {
			$errors['cityTermId'] = 'Не удалось зарегистрировать мета-поле cityTermId';
		}

		/*
		 * Metro term Id
		 */
		$metroTermIdArgs = [
			'object_subtype'    => EXCELLENT_EXAM_CORE_PREFIX . 'profile',
			'type'              => 'integer',
			'single'            => true,
			'show_in_rest'      => false,
			'default'           => 0,
			'sanitize_callback' => [ $this, 'sanitizeMeta' ]
		];

		$metroTermId = register_meta( $objectType, 'metroTermId', $metroTermIdArgs );

		if ( ! $metroTermId ) {
			$errors['metroTermId'] = 'Не удалось зарегистрировать мета-поле metroTermId';
		}

		/*
		 * Status term Id
		 */
		$statusTermIdArgs = [
			'object_subtype'    => EXCELLENT_EXAM_CORE_PREFIX . 'profile',
			'type'              => 'integer',
			'single'            => true,
			'show_in_rest'      => false,
			'default'           => 0,
			'sanitize_callback' => [ $this, 'sanitizeMeta' ]
		];

		$statusTermId = register_meta( $objectType, 'statusTermId', $statusTermIdArgs );

		if ( ! $statusTermId ) {
			$errors['statusTermId'] = 'Не удалось зарегистрировать мета-поле statusTermId';
		}

		/*
		 * Gender term Id
		 */
		$genderTermIdArgs = [
			'object_subtype'    => EXCELLENT_EXAM_CORE_PREFIX . 'profile',
			'type'              => 'integer',
			'single'            => true,
			'show_in_rest'      => false,
			'default'           => 0,
			'sanitize_callback' => [ $this, 'sanitizeMeta' ]
		];

		$genderTermId = register_meta( $objectType, 'genderTermId', $genderTermIdArgs );

		if ( ! $genderTermId ) {
			$errors['genderTermId'] = 'Не удалось зарегистрировать мета-поле genderTermId';
		}

		/*
		 * Place term Ids
		 */
		$placeTermIdsArgs = [
			'object_subtype'    => EXCELLENT_EXAM_CORE_PREFIX . 'profile',
			'type'              => 'array',
			'single'            => true,
			'show_in_rest'      => false,
			'default'           => [],
			'sanitize_callback' => [ $this, 'sanitizeMeta' ]
		];

		$placeTermIds = register_meta( $objectType, 'placeTermIds', $placeTermIdsArgs );

		if ( ! $placeTermIds ) {
			$errors['placeTermIds'] = 'Не удалось зарегистрировать мета-поле placeTermIds';
		}

		/*
		 * Subject term Ids
		 */
		$subjectTermIdsArgs = [
			'object_subtype'    => EXCELLENT_EXAM_CORE_PREFIX . 'profile',
			'type'              => 'array',
			'single'            => true,
			'show_in_rest'      => false,
			'default'           => [],
			'sanitize_callback' => [ $this, 'sanitizeMeta' ]
		];

		$subjectTermIds = register_meta( $objectType, 'subjectTermIds', $subjectTermIdsArgs );

		if ( ! $subjectTermIds ) {
			$errors['subjectTermIds'] = 'Не удалось зарегистрировать мета-поле subjectTermIds';
		}

		/*
		 * Students term Ids
		 */
		$studentTermIdsArgs = [
			'object_subtype'    => EXCELLENT_EXAM_CORE_PREFIX . 'profile',
			'type'              => 'array',
			'single'            => true,
			'show_in_rest'      => false,
			'default'           => [],
			'sanitize_callback' => [ $this, 'sanitizeMeta' ]
		];

		$studentTermIds = register_meta( $objectType, 'studentTermIds', $studentTermIdsArgs );

		if ( ! $studentTermIds ) {
			$errors['studentTermIds'] = 'Не удалось зарегистрировать мета-поле studentTermIds';
		}

		/*
		 * Marks term Ids
		 */
		$markTermIdsArgs = [
			'object_subtype'    => EXCELLENT_EXAM_CORE_PREFIX . 'profile',
			'type'              => 'array',
			'single'            => true,
			'show_in_rest'      => false,
			'default'           => [],
			'sanitize_callback' => [ $this, 'sanitizeMeta' ]
		];

		$markTermIds = register_meta( $objectType, 'markTermIds', $markTermIdsArgs );

		if ( ! $markTermIds ) {
			$errors['markTermIds'] = 'Не удалось зарегистрировать мета-поле markTermIds';
		}

		/*
		 * Relationship fields
		 */

		/*
		 * Owner Id
		 */
		$ownerUserIdArgs = [
			'object_subtype'    => EXCELLENT_EXAM_CORE_PREFIX . 'profile',
			'type'              => 'integer',
			'single'            => true,
			'show_in_rest'      => false,
			'default'           => 0,
			'sanitize_callback' => [ $this, 'sanitizeMeta' ]
		];

		$ownerUserId = register_meta( $objectType, 'ownerUserId', $ownerUserIdArgs );

		if ( ! $ownerUserId ) {
			$errors['ownerUserId'] = 'Не удалось зарегистрировать мета-поле ownerUserId';
		}

		/*
		 * Avatar Attachment Id
		 */
		$avatarAttachmentIdArgs = [
			'object_subtype'    => EXCELLENT_EXAM_CORE_PREFIX . 'profile',
			'type'              => 'integer',
			'single'            => true,
			'show_in_rest'      => false,
			'default'           => 0,
			'sanitize_callback' => [ $this, 'sanitizeMeta' ]
		];

		$avatarAttachmentId = register_meta( $objectType, 'avatarAttachmentId', $avatarAttachmentIdArgs );

		if ( ! $avatarAttachmentId ) {
			$errors['avatarAttachmentId'] = 'Не удалось зарегистрировать мета-поле avatarAttachmentId';
		}

		/*
		 * Document Attachment Ids
		 */
		$documentAttachmentIdsArgs = [
			'object_subtype'    => EXCELLENT_EXAM_CORE_PREFIX . 'profile',
			'type'              => 'array',
			'single'            => true,
			'show_in_rest'      => false,
			'default'           => [],
			'sanitize_callback' => [ $this, 'sanitizeMeta' ]
		];

		$documentAttachmentIds = register_meta( $objectType, 'documentAttachmentIds', $documentAttachmentIdsArgs );

		if ( ! $documentAttachmentIds ) {
			$errors['documentAttachmentIds'] = 'Не удалось зарегистрировать мета-поле documentAttachmentIds';
		}

		/*
		 * Feedback Attachment Ids
		 */
		$feedbackIdsArgs = [
			'object_subtype'    => EXCELLENT_EXAM_CORE_PREFIX . 'profile',
			'type'              => 'array',
			'single'            => true,
			'show_in_rest'      => false,
			'default'           => [],
			'sanitize_callback' => [ $this, 'sanitizeMeta' ]
		];

		$feedbackIds = register_meta( $objectType, 'feedbackIds', $feedbackIdsArgs );

		if ( ! $feedbackIds ) {
			$errors['feedbackIds'] = 'Не удалось зарегистрировать мета-поле feedbackIds';
		}

		if ( ! empty( $errors ) ) {
			return new WP_Error( EXCELLENT_EXAM_CORE_PREFIX . 'plugin_hooks_error', 'Ошибка registerProfileMeta', $errors );
		}
	}

	/**
	 * @return WP_Error|void
	 */
	private function registerVacancyMeta() {
		$errors = [];

		if ( ! empty( $errors ) ) {
			return new WP_Error( EXCELLENT_EXAM_CORE_PREFIX . 'plugin_hooks_error', 'Ошибка registerVacancyMeta', $errors );
		}
	}

	/**
	 * @return WP_Error|void
	 */
	private function registerMessageMeta() {
		$errors = [];

		if ( ! empty( $errors ) ) {
			return new WP_Error( EXCELLENT_EXAM_CORE_PREFIX . 'plugin_hooks_error', 'Ошибка registerMessageMeta', $errors );
		}
	}

	/**
	 * @return WP_Error|void
	 */
	private function registerFeedbackMeta() {
		$errors = [];

		if ( ! empty( $errors ) ) {
			return new WP_Error( EXCELLENT_EXAM_CORE_PREFIX . 'plugin_hooks_error', 'Ошибка registerFeedbackMeta', $errors );
		}
	}

	public function sanitizeMeta( $value, $key, $objectType ) {
		//TODO Add sanitizeMeta
		return $value;
	}

	public function registerCustomRoutes() {
		$profileRoute = new Profile();
		$profileRoute->register_routes();

		$uploadRoute = new Upload();
		$uploadRoute->registerRoutes();
	}

	/**
	 * Handle delete_attachment hook
	 *
	 * @param int|string $attachmentId Attachment Id
	 * @param WP_Post $post Attachment Object
	 *
	 * @return void
	 */
	public function handleDeleteAttachment( $attachmentId, WP_Post $post ): void {
		/*
		 * При удалении изображения проверяем не является ли оно аватаром или документов профиля,
		 * если да, то обновляем мета-данные
		 */
		if ( ! empty( $post->post_parent ) && get_post_type( $post->post_parent ) === EXCELLENT_EXAM_CORE_PREFIX . 'profile' ) {
			$profileAvatarId    = absint( get_post_meta( $post->post_parent, 'avatarAttachmentId', true ) );
			$profileDocumentIds = get_post_meta( $post->post_parent, 'documentAttachmentIds', true );
			if ( $attachmentId === $profileAvatarId ) {
				update_post_meta( $post->post_parent, 'avatarAttachmentId', 0 );
			}
			if ( is_array( $profileDocumentIds ) && ! empty( $profileDocumentIds ) && in_array( $attachmentId, $profileDocumentIds, true ) ) {
				update_post_meta( $post->post_parent, 'documentAttachmentIds', array_filter( $profileDocumentIds,
					static function ( $value ) use ( $attachmentId ) {
						return (int) $value !== (int) $attachmentId;
					} ) );
			}
		}
	}

	/**
	 * Generate Slug with UUID
	 * @param string $overrideSlug
	 * @param string $slug
	 * @param int|string $postId
	 * @param string $postStatus
	 * @param string $postType
	 * @param int|string $postParent
	 *
	 * @return string New Slug
	 */
	public function generateUniquePostSlug( $overrideSlug, $slug, $postId, $postStatus, $postType, $postParent ) {
		switch ( $postType ) {
			case EXCELLENT_EXAM_CORE_PREFIX . 'profile':
				$uuid = get_post_meta( $postId, 'uuid', true );
				if ( empty( $uuid ) ) {
					$uuid = current_time( 'timestamp' );
				}

				return 'profile-' . $uuid;
			case EXCELLENT_EXAM_CORE_PREFIX . 'vacancy':
				$uuid = get_post_meta( $postId, 'uuid', true );
				if ( empty( $uuid ) ) {
					$uuid = current_time( 'timestamp' );
				}

				return 'vacancy-' . $uuid;
		}

		return $overrideSlug;
	}
}

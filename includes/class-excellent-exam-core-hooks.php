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

/**
 * The functionality of the plugin.
 *
 * @package    Excellent_Exam_Core
 * @subpackage Excellent_Exam_Core/public
 * @author     Aleksandr Grigorii <domosedov.dev@gmail.com>
 */
class Excellent_Exam_Core_Hooks
{

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
     * @since    1.0.0
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register Custom Post Types
     * @wp-hook init
     * @return WP_Error|void
     */
    public function registerCustomPostTypes()
    {
        $errors = [];

        /*
         * Profile CPT
         */
        $profileLabels = [
            'name' => 'Профили репетиторов',
            'singular_name' => 'Профиль репетитора',
            'menu_name' => 'Профили репетиторов',
            'all_items' => 'Все профили репетиторов',
            'add_new' => 'Добавить новый',
            'add_new_item' => 'Добавить новый профиль репетитора',
            'edit_item' => 'Редактировать профиль репетитора',
            'new_item' => 'Новый профиль репетитора',
            'view_item' => 'Просмотреть профиль репетитора',
            'view_items' => 'Просмотреть профили репетиторов',
            'search_items' => 'Найти профиль репетитора',
            "not_found" => 'Профиль репетитора не найден',
            'not_found_in_trash' => 'В корзине профиль репетитора не найден',
            'parent' => 'Главный профиль репетитора',
            'featured_image' => 'Изображение профиля репетитора',
            'set_featured_image' => 'Установить изображение профиля репетитора',
            'remove_featured_image' => 'Удалить изображение профиля репетитора',
            'use_featured_image' => 'Использовать изображение профиля репетитора',
            'archives' => 'Архив репетиторов',
            'insert_into_item' => 'Вставить в профиль репетитора',
            'uploaded_to_this_item' => 'Загрузить для этого профиля репетитора',
            'filter_items_list' => 'Фильтровать список профилей репетиторов',
            'items_list_navigation' => 'Навигация по списку профилей репетиторов',
            'items_list' => 'Список профилей репетиторов',
            'attributes' => 'Атрибуты',
            'name_admin_bar' => 'Новый профиль репетитора',
            'item_published' => 'Профиль репетитора опубликован',
            'item_published_privately' => 'Профиль репетитора опубликован приватно',
            'item_reverted_to_draft' => 'Профиль репетитора перемещен в черновики',
            'item_scheduled' => 'Профиль репетитора запланирован для публикации',
            'item_updated' => 'Профиль репетитора обновлен',
            'parent_item_colon' => 'Главный профиль репетитора'
        ];

        $profileArgs = [
            'label' => 'Профили репетиторов',
            'labels' => $profileLabels,
            'public' => false,
            'show_ui' => true,
            'has_archive' => false,
            'show_in_menu' => true,
            'delete_with_user' => false,
            'hierarchical' => false,
            'query_var' => false,
            'menu_icon' => 'dashicons-id-alt',
            'supports' => ['title', 'editor', 'author']
        ];

        $profileType = register_post_type('profile', $profileArgs);

        if (is_wp_error($profileType)) {
            $errors['profile'] = 'Не удалось зарегистрировать Profile CPT';
        }

        /*
         * Vacancy CPT
         */

        if (!empty($errors)) {
            return new WP_Error('EEC_plugin_hooks_error', 'Ошибка registerCustomPostTypes', $errors);
        }
    }

    /**
     * @wp-hook init
     * @return WP_Error|void
     */
    public function registerCustomTaxonomies()
    {
        $errors = [];

        if (!empty($errors)) {
            return new WP_Error('EEC_plugin_hooks_error', 'Ошибка registerCustomTaxonomies', $errors);
        }
    }

    /**
     * @wp-hook init
     * @return WP_Error|void
     */
    public function registerCustomMeta()
    {
        $errors = [];

        if (!empty($errors)) {
            return new WP_Error('EEC_plugin_hooks_error', 'Ошибка registerCustomMeta', $errors);
        }
    }

}

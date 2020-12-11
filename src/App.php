<?php

namespace Domosed\EEC;

use JsonException;
use WP_Error;

class App {

	private $loader;
	private $hooks;

	public function __construct( $loader, $hooks ) {

		$this->loader = $loader;
		$this->hooks  = $hooks;
		$this->defineHooks();

	}

	private function defineHooks(): void {

		$this->loader->addAction( 'init', $this->hooks, 'registerCustomPostTypes' );
		$this->loader->addAction( 'init', $this->hooks, 'registerCustomTaxonomies' );
		$this->loader->addAction( 'init', $this->hooks, 'registerCustomMeta' );
		$this->loader->addAction( 'rest_api_init', $this->hooks, 'registerCustomRoutes' );
		$this->loader->addAction( 'delete_attachment', $this->hooks, 'handleDeleteAttachment', 100, 2 );
		$this->loader->addFilter( 'pre_wp_unique_post_slug', $this->hooks, 'generateUniquePostSlug', 100, 6 );

	}

	public function createTables(): void {

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		global $wpdb;

		$charsetCollate = $wpdb->get_charset_collate();
		$prefix         = $wpdb->prefix;

		$citiesTableName = $prefix . 'eec_cities';

		$citiesQuery = "
			CREATE TABLE {$citiesTableName} (
				id bigint(20) unsigned NOT NULL auto_increment,
				name varchar(255) NOT NULL default '',				
				PRIMARY KEY (id),
				KEY name (name)
			) $charsetCollate;
		";

		$metrosTableName = $prefix . 'eec_metros';

		$metrosQuery = "
			CREATE TABLE {$metrosTableName} (
				id bigint(20) unsigned NOT NULL auto_increment,
				name varchar(255) NOT NULL default '',				
				PRIMARY KEY (id),
				KEY name (name)
			) $charsetCollate;
		";

		$subjectsTableName = $prefix . 'eec_subjects';

		$subjectsQuery = "
			CREATE TABLE {$subjectsTableName} (
				id bigint(20) unsigned NOT NULL auto_increment,
				name varchar(255) NOT NULL default '',				
				PRIMARY KEY (id),
				KEY name (name)
			) $charsetCollate;
		";

		$studentsTableName = $prefix . 'eec_students';

		$studentsQuery = "
			CREATE TABLE {$studentsTableName} (
				id bigint(20) unsigned NOT NULL auto_increment,
				name varchar(255) NOT NULL default '',				
				PRIMARY KEY (id),
				KEY name (name)
			) $charsetCollate;
		";

		$statusesTableName = $prefix . 'eec_statuses';

		$statusesQuery = "
			CREATE TABLE {$statusesTableName} (
				id bigint(20) unsigned NOT NULL auto_increment,
				name varchar(255) NOT NULL default '',				
				PRIMARY KEY (id),
				KEY name (name)
			) $charsetCollate;
		";

		$placesTableName = $prefix . 'eec_places';

		$placesQuery = "
			CREATE TABLE {$placesTableName} (
				id bigint(20) unsigned NOT NULL auto_increment,
				name varchar(255) NOT NULL default '',				
				PRIMARY KEY (id),
				KEY name (name)
			) $charsetCollate;
		";

		$marksTableName = $prefix . 'eec_marks';

		$marksQuery = "
			CREATE TABLE {$marksTableName} (
				id bigint(20) unsigned NOT NULL auto_increment,
				name varchar(255) NOT NULL default '',				
				PRIMARY KEY (id),
				KEY name (name)
			) $charsetCollate;
		";

		$profilesTableName = $prefix . 'eec_profiles';

		$profilesQuery = "
			CREATE TABLE {$profilesTableName} (
				id bigint(20) unsigned NOT NULL auto_increment,
				user_id bigint(20) unsigned NOT NULL default '0',
				owner_id bigint(20) unsigned NOT NULL,
				city_id bigint(20) unsigned NOT NULL,
				metro_id bigint(20) unsigned NOT NULL default '0',
				status_id bigint(20) unsigned NOT NULL default '0',
				gender enum('Не указан', 'Мужской', 'Женский') NOT NULL default 'Не указан',
				first_name varchar(100) NOT NULL default '',
				middle_name varchar(100) NOT NULL default '',
				last_name varchar(100) NOT NULL default '',
				email varchar(100) NOT NULL default '',
				phone varchar(100) NOT NULL default '',
				birth_year int(11) NOT NULL default '0',
				hourly_rate int(11) NOT NULL default '0',
				experience_start_year int(11) NOT NULL default '0',
				area varchar(255) NOT NULL default '',
				education text NOT NULL default '',
				description text NOT NULL default '',
				created datetime NOT NULL default CURRENT_TIMESTAMP,
				modified datetime NOT NULL ON UPDATE CURRENT_TIMESTAMP default CURRENT_TIMESTAMP,				
				PRIMARY KEY (id),
				FOREIGN KEY (owner_id) REFERENCES {$wpdb->users}(ID) ON DELETE CASCADE,
				FOREIGN KEY (city_id) REFERENCES {$citiesTableName}(id),
				KEY phone (phone),
				KEY email (email)
			) $charsetCollate;
		";

		$profilesSubjectsTableName = $prefix . 'eec_profiles_subjects';

		$profilesSubjectsQuery = "
			CREATE TABLE {$profilesSubjectsTableName} (
				profile_id bigint(20) unsigned NOT NULL,
				subject_id bigint(20) unsigned NOT NULL,
				FOREIGN KEY (profile_id) REFERENCES {$profilesTableName}(id) ON DELETE CASCADE,
				FOREIGN KEY (subject_id) REFERENCES {$subjectsTableName}(id)
			) $charsetCollate;
		";

		$profilesPlacesTableName = $prefix . 'eec_profiles_places';

		$profilesPlacesQuery = "
			CREATE TABLE {$profilesPlacesTableName} (
				profile_id bigint(20) unsigned NOT NULL,
				place_id bigint(20) unsigned NOT NULL,
				FOREIGN KEY (profile_id) REFERENCES {$profilesTableName}(id) ON DELETE CASCADE,
				FOREIGN KEY (place_id) REFERENCES {$placesTableName}(id)
			) $charsetCollate;
		";

		$profilesStudentsTableName = $prefix . 'eec_profiles_students';

		$profilesStudentsQuery = "
			CREATE TABLE {$profilesStudentsTableName} (
				profile_id bigint(20) unsigned NOT NULL,
				student_id bigint(20) unsigned NOT NULL,
				FOREIGN KEY (profile_id) REFERENCES {$profilesTableName}(id) ON DELETE CASCADE,
				FOREIGN KEY (student_id) REFERENCES {$studentsTableName}(id)
			) $charsetCollate;
		";

		$profilesMarksTableName = $prefix . 'eec_profiles_marks';

		$profilesMarksQuery = "
			CREATE TABLE {$profilesMarksTableName} (
				profile_id bigint(20) unsigned NOT NULL,
				mark_id bigint(20) unsigned NOT NULL,
				FOREIGN KEY (profile_id) REFERENCES {$profilesTableName}(id) ON DELETE CASCADE,
				FOREIGN KEY (mark_id) REFERENCES {$marksTableName}(id)
			) $charsetCollate;
		";

		dbDelta( [
			$citiesQuery,
			$metrosQuery,
			$subjectsQuery,
			$studentsQuery,
			$statusesQuery,
			$placesQuery,
			$marksQuery,
			$profilesQuery,
			$profilesSubjectsQuery,
			$profilesPlacesQuery,
			$profilesStudentsQuery,
			$profilesMarksQuery
		] );

		$this->insertCities( $citiesTableName );
		$this->insertMetros( $metrosTableName );

	}

	public function insertCities( $citiesTableName ): void {

		global $wpdb;

		try {
			$queryValues = implode( ', ', array_map( static function ( $cityName ) {
				return "('" . $cityName . "')";
			}, json_decode( file_get_contents( EEC_DIR_PATH . 'data/cities.json' ), false, 512, JSON_THROW_ON_ERROR ) ) );

			$wpdb->query(
				"INSERT INTO {$citiesTableName} (name) VALUES " . $queryValues . ";"
			);
		} catch ( JsonException $e ) {
		}

	}

	public function insertMetros( $metrosTableName ): void {

		global $wpdb;

		try {
			$queryValues = implode( ', ', array_map( static function ( $metroName ) {
				return "('" . $metroName . "')";
			}, json_decode( file_get_contents( EEC_DIR_PATH . 'data/metros.json' ), false, 512, JSON_THROW_ON_ERROR ) ) );

			$wpdb->query(
				"INSERT INTO {$metrosTableName} (name) VALUES " . $queryValues . ";"
			);
		} catch ( JsonException $e ) {
		}

	}

	public function dropTables(): void {

		global $wpdb;

		$prefix = $wpdb->prefix;

		$profilesTableName         = $prefix . 'eec_profiles';
		$citiesTableName           = $prefix . 'eec_cities';
		$metrosTableName           = $prefix . 'eec_metros';
		$subjectsTableName         = $prefix . 'eec_subjects';
		$studentsTableName         = $prefix . 'eec_students';
		$statusesTableName         = $prefix . 'eec_statuses';
		$placesTableName           = $prefix . 'eec_places';
		$marksTableName            = $prefix . 'eec_marks';
		$profilesSubjectsTableName = $prefix . 'eec_profiles_subjects';
		$profilesStudentsTableName = $prefix . 'eec_profiles_students';
		$profilesPlacesTableName   = $prefix . 'eec_profiles_places';
		$profilesMarksTableName    = $prefix . 'eec_profiles_marks';

		$wpdb->query( "DROP TABLE $profilesSubjectsTableName;" );
		$wpdb->query( "DROP TABLE $profilesPlacesTableName;" );
		$wpdb->query( "DROP TABLE $profilesMarksTableName;" );
		$wpdb->query( "DROP TABLE $profilesStudentsTableName;" );
		$wpdb->query( "DROP TABLE $profilesTableName;" );
		$wpdb->query( "DROP TABLE $citiesTableName;" );
		$wpdb->query( "DROP TABLE $metrosTableName;" );
		$wpdb->query( "DROP TABLE $statusesTableName;" );
		$wpdb->query( "DROP TABLE $subjectsTableName;" );
		$wpdb->query( "DROP TABLE $studentsTableName;" );
		$wpdb->query( "DROP TABLE $placesTableName;" );
		$wpdb->query( "DROP TABLE $marksTableName;" );

	}

	public function run(): void {
		$this->loader->run();
	}
}

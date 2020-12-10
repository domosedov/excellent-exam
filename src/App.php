<?php

namespace Domosed\EEC;

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

	public function run(): void {
		$this->loader->run();
	}
}

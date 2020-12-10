<?php

namespace Domosed\EEC;

class AppLoader {

	protected $actions;

	protected $filters;

	public function __construct() {

		$this->actions = [];
		$this->filters = [];
	}

	public function addAction( $hook, $component, $callback, $priority = 10, $acceptedArgs = 1 ): void {

		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $acceptedArgs );

	}

	public function addFilter( $hook, $component, $callback, $priority = 10, $acceptedArgs = 1 ): void {

		$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $acceptedArgs );

	}

	private function add( $hooks, $hook, $component, $callback, $priority, $acceptedArgs ) {

		$hooks[] = array(
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $acceptedArgs
		);

		return $hooks;

	}

	public function run(): void {

		foreach ( $this->filters as $hook ) {
			add_filter( $hook['hook'], array(
				$hook['component'],
				$hook['callback']
			), $hook['priority'], $hook['accepted_args'] );
		}

		foreach ( $this->actions as $hook ) {
			add_action( $hook['hook'], array(
				$hook['component'],
				$hook['callback']
			), $hook['priority'], $hook['accepted_args'] );
		}

	}
}

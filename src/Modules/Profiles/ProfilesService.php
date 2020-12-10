<?php


namespace Domosed\EEC\Modules\Profiles;


class ProfilesService {

	private $repository;
	private $authService;

	public function __construct( $repository, $authService ) {
		$this->repository  = $repository;
		$this->authService = $authService;
	}

	public function getItemsPermissionsCheck( $request ): bool {
		return true;
	}

	public function getItems( $request ) {
		return rest_ensure_response( [ 'message' => 'success' ] );
	}
}
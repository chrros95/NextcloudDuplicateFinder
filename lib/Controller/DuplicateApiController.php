<?php

namespace OCA\DuplicateFinder\Controller;

use OCP\IRequest;
use OCP\IUserSession;
use Psr\Log\LoggerInterface;
use OCP\AppFramework\Http\JSONResponse;
use OCA\DuplicateFinder\AppInfo\Application;
use OCA\DuplicateFinder\Service\FileDuplicateService;

class DuplicateApiController extends AbstractAPIController {
	/** @var FileDuplicateService */
	private $fileDuplicateService;

	public function __construct(
		$appName,
		IRequest $request,
		?IUserSession $userSession,
		FileDuplicateService $fileDuplicateService,
		LoggerInterface $logger
	) {
		parent::__construct($appName, $request, $userSession, $logger);
		$this->fileDuplicateService = $fileDuplicateService;
	}

	/**
	 * @NoAdminRequired
	 * @NoCSRFRequired
	 */
	public function list(int $offset = 0, int $limit = 20): JSONResponse {
		try {
			$duplicates = $this->fileDuplicateService->findAll($this->getUserId(), $limit, $offset, true);
			return $this->success($duplicates);
		} catch (\Exception $e) {
			$this->logger->error('A unknown exception occured', ['app' => Application::ID, 'exception' => $e]);
			return $this->handleException($e);
		}
	}
}

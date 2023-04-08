<?php

namespace OCA\DuplicateFinder\BackgroundJob;

use OCP\IUserManager;
use OCP\IUser;
use OCP\IDBConnection;
use OCA\DuplicateFinder\Service\FileInfoService;
use OCA\DuplicateFinder\Service\ConfigService;
use OCP\AppFramework\Utility\ITimeFactory;

class FindDuplicates extends \OCP\BackgroundJob\TimedJob {
	/** @var IUserManager */
	private $userManager;
	/** @var FileInfoService*/
	private $fileInfoService;
	/** @var IDBConnection */
	protected $connection;


	/**
	 * @param IUserManager $userManager
	 * @param FileInfoService $fileInfoService
	 */
	public function __construct(
		ITimeFactory $time,
		IUserManager $userManager,
		IDBConnection $connection,
		FileInfoService $fileInfoService,
		ConfigService $config
	) {
		parent::__construct($time);
		$this->setInterval($config->getFindJobInterval());
		$this->userManager = $userManager;
		$this->connection = $connection;
		$this->fileInfoService = $fileInfoService;
	}

	/**
	 * @param mixed $argument
	 * @return void
	 * @throws \Exception
	 */
	protected function run($argument): void {
		$this->userManager->callForAllUsers(function (IUser $user): void {
			$this->fileInfoService->scanFiles($user->getUID());
		});
	}
}

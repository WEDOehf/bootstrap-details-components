<?php declare (strict_types = 1);

namespace Wedo\Tests\TestAppTests;

use Nette\Configurator;
use Nette\StaticClass;

class Bootstrap
{

	use StaticClass;

	public static function boot(): Configurator
	{
		$configurator = new Configurator();
		$rootDir = __DIR__ . '/..';
		$configurator->addConfig($rootDir . '/config/common.neon');
		$tempDir = $rootDir . '/../../temp';
		$configurator->setTempDirectory($tempDir);
		$configurator->addParameters(['wwwDir' => $tempDir]);
		return $configurator;
	}

}

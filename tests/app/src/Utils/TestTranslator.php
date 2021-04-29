<?php declare(strict_types = 1);

namespace Wedo\Tests\TestApp\Utils;

use Nette\Localization\ITranslator;

class TestTranslator implements ITranslator
{

	/**
	 * @param string|mixed $message
	 * @param mixed $parameters
	 */
	public function translate($message, ...$parameters): string
	{
		return $message . '*';
	}

}

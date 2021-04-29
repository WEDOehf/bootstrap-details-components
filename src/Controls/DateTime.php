<?php declare(strict_types = 1);

namespace Wedo\Details\Controls;

use DateTimeInterface;

class DateTime extends BaseControl
{

	public static string $default_format = 'd.m.Y H:i:s';

	protected string $format;

	public function __construct(Group $group, string $name, string $caption)
	{
		parent::__construct($group, $name, $caption);
		$this->format = self::$default_format;
	}


	public function getValue(): ?string
	{
		if ($this->value === null) {
			return null;
		}

		$dateTime = $this->value instanceof DateTimeInterface
			? $this->value
			: new \DateTime($this->value);

		return $dateTime->format($this->format);
	}


	/**
	 * @param mixed $format
	 */
	public function setFormat($format): DateTime
	{
		$this->format = $format;

		return $this;
	}


	/**
	 * DateTime format to be used when printing value
	 */
	public function getFormat(): string
	{
		return $this->format;
	}

}

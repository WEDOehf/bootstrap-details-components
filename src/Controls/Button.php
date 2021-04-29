<?php declare(strict_types = 1);

namespace Wedo\Details\Controls;

class Button extends Link
{

	/**
	 * @param mixed[]|null $params
	 */
	public function __construct(Group $group, string $name, string $caption, string $destination, ?array $params)
	{
		parent::__construct($group, $name, $caption, $destination, $params);
		$this->link->setAttribute('class', 'btn btn-default btn-xs');
	}

}

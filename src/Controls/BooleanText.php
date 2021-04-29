<?php declare(strict_types = 1);

namespace Wedo\Details\Controls;

class BooleanText extends Text
{

	protected bool $isTranslated = true;

	/** @var mixed */
	protected $value = 'No';

	/**
	 * @param mixed $value
	 */
	public function setValue($value): BaseControl
	{
		$this->value = (bool) $value
			? 'Yes'
			: 'No';

		return $this;
	}

}

<?php declare(strict_types = 1);

namespace Wedo\Details\Controls;

use Wedo\Details\Details;

/**
 * @method Group|Details getParent()
 */
class Text extends BaseControl
{

	protected bool $isTranslated = false;

	protected string $translationPrefix = '';

	public function setTranslated(bool $translated = true, string $prefix = ''): self
	{
		$this->isTranslated = $translated;
		$this->translationPrefix = $prefix;

		return $this;
	}


	public function getValue(): string
	{
		if ($this->isTranslated) {
			return $this->getParent()->getTranslator()->translate($this->translationPrefix . parent::getValue());
		}

		return (string) parent::getValue();
	}

}

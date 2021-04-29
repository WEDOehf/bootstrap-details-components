<?php declare(strict_types = 1);

namespace Wedo\Details\Controls;

use Nette\Utils\Html;

class Link extends BaseControl
{

	protected ?string $destination;

	/** @var mixed */
	protected $parameters;

	protected Html $link;

	/**
	 * @param mixed[] $params
	 */
	public function __construct(Group $group, string $name, ?string $caption, ?string $destination, ?array $params = null)
	{
		parent::__construct($group, $name, $caption ?? $name);

		$this->destination = $destination;
		$this->parameters = $params;

		$this->link = Html::el('a');
	}


	public function getLink(): Html
	{
		return $this->link;
	}


	/**
	 * @return $this
	 */
	public function setLink(Html $link): Link
	{
		$this->link = $link;

		return $this;
	}


	public function setAjax(bool $ajax = true): Link
	{
		if ($ajax) {
			$this->link->setAttribute('class', $this->link->getAttribute('class') . ' ajax');
		} else {
			$this->link->setAttribute('class', str_replace([' ajax', 'ajax'], '', $this->link->getAttribute('class')));
		}

		return $this;
	}


	public function getValue(): Html
	{
		$this->link->setHtml($this->value);

		return $this->link;
	}


	/**
	 * @return mixed[]
	 */
	protected function getParameterValues(): array
	{
		$data = $this->getDataSource();
		$params = [];

		if (is_callable($this->parameters)) {
			$params = call_user_func($this->parameters, $this, $data);
		} elseif (is_array($this->parameters)) {
			foreach ($this->parameters as $param => $paramValue) {
				$params[$param] = $data->$paramValue; //@phpstan-ignore-line
			}
		}

		return $params;
	}


	public function render(): ?Html
	{
		$href = $this->getPresenter()->link($this->destination ?? '', $this->getParameterValues());
		$this->link->setAttribute('href', $href);

		return parent::render();
	}

}

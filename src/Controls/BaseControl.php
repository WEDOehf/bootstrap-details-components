<?php declare(strict_types = 1);

namespace Wedo\Details\Controls;

use Exception;
use Nette\Application\UI\Control;
use Nette\ComponentModel\IContainer;
use Nette\Utils\Html;

/**
 * @method onRender($control, $details)
 * @property IContainer $parent
 */
class BaseControl extends Control
{

	protected string $caption;

	/** @var mixed */
	protected $value = null;

	protected Html $container;

	protected Html $label;

	protected Html $htmlValue;

	protected bool $hideOnEmpty = true;

	/** @var mixed[] */
	protected array $concatenatedValues = [];

	protected object $dataSource;

	/** @var callable */
	protected $condition;

	/** @var callable[] */
	public array $onRender = [];

	public function __construct(Group $group, string $name, string $caption)
	{
		$group->addComponent($this, $name);

		$this->setCaption($caption);

		if ($group->dataSource === null) {
			throw new Exception('Datasource not set!');
		}

		if (isset($group->dataSource->$name)) { //@phpstan-ignore-line
			$this->setValue($group->dataSource->$name); //@phpstan-ignore-line
		}

		$this->dataSource = $group->dataSource;

		$this->container = Html::el('tr');
		$this->label = Html::el('th');
		$this->htmlValue = Html::el('td');
	}


	public function setCaption(string $caption): BaseControl
	{
		$this->caption = $caption;

		return $this;
	}


	public function getCaption(): ?string
	{
		return $this->caption;
	}


	/**
	 * @return mixed
	 */
	public function getValue()
	{
		return $this->value ?? '';
	}


	/**
	 * @param mixed $value
	 */
	public function setValue($value): self
	{
		$this->value = $value;

		return $this;
	}


	public function setHideOnempty(bool $hide = true): self
	{
		$this->hideOnEmpty = $hide;

		return $this;
	}


	public function getDataSource(): object
	{
		return $this->dataSource;
	}


	public function getContainer(): Html
	{
		return $this->container;
	}


	public function getLabelPart(): Html
	{
		return $this->label;
	}


	public function getValuePart(): Html
	{
		return $this->htmlValue;
	}


	public function addRender(callable $callback): self
	{
		$this->onRender[] = $callback;

		return $this;
	}


	public function addConcat(BaseControl $control, string $delimiter = ' '): self
	{
		$this->concatenatedValues[] = [$control, $delimiter];
		$this->parent->removeComponent($control);

		return $this;
	}


	public function setCondition(callable $condition): self
	{
		$this->condition = $condition;

		return $this;
	}


	public function isEmpty(): bool
	{
		return strlen(trim($this->getValuePart()->getText())) === 0;
	}


	public function render(): ?Html
	{
		if (isset($this->condition) && empty(call_user_func($this->condition, $this, $this->getDataSource()))) { //@phpstan-ignore-line
			return null;
		}

		$this->label->addHtml($this->getCaption() ?? '');
		$this->htmlValue->addHtml($this->getValue() ?? '');
		$this->container->addHtml($this->getLabelPart());
		$this->container->addHtml($this->getValuePart());

		$this->onRender($this, $this->getDataSource());

		$i = 0;

		foreach ($this->concatenatedValues as $concat) {
			$this->addComponent($concat[0], '__child_' . $i);
			$i++;
			$concat[0]->render();

			if (!$concat[0]->isEmpty()) {
				if (!$this->isEmpty()) {
					$this->getValuePart()->addText($concat[1]);
				}

				$this->getValuePart()->addHtml($concat[0]->getValuePart()->getHtml());
			}
		}

		if ($this->hideOnEmpty && $this->isEmpty()) {
			return null;
		}

		return $this->container;
	}

}

<?php declare(strict_types = 1);

namespace Wedo\Details\Controls;

use DateTimeInterface;
use Exception;
use Nette\Application\UI\Control;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Localization\ITranslator;
use Nette\Utils\Html;
use Nette\Utils\Strings;
use Wedo\Details\Details;

/**
 * @property Template $template
 */
class Group extends Control
{

	protected ITranslator $translator;

	public ?object $dataSource;

	protected string $templateFile;

	protected ?string $caption = null;

	protected ?int $fixedColumnWidth = null;

	/** @var Html[] */
	protected array $renderedComponents = [];

	public function __construct(Details $details, string $name, ?object $dataSource, ?string $caption)
	{
		$details->addComponent($this, $name);
		$this->translator = $details->getTranslator();
		$this->caption = $caption;
		$this->dataSource = $dataSource;
	}


	public function setCaption(string $caption): Group
	{
		$this->caption = $caption;

		return $this;
	}


	public function getCaption(): ?string
	{
		return $this->caption;
	}


	public function setFixedColumnWidth(int $width): Group
	{
		$this->fixedColumnWidth = $width;

		return $this;
	}


	public function getFixedColumnWidth(): ?int
	{
		return $this->fixedColumnWidth;
	}


	public function addText(string $name, ?string $caption = null): Text
	{
		return new Text($this, $name, $caption ?? $this->createCaption($name));
	}


	public function addBooleanText(string $name, ?string $caption = null): BooleanText
	{
		return new BooleanText($this, $name, $caption ?? $this->createCaption($name));
	}


	public function addDateTime(string $name, ?string $caption = null, ?string $format = null): DateTime
	{
		$detail = new DateTime($this, $name, $caption ?? $this->createCaption($name));

		if ($format !== null) {
			$detail->setFormat($format);
		}

		return $detail;
	}


	/**
	 * @param mixed[] $params
	 */
	public function addLink(string $name, ?string $caption = null, ?string $destination = null, ?array $params = null): Link
	{
		return new Link($this, $name, $caption, $destination ?? 'this', $params);
	}


	/**
	 * @param mixed[] $params
	 */
	public function addButton(string $name, ?string $caption = null, ?string $destination = null, ?array $params = null): Button
	{
		return new Button($this, $name, $caption ?? $name, $destination ?? 'this', $params);
	}


	public function addFromDataSource(): void
	{
		if ($this->dataSource === null) {
			throw new Exception('Datasource is not set!');
		}

		$vars = get_object_vars($this->dataSource);

		foreach ($vars as $key => $value) {
			if ($value instanceof DateTimeInterface) {
				$this->addDateTime($key);
				continue;
			}

			if (is_object($value) || is_array($value)) {
				continue;
			}

			$this->addText($key);

		}
	}


	public function render(): void
	{
		$isEmpty = true;

		/** @var BaseControl $component */
		foreach ($this->getComponents() as $component) {
			$rendered = $component->render();

			if ($rendered !== null) {
				$isEmpty = false;
				$this->renderedComponents[] = $rendered;
			}
		}

		if ($isEmpty) {
			return;
		}

		$this->template->setTranslator($this->translator);
		$this->template->setFile($this->getTemplateFile());
		$this->template->render();
	}


	/**
	 * @return Html[]
	 */
	public function getRenderedComponents(): array
	{
		return $this->renderedComponents;
	}


	public function getTemplateFile(): string
	{
		if (isset($this->templateFile)) {
			return $this->templateFile;
		}

		return __DIR__ . '/Group.latte';
	}


	public function setTemplateFile(string $templateFile): Group
	{
		$this->templateFile = $templateFile;

		return $this;
	}


	public function getTranslator(): ITranslator
	{
		return $this->translator;
	}


	protected function createCaption(string $name): string
	{
		$caption = Strings::firstUpper($name);
		$caption = str_replace('_', ' ', $caption);

		return $caption;
	}

}

<?php declare(strict_types = 1);

namespace Wedo\Details;

use Exception;
use Nette\Application\UI\Control;
use Nette\Bridges\ApplicationLatte\Template;
use Nette\Localization\ITranslator;
use Wedo\Details\Controls\Group;

class Details extends Control
{

	public static string $defaultTemplateFile = __DIR__ . '/Default.latte';

	protected ITranslator $translator;

	protected string $template_file;

	protected ?object $dataSource;

	/** @var array<int|string, Group[]> */
	protected array $group_columns = [];

	protected int $group_columns_length = 2;

	protected int $group_columns_index = 0;

	protected string $group_columns_breakpoint = 'md';

	public function __construct(ITranslator $translator, ?object $data_source = null)
	{
		$this->dataSource = $data_source;
		$this->setTranslator($translator);
	}


	public function addGroup(string $name, string $caption, ?object $dataSource = null, ?string $column = null): Group
	{
		if ($dataSource === null) {
			$dataSource = $this->dataSource;
		}

		$group = new Group($this, $name, $dataSource, $caption);

		if ($column !== null) {
			$this->group_columns[$column][] = $group;
		} else {
			$this->group_columns[$this->group_columns_index][] = $group;
			$this->group_columns_index++;

			if ($this->group_columns_index >= $this->group_columns_length) {
				$this->group_columns_index = 0;
			}
		}

		return $group;
	}


	public function getTranslator(): ITranslator
	{
		return $this->translator;
	}


	public function setTranslator(ITranslator $translator): Details
	{
		$this->translator = $translator;

		return $this;
	}


	public function render(): void
	{
		/** @var Template $template */
		$template = $this->getTemplate();
		$template->setTranslator($this->getTranslator());
		$template->setFile($this->getTemplateFile());
		$template->group_columns = $this->group_columns;
		$template->group_columns_breakpoint = $this->group_columns_breakpoint;
		$template->render();
	}


	public function getTemplateFile(): string
	{
		if (isset($this->template_file)) {
			return $this->template_file;
		}

		return self::$defaultTemplateFile;
	}


	public function setTemplateFile(string $template_file): Details
	{
		$this->template_file = $template_file;

		return $this;
	}


	public function getDataSource(): ?object
	{
		return $this->dataSource;
	}


	public function setDataSource(object $dataSource): Details
	{
		$this->dataSource = $dataSource;

		return $this;
	}


	/**
	 * Sets the number of columns per line.
	 *
	 * @throws Exception
	 */
	public function setColumnsAmount(int $amount): Details
	{
		if ($amount > 12) {
			throw new Exception('Column count cannot be greater than 12');
		}

		if ($this->group_columns !== []) {
			throw new Exception('Column count cannot be changed after a group has been added');
		}

		$this->group_columns_length = $amount;

		return $this;
	}


	/**
	 * Sets the bootstrap break point for columns.
	 *
	 * @param string $size xs, sm, md, lg
	 */
	public function setColumnsBreakpoint(string $size): Details
	{
		$this->group_columns_breakpoint = $size;

		return $this;
	}


	public function reload(): void
	{
		if ($this->getPresenter()->isAjax()) {
			$this->redrawControl('details');
		} else {
			$this->getPresenter()->redirect('this');
		}
	}

}

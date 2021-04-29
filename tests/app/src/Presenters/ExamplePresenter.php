<?php declare(strict_types = 1);

namespace Wedo\Tests\TestApp\Presenters;

use DateTime;
use Nette\Application\UI\Presenter;
use Nette\SmartObject;
use Nette\Utils\ArrayHash;
use stdClass;
use Wedo\Details\Controls\Text;
use Wedo\Details\Details;
use Wedo\Tests\TestApp\Utils\TestTranslator;

class ExamplePresenter extends Presenter
{

	use SmartObject;

	public function actionLink(int $id): void
	{
		$this->sendJson([]);
	}

	public function createComponentDetails(): Details
	{
		$dataSource = ArrayHash::from(['a' => 'b', 'created' => new DateTime('2021-04-29')]);
		$details = new Details(new TestTranslator(), $dataSource);
		$group = $details->addGroup('All', 'All');
		$group->addFromDataSource();

		return $details;
	}

	public function createComponentDetailsTwoGroup(): Details
	{
		$dataSource = new stdClass();
		$dataSource->group1 = new stdClass();
		$dataSource->group2 = new stdClass();
		$dataSource->group1->name = 'Title';
		$dataSource->group1->created = new DateTime('2021-04-29');
		$dataSource->group1->active = true;

		$dataSource->group2->name = 'Title2';
		$dataSource->group2->created = new DateTime('2021-04-30');
		$dataSource->group2->amount = 10.25;
		$dataSource->group2->id = 15;
		$dataSource->group2->id2 = 17;
		$dataSource->group2->active = false;

		$details = new Details(new TestTranslator(), $dataSource);
		$group1 = $details->addGroup('group1', 'Group 1', $dataSource->group1);
		$group2 = $details->addGroup('group2', 'Group 2', $dataSource->group2);

		$group1->addFromDataSource();

		$text = $group2->addText('name')->setTranslated(true, 'bla-');
		$text->addRender(fn (Text $text, $dataSource) => $text->getContainer()->addAttributes(['data-id' => $dataSource->id]));
		$text->addConcat($group2->addDateTime('created')->setFormat('Y'));

		$group2->addLink('id', 'Link', 'Example:link', ['id']);
		$group2->addBooleanText('active');
		$group2->addDateTime('created')->setFormat('d.m.Y');
		$group2->addButton('button', 'Button', 'Example:link', ['id'])->setHideOnempty(false);
		return $details;
	}

}

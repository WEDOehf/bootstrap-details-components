<?php declare(strict_types = 1);

namespace Wedo\Tests\TestAppTests\Presenters;

use Wavevision\NetteTests\Runners\PresenterRequest;
use Wavevision\NetteTests\TestCases\PresenterTestCase;
use Wedo\Tests\TestApp\Presenters\ExamplePresenter;

class ExamplePresenterTest extends PresenterTestCase
{

	public function testDetailsFromSource(): void
	{
		$content = $this->extractTextResponseContent($this->runPresenter(new PresenterRequest(ExamplePresenter::class, 'default')));
		$this->assertStringContainsString('<h3 class="panel-title">All</h3>', $content);
		$this->assertStringContainsString('<tr><th>A</th><td>b</td></tr>', $content);
		$this->assertStringContainsString('<tr><th>Created</th><td>29.04.2021 00:00:00</td></tr>', $content);
	}

	public function testDetailsTwoGroup(): void
	{
		$content = $this->extractTextResponseContent($this->runPresenter(new PresenterRequest(ExamplePresenter::class, 'twoGroup')));
		$this->assertStringContainsString('<h3 class="panel-title">Group 1</h3>', $content);
		$this->assertStringContainsString('<h3 class="panel-title">Group 2</h3>', $content);
		$this->assertStringContainsString(' <tr data-id="15"><th>Name</th><td>bla-Title2* 2021</td></tr>', $content);
		$this->assertStringContainsString('<tr><th>Created</th><td>30.04.2021</td></tr>', $content);
		$this->assertStringContainsString('<tr><th>Active</th><td>No*</td></tr>', $content);
		$this->assertStringContainsString('<tr><th>Link</th><td><a href="/example/link/15">15</a></td></tr>', $content);
		$this->assertStringContainsString('<tr><th>Button</th><td><a class="btn btn-default btn-xs" href="/example/link/15"></a></td></tr>', $content);
	}

}

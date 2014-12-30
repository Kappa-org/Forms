<?php
/**
 * This file is part of the Kappa\Forms package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 *
 * @testCase
 */

namespace Kappa\Forms\Tests;

use Kappa\Forms\Controls\SelectBox;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';

/**
 * Class SelectBoxTest
 *
 * @package Kappa\Forms\Tests
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class SelectBoxTest extends TestCase
{
	public function testRemoveItem()
	{
		$selectBox = new SelectBox();
		$selectBox->setItems(['zero', 'one', 'two', 'string' => 'four']);
		Assert::count(4, $selectBox->getItems());
		Assert::equal(['zero', 'one', 'two', 'string' => 'four'], $selectBox->getItems());
		$selectBox->removeItem(1);
		Assert::count(3, $selectBox->getItems());
		Assert::equal(['zero', 2 => 'two', 'string' => 'four'], $selectBox->getItems());
		$selectBox->removeItem('string');
		Assert::count(2, $selectBox->getItems());
		Assert::equal(['zero', 2 => 'two'], $selectBox->getItems());
		Assert::throws(function () {
			$selectBox = new SelectBox();
			$selectBox->removeItem('some');
		}, 'Kappa\Forms\ItemNotFoundException');

	}
}

\run(new SelectBoxTest());

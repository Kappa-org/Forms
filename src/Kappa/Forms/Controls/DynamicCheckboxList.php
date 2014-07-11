<?php
/**
 * This file is part of the Kappa\Forms package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\Forms\Controls;

use Nette\Forms\Controls\CheckboxList;

/**
 * Class Typeahead
 * @package Kappa\Forms\Controls
 */
class DynamicCheckboxList extends CheckboxList
{
	/**
	 * @return array
	 */
	public function getValue()
	{
		return array_values($this->value);
	}
}
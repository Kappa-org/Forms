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

use Kappa\Forms\ItemNotFoundException;

/**
 * Class SelectBox
 *
 * @package Kappa\Forms\Controls
 * @author Ondřej Záruba <http://zaruba-ondrej.cz>
 */
class SelectBox extends \Nette\Forms\Controls\SelectBox
{
	/**
	 * @param mixed $identifier
	 */
	public function removeItem($identifier)
	{
		$items = $this->getItems();
		if (!array_key_exists($identifier, $items)) {
			throw new ItemNotFoundException("Item with '{$identifier}' index has not been found");
		}
		unset($items[$identifier]);
		$this->setItems($items);
	}
}

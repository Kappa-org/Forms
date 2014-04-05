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

use Nette\Forms\Controls\TextBase;

/**
 * Class AntiSpam
 * @package Kappa\Forms\Controls
 */
class AntiSpam extends TextBase
{
	/**
	 * @param string|null $label
	 */
	public function __construct($label = null)
	{
		parent::__construct($label);
		$this->control->type = "text";
	}

	/**
	 * @return \Nette\Utils\Html
	 */
	public function getControl()
	{
		$control = parent::getControl();
		$control->addAttributes(array('data-kappa' => 'Kappa-checkControl'));
		return $control;
	}
}

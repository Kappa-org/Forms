<?php
/**
 * This file is part of the Kappa\Forms package.
 *
 * (c) Ondřej Záruba <zarubaondra@gmail.com>
 *
 * For the full copyright and license information, please view the license.md
 * file that was distributed with this source code.
 */

namespace Kappa\Forms;

/**
 * Interface IFormFactory
 * @package Kappa\Forms
 */
interface IFormFactory
{
	/**
	 * @return \Nette\Forms\Form
	 */
	public function create();
} 
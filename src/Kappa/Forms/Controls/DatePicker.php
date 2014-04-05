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

use Kappa\InvalidArgumentException;
use Kappa\InvalidStateException;
use Kappa\Utils\Validators;
use Nette\DateTime;
use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Form;
use Nette\Forms\IControl;
use Nette\Forms\Rule;
use Nette\Forms\Rules;

/**
 * @author Jan Tvrdík
 */
class DatePicker extends BaseControl
{
	/** @link    http://dev.w3.org/html5/spec/common-microsyntaxes.html#valid-date-string */
	const W3C_DATE_FORMAT = 'Y-m-d';

	/** @var     DateTime|NULL     internal date reprezentation */
	protected $value;

	/** @var     string            value entered by user (unfiltered) */
	protected $rawValue;

	/** @var     string            class name */
	private $className = 'date';



	/**
	 * @param null $label
	 */
	public function __construct($label = null)
	{
		parent::__construct($label);
		$this->control->type = 'date';
	}



	/**
	 * @return string
	 */
	public function getClassName()
	{
		return $this->className;
	}



	/**
	 * @param $className
	 * @return DatePicker
	 */
	public function setClassName($className)
	{
		$this->className = $className;
		return $this;
	}



	/**
	 * @return \Nette\Utils\Html
	 */
	public function getControl()
	{
		$control = parent::getControl();
		$control->addClass($this->className);
		list($min, $max) = $this->extractRangeRule($this->getRules());
		if ($min !== null) $control->min = $min->format(self::W3C_DATE_FORMAT);
		if ($max !== null) $control->max = $max->format(self::W3C_DATE_FORMAT);
		if ($this->value) $control->value = $this->value->format(self::W3C_DATE_FORMAT);
		return $control;
	}

	/**
	 * @param $value
	 * @return $this|BaseControl
	 * @throws \Kappa\InvalidArgumentException
	 */
	public function setValue($value)
	{
		if ($value instanceof \DateTime) {

		} elseif (is_int($value)) { // timestamp

		} elseif (empty($value)) {
			$rawValue = $value;
			$value = null;

		} elseif (is_string($value)) {
			$rawValue = $value;

			if (preg_match('#^(?P<dd>\d{1,2})[. -] *(?P<mm>\d{1,2})([. -] *(?P<yyyy>\d{4})?)?$#', $value, $matches)) {
				$dd = $matches['dd'];
				$mm = $matches['mm'];
				$yyyy = isset($matches['yyyy']) ? $matches['yyyy'] : date('Y');

				if (checkdate($mm, $dd, $yyyy)) {
					$value = "$yyyy-$mm-$dd";
				} else {
					$value = null;
				}
			}

		} else {
			throw new InvalidArgumentException("String {$value} is invalid argument!", 0);
		}

		if ($value !== null) {
			// DateTime constructor throws Exception when invalid input given
			try {
				$value = DateTime::from($value); // clone DateTime when given
			} catch (\Exception $e) {
				$value = null;
			}
		}

		if (!isset($rawValue) && isset($value)) {
			$rawValue = $value->format(self::W3C_DATE_FORMAT);
		}

		$this->value = $value;
		$this->rawValue = $rawValue;

		return $this;
	}



	/**
	 * @return string
	 */
	public function getRawValue()
	{
		return $this->rawValue;
	}

	/**
	 * @param \Nette\Forms\IControl $control
	 * @return bool
	 * @throws \Kappa\InvalidStateException
	 */
	public static function validateFilled(IControl $control)
	{
		if (!$control instanceof self) throw new InvalidStateException('Unable to validate ' . get_class($control) . ' instance.');
		$rawValue = $control->rawValue;
		return !empty($rawValue);
	}



	/**
	 * @static
	 * @param \Nette\Forms\IControl $control
	 * @return bool
	 * @throws \Kappa\InvalidStateException
	 */
	public static function validateValid(IControl $control)
	{
		if (!$control instanceof self) throw new InvalidStateException('Unable to validate ' . get_class($control) . ' instance.');
		$value = $control->value;
		return (empty($control->rawValue) || $value instanceof \DateTime);
	}



	/**
	 * @static
	 * @param \Nette\Forms\IControl $control
	 * @param $range
	 * @return bool
	 */
	public static function validateRange(IControl $control, $range)
	{
		return Validators::isInRange($control->getValue(), $range);
	}



	/**
	 * @param \Nette\Forms\Rules $rules
	 * @return array
	 */
	private function extractRangeRule(Rules $rules)
	{
		$controlMin = $controlMax = null;
		foreach ($rules as $rule) {
			if ($rule->type === Rule::VALIDATOR) {
				if ($rule->operation === Form::RANGE && !$rule->isNegative) {
					$ruleMinMax = $rule->arg;
				}

			} elseif ($rule->type === Rule::CONDITION) {
				if ($rule->operation === Form::FILLED && !$rule->isNegative && $rule->control === $this) {
					$ruleMinMax = $this->extractRangeRule($rule->subRules);
				}
			}

			if (isset($ruleMinMax)) {
				list($ruleMin, $ruleMax) = $ruleMinMax;
				if ($ruleMin !== null && ($controlMin === null || $ruleMin > $controlMin)) $controlMin = $ruleMin;
				if ($ruleMax !== null && ($controlMax === null || $ruleMax < $controlMax)) $controlMax = $ruleMax;
				$ruleMinMax = null;
			}
		}
		return array($controlMin, $controlMax);
	}

}
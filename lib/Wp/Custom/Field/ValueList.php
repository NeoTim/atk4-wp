<?php

/* =====================================================================
 * Atk4-wp => An Agile Toolkit PHP framework interface for WordPress.
 *
 * This interface enable the use of the Agile Toolkit framework within a WordPress site.
 *
 * Please note that atk or atk4 mentioned in comments refer to Agile Toolkit or Agile Toolkit version 4.
 * More information on Agile Toolkit: http://www.agiletoolkit.org
 *
 * Author: Alain Belair
 * Licensed under MIT
 * =====================================================================*/
/**
 * Valuelist field in Wordpress custom form.
 */
class Wp_Custom_Field_ValueList extends Wp_Custom_Field
{
	// array of available values
	public $value_list = array();

	// default empty text message
	public $default_empty_text = 'Please, select ...';

	// current empty text message and ID
	public $empty_text = null;
	protected $empty_value = ''; // don't change this value

	// value separator, for internal use
	protected $separator = ',';



	/**
	 * Sets model of form field
	 *
	 * @param Model $m
	 *
	 * @return Model
	 */
	public function setModel($m)
	{
		$ret = parent::setModel($m);
		$this->setValueList(array());
		return $ret;
	}

	/**
	 * Set value list of form field
	 *
	 * @param array $list
	 *
	 * @return $this
	 */
	public function setValueList($list)
	{
		$this->value_list = $list;
		return $this;
	}

	/**
	 * Sets default text which is displayed on a null-value option.
	 *
	 * Set to "Select.." or "Pick one.."
	 *
	 * @param string $text Pass UNDEFINED to use default text, empty string - disable
	 *
	 * @return $this
	 */
	public function setEmptyText($text = UNDEFINED)
	{
		$this->empty_text = $text === null ? $this->default_empty_text : $text;
		return $this;
	}

	/**
	 * Validate POSTed field value
	 *
	 * @return boolean
	 */
	public function validate()
	{
		if (!$this->value) {
			return parent::validate();
		}

		// load allowed values in values_list
		// @todo Imants: Actually we don't need to load all values from Model in
		//       array, just to check couple of posted values.
		//       Probably we should do SELECT * FROM t WHERE id IN ($values) or
		//       something like that to limit array size and time spent on
		//       parsing all DB records in Model.
		$this->getValueList();

		$values = explode($this->separator, $this->value);
		foreach ($values as $v) {
			if (!isset($this->value_list[$v])) {
				$this->displayFieldError("Value $v is not one of the offered values");
				return parent::validate();
			}
		}

		return parent::validate();
	}

	/**
	 * Return value list
	 *
	 * @return array
	 */
	public function getValueList()
	{
		// add model data rows in value list
		if ($this->model) {
			$id = $this->model->id_field;
			$title = $this->model->getTitleField();

			$this->value_list = array();
			foreach ($this->model as $row) {
				$this->value_list[(string)$row[$id]] = $row[$title];
			}
		}

		// prepend empty text message at the begining of value list if needed
		if ($this->empty_text && !isset($this->value_list[$this->empty_value])) {
			$this->value_list = array($this->empty_value => $this->empty_text) + $this->value_list;
		}

		return $this->value_list;
	}

	/**
	 * Normalize POSTed data
	 *
	 * @return void
	 */
	public function normalize()
	{
		$data = $this->get();
		if (is_array($data)) {
			$data = join($this->separator, $data);
		}
		$data = trim($data, $this->separator);

		if (get_magic_quotes_gpc()) {
			$this->set(stripslashes($data));
		} else {
			$this->set($data);
		}

		return parent::normalize();
	}
}
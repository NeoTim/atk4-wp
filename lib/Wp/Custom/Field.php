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
 * Adapt custom form field for Wordpress.
 * Add a property Id. By default set to $this->name but
 * id property will be set when form widget is display using
 * wordpress Widget function $widget->get_field_id( 'field_name' );
 */
class Wp_Custom_Field extends Form_Field
{

	public $id;
	public function init()
	{
		parent::init();
		$this->id = $this->name;
	}

	/**
	 * Need to redefine this function since Widget form field
	 * required the id be different of the name.
	 * @param array $attr
	 *
	 * @return string
	 */
	public function getInput($attr = array())
	{
		// This function returns HTML tag for the input field. Derived classes
		// should inherit this and add new properties if needed
		return $this->getTag('input',
			array_merge(array(
				'name'=>$this->name,
				'data-shortname'=>$this->short_name,
				'id'=>$this->id,
				'value'=>$this->value,
			),$attr,$this->attr)
		);
	}
}
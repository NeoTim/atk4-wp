<?php

/**
 * Created by abelair.
 * Date: 2016-03-25
 * Time: 1:34 PM
 */
class Wp_Widget_Field_Line extends Wp_Widget_Field
{
	public function getInput( $attr=array() )
	{
		return parent::getInput( array_merge( array('type'=>'text'), $attr ));
	}
}
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
 * Wordpress Post table as an atk model.
 *
 */

class Model_WpPosts extends SQL_Model
{
	public function init()
	{
		$this->table = WpHelper::getDbPrefix() . 'posts';
		$this->id_field = 'ID';
		parent::init();

		$this->addField('post_name');
		$this->title_field ='post_name';

		$this->addField('post_author');
		$this->addField('post_date');
		$this->addField('post_date_gmt');
		$this->addField('post_content');
		$this->addField('post_title');
		$this->addField('post_excerpt');
		$this->addField('post_status');
		$this->addField('comment_status');
		$this->addField('ping_status');
		$this->addField('post_password');
		$this->addField('to_ping');
		$this->addField('pinged');
		$this->addField('post_modified');
		$this->addField('post_modified_gmt');
		$this->addField('post_content_filtered');
		$this->addField('post_parent');
		$this->addField('guid');
		$this->addField('menu_order');
		$this->addField('post_type');
		$this->addField('post_mime_type');
		$this->addField('comment_count');
	}
}
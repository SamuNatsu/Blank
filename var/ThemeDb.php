<?php

namespace Blank;

class ThemeDb
{
	private static $instance = null;

	private $db = null;
	private $data = null;
	private $is_new = false;
	private $modified = false;

	public static function get(): \Blank\ThemeDb
	{
		if (self::$instance === null)
		{
			self::$instance = new \Blank\ThemeDb();
		}

		return self::$instance;
	}

	public function __construct()
	{
		$this->db = \Typecho\Db::get();
		$this->data = $this->db->fetchAll(
			$this->db->select()->from('table.options')->where('name = ?', 'theme:blank:data')
		);
		if (count($this->data) === 0)
		{
			$this->data = [];
			$this->is_new = true;
		}
		else
		{
			$this->data = unserialize($this->data[0]['value']);
		}
	}

	public function __destruct()
	{
		if ($this->is_new === true)
		{
			$this->db->query(
				$this->db->insert('table.options')
					->rows([
						'name' => 'theme:blank:data', 
						'user' => '0', 
						'value' => serialize($this->data)
					])
			);
		}
		else if ($this->modified === true)
		{
			$this->db->query(
				$this->db->update('table.options')->where('name = ?', 'theme:blank:data')->rows(['value' => serialize($this->data)])
			);
		}
	}

	public function __get($property)
	{
		return $this->data[$property] ?? null;
	}

	public function __set($property, $value)
	{
		$this->data[$property] = $value;
		$this->modified = true;
	}

	public function __isset($property)
	{
		return isset($this->data[$property]);
	}

	public function __unset($property)
	{
		if (isset($this->data[$property]))
		{
			unset($this->data[$property]);
			$this->modified = true;
		}	
	}
}

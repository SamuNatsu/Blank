<?php

namespace Blank;

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

/**
 * Theme database manager
 */
class Db
{
	/**
	 * Theme database version
	 * 
	 * @var int
	 */
	public const VERSION = 1;

	/**
	 * Manager instance
	 * 
	 * @var \Blank\Db
	 */
	private static $instance;

	/**
	 * Typecho database instance
	 * 
	 * @var \Typecho\Db
	 */
	private $db;

	/**
	 * Theme database cache data
	 * 
	 * @var array
	 */
	private $data;

	/**
	 * Is new theme database
	 * 
	 * @var bool
	 */
	private $is_new = false;

	/**
	 * Is theme database modified
	 * 
	 * @var bool
	 */
	private $is_modified = false;

	/**
	 * Get manager instance
	 * 
	 * @return \Blank\Db
	 */
	public static function get(): \Blank\Db
	{
		// Check instance
		if (self::$instance === null)
		{
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor
	 */
	private function __construct()
	{
		// Initialize typecho database instance & fetch theme data 
		$this->db = \Typecho\Db::get();
		$this->data = $this->db->fetchAll(
			$this->db->select()
				->from('table.options')
				->where('name = ?', 'theme:blank:data')
		);

		// Check data available
		if (count($this->data) === 0)
		{
			$this->data = ['VERSION' => self::VERSION];
			$this->is_new = true;
		}
		else
		{
			$this->data = unserialize($this->data[0]['value']);
		}
	}

	/**
	 * Destructor
	 */
	public function __destruct()
	{
		$this->sync();
	}

	/**
	 * Synchronize database
	 * 
	 * @return void
	 */
	public function sync(): void
	{
		if ($this->is_new === true)
		{
			// Insert theme data
			$this->db->query(
				$this->db->insert('table.options')
					->rows([
						'name' => 'theme:blank:data', 
						'user' => '0', 
						'value' => serialize($this->data)
					])
			);
		}
		else if ($this->is_modified === true)
		{
			// Update theme data
			$this->db->query(
				$this->db->update('table.options')
					->where('name = ?', 'theme:blank:data')
					->rows(['value' => serialize($this->data)])
			);
		}

		// Reset flags
		$this->is_new = false;
		$this->is_modified = false;
	}

	/**
	 * Get data
	 * 
	 * @param string $name
	 * 
	 * @return mixed
	 */
	public function __get(string $name): mixed
	{
		return $this->data[$name] ?? null;
	}

	/**
	 * Set data
	 * 
	 * @param string $name
	 * @param mixed $value
	 * 
	 * @return void
	 */
	public function __set(string $name, mixed $value): void
	{
		$this->data[$name] = $value;
		$this->is_modified = true;
	}

	/**
	 * Check data isset
	 * 
	 * @param string $name
	 * 
	 * @return bool
	 */
	public function __isset(string $name): bool
	{
		return isset($this->data[$name]);
	}

	/**
	 * Unset data
	 * 
	 * @param string $name
	 * 
	 * @return void
	 */
	public function __unset(string $name): void
	{
		if (isset($this->data[$name]))
		{
			unset($this->data[$name]);
			$this->is_modified = true;
		}	
	}
}

<?php

namespace Blank;

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

require_once 'Db.php';

/**
 * Used theme database items
 * 
 * updated | bool | Theme database update flag
 * VERSION | int  | Theme database version
 */

 /**
  * Theme update system
  */
class Update
{
	/**
	 * Auto update
	 * 
	 * @return void
	 */
	public static function autoUpdate(): void
	{
		// Initialize theme database
		$db = \Blank\Db::get();
		$last = ($db->VERSION ?? 0);

		// Update step by step
		while ($last < \Blank\Db::VERSION)
		{
			++$last;
			$method = "update_{$last}";
			self::{$method}();
		}
	}

	/**
	 * Display update banner
	 * 
	 * @return void
	 */
	public static function updatedBanner(): void
	{
		// Check updated flag
		$db = \Blank\Db::get();
		if ($db->updated === true)
		{
?>
<div class="bt-banner bt-banner-success"><?php _e('主题数据库已升级至版本：v'); ?><?= \Blank\Db::VERSION ?></div>
<?php
			// Reset flag & synchronize database
			$db->updated = false;
			$db->sync();
		}
	}

	/**
	 * Update from v0~v1
	 * 
	 * @return void
	 */
	private static function update_1(): void
	{
		// Initialize typecho database
		$db = \Typecho\Db::get();
		$options = $db->fetchAll(
			$db->select()
				->from('table.options')
				->where('name = ?', 'theme:blank')
		);
		$options = unserialize($options[0]['value']);

		// Option name update
		if (isset($options['maintainMode']))
		{
			$options['isMaintaining'] = $options['maintainMode'];
			unset($options['maintainMode']);
		}
		if (isset($options['minify']))
		{
			$options['isMinified'] = $options['minify'];
			unset($options['minify']);
		}
		if (isset($options['ccLicense']))
		{
			$options['defaultCC'] = $options['ccLicense'];
			unset($options['ccLicense']);
		}
		if (isset($options['favicon']))
		{
			$options['faviconUrl'] = $options['favicon'];
			unset($options['favicon']);
		}
		if (isset($options['gravatarService']))
		{
			$options['gravatarMirror'] = $options['gravatarService'];
			unset($options['gravatarService']);
		}

		// Synchronize typecho database
		$db->query(
			$db->update('table.options')
				->where('name = ?', 'theme:blank')
				->rows(['value' => serialize($options)])
		);

		// Initialize theme database
		$db = \Blank\Db::get();

		// Option name update
		if (isset($db->last_statistic_total_views))
		{
			$db->count_total_views_timestamp = $db->last_statistic_total_views;
			unset($db->last_statistic_total_views);
		}

		// Set flags
		$db->updated = true;
		$db->VERSION = 1;

		// Synchronize theme database
		$db->sync();
	}
}

<?php

namespace Blank;

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

require_once('Db.php');

/**
 * Used theme database keys
 * 
 * total_views                 | int | Total views
 * count_total_views_timestamp | int | Last fully count total views timestamp
 */

/**
 * Theme view statistic system
 */
class View
{
	/**
	 * Update post/page views
	 * 
	 * @param \Widge\Base\Contents $context
	 * 
	 * @return void
	 */
	public static function update(\Widget\Base\Contents $context): void
	{
		// Check is post/page and not index page
		if (!$context->is('single') || $context->is('index')) return;

		// Initialize theme database
		$db = \Blank\Db::get();
		if ($db->total_views === null || time() - $db->count_total_views_timestamp > 604800)
		{
			self::countTotalViews();
		}

		// Parse cookie
		$views = \Typecho\Cookie::get('__blank_views', '*');
		$views = base64_decode($views, true);
		$views = ($views !== false ? explode(',', $views) : []);

		// Check is viewed
		if (!in_array($context->cid, $views))
		{
			// Accumulate views
			$context->incrIntField('views', 1, $context->cid);

			// Update cookie
			$views[] = $context->cid;
			$views = implode(',', $views);
			$views = base64_encode($views);
			\Typecho\Cookie::set('__blank_views', $views, 86400);

			// Update total views
			$db->total_views = $db->total_views + 1;
		}
	}

	/**
	 * Count total views
	 * 
	 * @return void
	 */
	public static function countTotalViews(): void
	{
		// Get view fields
		$db = \Typecho\Db::get();
		$fields = $db->fetchAll(
			$db->select()
				->from('table.fields')
				->where('name = ?', 'views')
		);

		// Count views
		$total = 0;
		foreach ($fields as $i)
		{
			// Check field type, cid & status
			if ($i['int_value'] && $db->fetchRow(
										$db->select()
											->from('table.contents')
											->where('cid = ? AND status = ?', $i['cid'], 'publish')
									))
			{
				$total += $i['int_value'];
			}
		}

		// Update & synchronize theme database
		$db = \Blank\Db::get();
		$db->total_views = $total;
		$db->count_total_views_timestamp = time();
		$db->sync();
	}

	/**
	 * Get total views
	 * 
	 * @return int
	 */
	public static function getTotalViews(): int
	{
		// Initialize theme database
		$db = \Blank\Db::get();
		if ($db->total_views === null || time() - $db->count_total_views_timestamp > 604800)
		{
			self::countTotalViews();
		}

		return $db->total_views;
	}
}

<?php

namespace Blank;

require_once 'ThemeDb.php';

class ThemeView
{
	public static function update($context): void
	{
		if (!$context->is('single') || $context->is('index'))
		{
			return;
		}

		$db = \Blank\ThemeDb::get();
		if ($db->total_views === null || time() - $db->last_statistic_total_views > 604800)
		{
			self::statisticAll();
		}

		$views = \Typecho\Cookie::get('__blank_views');
		if ($views !== null)
		{
			$views = base64_decode($views, true);
			if ($views !== false)
			{
				$views = explode(',', $views);
			}
			else
			{
				$views = [];
			}
		}
		else 
		{
			$views = [];
		}

		if (!in_array($context->cid, $views))
		{
			$context->incrIntField('views', 1, $context->cid);
			$views[] = $context->cid;
			$views = implode(',', $views);
			$views = base64_encode($views);
			\Typecho\Cookie::set('__blank_views', $views, 86400);
			$db->total_views = $db->total_views + 1;
		}
	}

	public static function statisticAll(): void
	{
		$db = \Typecho\Db::get();
		$fields = $db->fetchAll($db->select()->from('table.fields')->where('name = ?', 'views'));
		$total = 0;

		foreach ($fields as $i)
		{
			if ($i['int_value'] && $db->fetchRow($db->select()->from('table.contents')->where('cid = ? AND status = ?', $i['cid'], 'publish')))
			{
				$total += $i['int_value'];
			}
		}

		\Blank\ThemeDb::get()->total_views = $total;
		\Blank\ThemeDb::get()->last_statistic_total_views = time();
	}

	public static function getStatisticAll(): int
	{
		$db = \Blank\ThemeDb::get();
		if ($db->total_views === null || time() - $db->last_statistic_total_views > 604800)
		{
			self::statisticAll();
		}

		return $db->total_views;
	}
}

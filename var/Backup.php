<?php

namespace Blank;

require_once 'Db.php';

/**
 * Theme options backup system
 */
class Backup
{
	/**
	 * Render option panel
	 * 
	 * @return void
	 */
	public static function render(): void
	{
		// Initialize theme database
		$db = \Blank\Db::get();

		// Echo panel html
?>
<div class="bt-banner">
	<h3><?php _e('主题配置备份'); ?></h3>

	<?php if ($db->backup !== null): ?>
	<p><?php _e('上一次备份时间：'); ?><?= date('Y/m/d H:i:s \U\T\CP', $db->backup_timestamp); ?></p>
	<?php else: ?>
	<p><?php _e('无备份'); ?></p>
	<?php endif; ?>

	<form action="?btdo" method="post">
		<input class="bt-btn" name="backup" type="submit" value="<?php _e('备份（覆盖）'); ?>">
		<?php if ($db->backup !== null): ?>
		<input class="bt-btn" name="recover" type="submit" value="<?php _e('从备份恢复'); ?>">
		<input class="bt-btn" name="delete" type="submit" value="<?php _e('删除备份'); ?>">
		<?php endif; ?>
	</form>
</div>
<?php
	}

	/**
	 * Handle backup actions
	 * 
	 * @return void
	 */
	public static function handle(): void
	{
		// Check GET
		if (\Typecho\Request::getInstance()->btdo !== null)
		{
			// Is backup action
			if (isset($_POST['backup']))
			{
				// Get typecho origin theme options
				$db = \Typecho\Db::get();
				$options = $db->fetchAll(
					$db->select()
						->from('table.options')
						->where('name = ?', 'theme:blank')
				);
				$options = $options[0]['value'];

				// Backup options in theme database
				$db = \Blank\Db::get();
				$db->backup = $options;
				$db->backup_timestamp = time();
				$db->sync();
			}
			// Is recover action
			else if (isset($_POST['recover']))
			{
				// Get backup
				$db = \Blank\Db::get();
				$backup = $db->backup;

				// If available
				if ($backup !== null)
				{
					// Cover typecho origin theme options
					$db = \Typecho\Db::get();
					$db->query(
						$db->update('table.options')
							->where('name = ?', 'theme:blank')
							->rows(['value' => $backup])
					);
				}
			}
			// Is delete action
			else if (isset($_POST['delete']))
			{
				$db = \Blank\Db::get();
				unset($db->backup);
				unset($db->backup_timestamp);
				$db->sync();
			}

			// Temporary move
			\Typecho\Response::getInstance()
				->setStatus(302)
            	->setHeader('Location', \Utils\Helper::options()->adminUrl . 'options-theme.php')
            	->respond();
		}
	}
}

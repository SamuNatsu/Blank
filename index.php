<?php
/**
 * A Simple and Pure Theme for Typecho
 * 
 * @package Blank Theme
 * @author SNRainiar
 * @version 0.0.9
 * @link https://rainiar.top
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('header.php');
?>

<?php while ($this->next()): ?>
<article class="border-b-dashed-2 margin-v-2">
	<h1 class="margin-0">
		<a class="animate-u" href="<?php $this->permalink(); ?>"><?php $this->title(); ?></a>
	</h1>
	<div class="meta margin-v-05 no-select">
		<div class="wrapper-meta"><?php $this->category(', ', true, _t('<u>没有分类</u>')); ?></div>
		<div>|</div>
		<div title="<?php $this->date('c'); ?>"><?php $this->date(); ?></div>
		<?php if (!is_null($this->fields->views) && (is_null($this->fields->hide_views) || $this->fields->hide_views == 0)): ?>
        <div>|</div>
        <div><?php _e("浏览量："); ?><?php $this->fields->views(); ?></div>
        <?php endif; ?>
	</div>
	<div class="archive-excerpt margin-v-1"><?php $this->excerpt(); ?></div>
</article>
<?php endwhile; ?>

<?php $this->pageNav(_t('&laquo; 前一页'), _t('后一页 &raquo;'), 1, '...', [ 'wrapTag' => 'div', 'wrapClass' => 'page-nav no-select', 'itemTag' => 'div', 'currentClass' => 'page-nav--cur', 'prevClass' => 'page-nav--prev', 'nextClass' => 'page-nav--next', 'textTag' => '']); ?>
<?php $this->need('footer.php'); ?>

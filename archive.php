<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>

<h1 class="post-title"><?php $this->archiveTitle([
    'category' => _t('分类「%s」下的文章'),
    'search'   => _t('包含关键字「%s」的文章'),
    'tag'      => _t('标签「%s」下的文章'),
    'author'   => _t('「%s」发布的文章')
], '', ''); ?></h1>
<hr>

<?php if ($this->have()): ?>
<?php while ($this->next()): ?>
<article class="archive">
	<h1><a class="animate-u" href="<?php $this->permalink(); ?>"><?php $this->title(); ?></a></h1>
	<div class="meta">
		<div class="wrapper-meta hide-mobile"><?php $this->category(', ', true, _t('<i>没有分类</i>')); ?></div>
		<div class="hide-mobile">|</div>
		<div title="<?php $this->date('c'); ?>"><?php $this->date(); ?></div>
		<?php if (!is_null($this->fields->views) && (is_null($this->fields->hide_views) || $this->fields->hide_views == 0)): ?>
        <div class="hide-mobile">|</div>
        <div class="hide-mobile"><?php _e("浏览量："); ?><?php $this->fields->views(); ?></div>
        <?php endif; ?>
	</div>
	<div class="archive-excerpt"><?php $this->excerpt(); ?></div>
</article>
<?php endwhile; ?>
<?php else: ?>
<h1><?php _e('什么也没找到'); ?></h1>
<?php endif; ?>

<?php $this->pageNav(_t('&laquo; 前一页'), _t('后一页 &raquo;'), 1, '...', [ 'wrapTag' => 'div', 'wrapClass' => 'page-nav', 'itemTag' => 'div', 'currentClass' => 'page-nav--cur', 'prevClass' => 'page-nav--prev', 'nextClass' => 'page-nav--next', 'textTag' => '']); ?>
<?php $this->need('footer.php'); ?>

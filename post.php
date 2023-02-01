<?php if (!defined('__TYPECHO_ROOT_DIR__')) exit; ?>
<?php $this->need('header.php'); ?>

<article>
    <h1 class="post-title margin-0 margin-b-02">
        <a class="animate-u" href="<?php $this->permalink() ?>"><?php $this->title() ?></a>
    </h1>
    <div class="meta">
		<div class="wrapper-meta"><?php $this->category(', ', true, _t('<i>没有分类</i>')); ?></div>
		<div class="hide-mobile">|</div>
        <div title="<?php $this->date('c'); ?>"><?php $this->date(); ?></div>
        <?php if (!is_null($this->fields->views) && (is_null($this->fields->hide_views) || $this->fields->hide_views == 0)): ?>
        <div class="hide-mobile">|</div>
        <div><?php _e("浏览量："); ?><?php $this->fields->views(); ?></div>
        <?php endif; ?>
    </div>
	<div class="meta wrapper-meta"><?php _e('标签：'); ?><?php $this->tags('，', true, _t('没有标签')); ?></div>
    <div class="border-t-solid padding-t-05" id="post-content">
        <?= removeBeforeMore($this->content); ?>
    </div>
    <div class="post-end border-b-dashed-2 margin-t-4 no-select">END</div>
    <?php $cc = is_null($this->fields->cc) ? $this->options->defaultCC : $this->fields->cc; ?>
    <?php if ($cc === 'zero'): ?>
    <div class="post-cc margin-1">
        <a class="animate-u" href="https://creativecommons.org/publicdomain/zero/1.0/" target="_blank">CC0 | <?php _e("公共领域"); ?></a>
    </div>
    <?php elseif ($cc === 'by'): ?>
    <div class="post-cc margin-1">
        <a class="animate-u" href="https://creativecommons.org/licenses/by/4.0/" target="_blank">CC BY 4.0 | <?php _e("署名"); ?></a>
    </div>
    <?php elseif ($cc === 'by-sa'): ?>
    <div class="post-cc margin-1">
        <a class="animate-u" href="https://creativecommons.org/licenses/by-sa/4.0/" target="_blank">CC BY-SA 4.0 | <?php _e("署名-相同方式共享"); ?></a>
    </div>
    <?php elseif ($cc === 'by-nc'): ?>
    <div class="post-cc margin-1">
        <a class="animate-u" href="https://creativecommons.org/licenses/by-nc/4.0/" target="_blank">CC BY-NC 4.0 | <?php _e("署名-非商业使用"); ?></a>
    </div>
    <?php elseif ($cc === 'by-nd'): ?>
    <div class="post-cc margin-1">
        <a class="animate-u" href="https://creativecommons.org/licenses/by-nd/4.0/" target="_blank">CC BY-ND 4.0 | <?php _e("署名-禁止演绎"); ?></a>
    </div>
    <?php elseif ($cc === 'by-nc-sa'): ?>
    <div class="post-cc margin-1">
        <a class="animate-u" href="https://creativecommons.org/licenses/by-nc-sa/4.0/" target="_blank">CC BY-NC-SA 4.0 | <?php _e("署名-非商业使用-相同方式共享"); ?></a>
    </div>
    <?php elseif ($cc === 'by-nc-nd'): ?>
    <div class="post-cc margin-1">
        <a class="animate-u" href="https://creativecommons.org/licenses/by-nc-nd/4.0/" target="_blank">CC BY-NC-ND 4.0 | <?php _e("署名-非商业使用-禁止演绎"); ?></a>
    </div>
    <?php endif; ?>
    <?php unset($cc); ?>
</article>

<?php $this->need('comments.php'); ?>
<?php $this->need('footer.php'); ?>

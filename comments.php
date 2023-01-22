<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

function threadedComments($comments, $options)
{
	$commentBy = $comments->authorId == $comments->ownerId ? 'comment-by-author' : 'comment-by-user';
	$commentLevelClass = $comments->levels > 0 ? 'comment-child' : 'comment-parent';
?>
<li id="li-<?php $comments->theId(); ?>" class="comment-body <?= $commentLevelClass ?> <?= $commentBy ?>">
	<div id="<?php $comments->theId(); ?>">
		<div class="comment-author">
			<div class="comment-gravatar"><?php $comments->gravatar(40); ?></div>
			<div class="comment-name animate-u"><?php $comments->author(); ?></div>
		</div>
		<div class="meta">
			<div title="<?php $comments->date('c'); ?>"><?php $comments->date('Y/m/d H:i:s'); ?></div>
			<div>|</div>
			<div class="comment-reply"><?php $comments->reply(); ?></div>
		</div>
		<div class="comment-content border-l-dashed"><?php $comments->content(); ?></div>
	</div>
	<?php if ($comments->children): ?>
	<div class="comment-children">
		<?php $comments->threadedComments($options); ?>
	</div>
	<?php endif; ?>
</li>
<?php
}
?>

<?php if (is_null($this->fields->hide_comment) || $this->fields->hide_comment != 1): ?>
<div id="comments" class="border-b-dashed-2">
	<?php $this->comments()->to($comments); ?>
	<?php if ($this->allow('comment')): ?>
	<div id="<?php $this->respondId(); ?>" class="border-l-solid margin-v-1 padding-l-1 padding-v-05">
		<div class="cancel-comment-reply margin-b-02"><?php $comments->cancelReply(); ?></div>
		<h3 class="margin-0 margin-b-1"><?php _e('留下一条评论'); ?></h3>
		<form action="<?php $this->commentUrl() ?>" id="comment-form" method="post">
			<?php if ($this->user->hasLogin()): ?>
			<p><?php _e('登录身份: '); ?><a class="animate-u" href="<?php $this->options->profileUrl(); ?>" target="_blank"><?php $this->user->screenName(); ?></a> | <a class="animate-u" href="<?php $this->options->logoutUrl(); ?>" style="color:red"><?php _e('退出'); ?> &raquo;</a>
			</p>
			<?php else: ?>
			<input type="text" name="author" value="<?php $this->remember('author'); ?>" placeholder="<?php _e('称呼（必需）'); ?>" required>
			<br>
			<input type="email" name="mail" value="<?php $this->remember('mail'); ?>" placeholder="<?php _e('Email'); ?><?php if ($this->options->commentsRequireMail) _e('（必需）'); ?>"<?php if ($this->options->commentsRequireMail): ?> required<?php endif; ?>>
			<br>
			<input type="url" name="url" value="<?php $this->remember('url'); ?>" placeholder="<?php _e('网站'); ?><?php if ($this->options->commentsRequireURL) _e('（必需）'); ?>"<?php if ($this->options->commentsRequireURL): ?> required<?php endif; ?> />
			<br>
			<?php endif; ?>
			<textarea id="OwO-area" rows="8" cols="50" name="text" placeholder="<?php _e('评论'); ?>" required><?php $this->remember('text'); ?></textarea>
			<div class="OwO"></div>
			<button type="submit"><?php _e('提交评论'); ?></button>
		</form>
	</div>
	<?php else: ?>
	<h3><?php _e('评论已关闭'); ?></h3>
	<hr>
	<?php endif; ?>
    <h3><?php $this->commentsNum(_t('暂无评论'), _t('仅有一条评论'), _t('共 %d 条评论')); ?></h3>
    <?php $comments->listComments(); ?>
    <?php $comments->pageNav('&laquo; 前一页', '后一页 &raquo;', 1, '...', [ 'wrapTag' => 'div', 'wrapClass' => 'page-nav', 'itemTag' => 'div', 'currentClass' => 'page-nav--cur', 'prevClass' => 'page-nav--prev', 'nextClass' => 'page-nav--next', 'textTag' => '']); ?>
	<?php unset($comments); ?>
</div>
<?php endif; ?>

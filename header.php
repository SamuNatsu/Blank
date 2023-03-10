<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

if ($this->options->isMaintaining === 'on' && !$this->user->pass('administrator', true))
{
	$this->response->setStatus(501);
	$this->need('501.php');
	exit;
}

if ($this->options->gravatarMirror && !defined('__TYPECHO_GRAVATAR_PREFIX__'))
{
	define('__TYPECHO_GRAVATAR_PREFIX__', $this->options->gravatarMirror);
}

\Blank\View::update($this);
minifyBegin($this->options);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="<?php $this->options->charset(); ?>">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<title><?php $this->archiveTitle([
        'category' => _t('分类「%s」下的文章'),
        'search'   => _t('包含关键字「%s」的文章'),
        'tag'      => _t('标签「%s」下的文章'),
        'author'   => _t('「%s」发布的文章')
    ], '', ' | '); ?><?php $this->options->title(); ?></title>

	<?php if ($this->options->faviconUrl): ?>
	<link rel="icon" href="<?php $this->options->faviconUrl(); ?>">
	<?php endif; ?>

	<?php if ($this->options->modernNormalize): ?>
	<link rel="stylesheet" href="<?php $this->options->modernNormalize(); ?>">
	<?php else: ?>
	<link rel="stylesheet" href="<?php $this->options->themeUrl('css/modern-normalize.min.css'); ?>">
	<?php endif; ?>

	<link rel="stylesheet" href="<?php $this->options->themeUrl('css/atom-one-dark.min.css'); ?>">
	<link rel="stylesheet" href="<?php $this->options->themeUrl('css/highlightjs-line-numbers.min.css'); ?>">

	<?php if ($this->options->katexCss): ?>
	<link rel="stylesheet" href="<?php $this->options->katexCss(); ?>">
	<?php else: ?>
	<link rel="stylesheet" href="<?php $this->options->themeUrl('css/katex.min.css'); ?>">
	<?php endif; ?>

	<link rel="stylesheet" href="<?php $this->options->themeUrl('css/OwO.min.css'); ?>">
	<link rel="stylesheet" href="<?php $this->options->themeUrl('css/common.css'); ?>">
	<link rel="stylesheet" href="<?php $this->options->themeUrl('css/style.css?v=0.1.2'); ?>">

	<?php if ($this->options->pjaxService): ?>
	<script defer src="<?php $this->options->pjaxService(); ?>"></script>
	<?php else: ?>
	<script defer src="<?php $this->options->themeUrl('js/pjax.min.js'); ?>"></script>
	<?php endif; ?>

	<?php if ($this->options->hljsScript): ?>
	<script defer src="<?php $this->options->hljsScript(); ?>"></script>
	<?php else: ?>
	<script defer src="<?php $this->options->themeUrl('js/highlight.min.js'); ?>"></script>
	<?php endif; ?>

	<?php if ($this->options->hljsNumber): ?>
	<script defer src="<?php $this->options->hljsNumber(); ?>"></script>
	<?php else: ?>
	<script defer src="<?php $this->options->themeUrl('js/highlightjs-line-numbers.min.js'); ?>"></script>
	<?php endif; ?>

	<?php if ($this->options->katexScript): ?>
	<script defer src="<?php $this->options->katexScript(); ?>"></script>
	<?php else: ?>
	<script defer src="<?php $this->options->themeUrl('js/katex.min.js'); ?>"></script>
	<?php endif; ?>

	<?php if ($this->options->katexAuto): ?>
	<script defer src="<?php $this->options->katexAuto(); ?>"></script>
	<?php else: ?>
	<script defer src="<?php $this->options->themeUrl('js/auto-render.min.js'); ?>"></script>
	<?php endif; ?>

	<script defer src="<?php $this->options->themeUrl('js/OwO.min.js'); ?>"></script>
	<script src="<?php $this->options->themeUrl('js/style.js?v=0.1.1'); ?>"></script>

	<style>
		@font-face {
			font-family: 'smiley-sans';
			src: url(<?php $this->options->themeUrl('font/smiley-sans.woff2'); ?>);
		}
	</style>

	<script> let siteUrl = "<?php $this->options->siteUrl(); ?>"; </script>
	<script> let blankThemeUrl = "<?php $this->options->themeUrl(); ?>"; </script>

	<?php $this->options->customHead(); ?>

	<?php $this->header(); ?>
</head>

<body>
	<div class="loading-frame">
		<div class="loading-text">Loading</div>
		<div class="loading-spin" style="--spin-delay:0;--spin-time:1s;--spin-size:5rem"></div>
		<div class="loading-spin" style="--spin-delay:.2s;--spin-time:2s;--spin-size:6rem"></div>
		<div class="loading-spin" style="--spin-delay:.5s;--spin-time:3s;--spin-size:7rem"></div>
	</div>

	<?php if ($this->options->isMaintaining === 'on'): ?>
	<div class="maintain"><?php _e('维护模式已开启，请进入'); ?><a href="<?php $this->options->adminUrl('options-theme.php'); ?>" target="_blank"><?php _e('设置外观'); ?></a><?php _e('关闭'); ?></div>
	<?php endif; ?>

	<div class="search-layer">
		<div class="search-bg"></div>
		<form action="<?php $this->options->siteUrl(); ?>" class="search-bar" method="post">
			<input type="text" name="s" placeholder="<?php _e('搜点什么'); ?>">
			<button type="submit"><img src="<?php $this->options->themeUrl('svg/search.svg'); ?>"></button>
		</form>
	</div>

	<header>
		<h1 id="site-title"><a href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title(); ?></a></h1>
		<h2 id="site-dscrp"><?php $this->options->description(); ?></h2>
		<nav class="flex-row flex-x-center">
			<div class="mobile-btn-nav-close"><img src="<?php $this->options->themeUrl('svg/top.svg'); ?>"></div>
			<div><a class="animate-u" href="<?php $this->options->siteUrl(); ?>"><?php _e('首页'); ?></a></div>
			<?php \Widget\Contents\Page\Rows::alloc()->to($pages); ?>
			<?php while ($pages->next()): ?>
			<div <?php if ($this->is('page', $pages->slug)): ?> style="color:red" <?php endif; ?>><a class="animate-u" href="<?php $pages->permalink(); ?>"><?php $pages->title(); ?></a></div>
			<?php endwhile; ?>
			<?php unset($pages); ?>
			<div id="search-btn" title="<?php _e('搜索'); ?>"><img src="<?php $this->options->themeUrl('svg/search.svg'); ?>"></div>
		</nav>
	</header>

	<div class="flex-row flex-c-center width-100">
		<aside class="flex-col flex-x-center">
			<div class="mobile-btn-aside-close"><img src="<?php $this->options->themeUrl('svg/top.svg'); ?>"></div>
			<div id="gravatar"><a href="<?php $this->options->adminUrl('login.php'); ?>" target="_blank">
				<?php if ($this->options->customGravatar): ?>
				<img src="<?php $this->options->customGravatar(); ?>" alt="<?php $this->options->gravatarName(); ?>" width="150" height="150">
				<?php else: ?>
				<img src="<?php echo \Typecho\Common::gravatarUrl($this->options->gravatarMail, 150, 'X', $this->request->isSecure()); ?>" width="150" height="150">
				<?php endif; ?>
			</a></div>
			<h2 id="author"><?php $this->options->gravatarName(); ?></h2>
			<div id="statistic">
				<?php $stat = \Widget\Stat::alloc(); ?>
				<div>
					<h4><?php _e('文章'); ?></h4>
					<div><?php $stat->publishedPostsNum(); ?></div>
				</div>
				<div>
					<h4><?php _e('评论'); ?></h4>
					<div><?php $stat->publishedCommentsNum(); ?></div>
				</div>
				<div>
					<h4><?php _e('阅读'); ?></h4>
					<div id="views"><?= \Blank\View::getTotalViews(); ?></div>
				</div>
				<?php unset($stat); ?>
			</div>
			<div id="contacts" class="no-select">
				<?php if ($this->options->biliUrl): ?>
				<div><a href="<?php $this->options->biliUrl(); ?>" target="_blank" title="<?php _e('哔哩哔哩'); ?>"><img src="<?php $this->options->themeUrl('svg/bili.svg'); ?>"></a></div>
				<?php endif; ?>
				<?php if ($this->options->giteeUrl): ?>
				<div><a href="<?php $this->options->giteeUrl(); ?>" target="_blank" title="<?php _e('码云'); ?>"><img src="<?php $this->options->themeUrl('svg/gitee.svg'); ?>"></a></div>
				<?php endif; ?>
				<?php if ($this->options->githubUrl): ?>
				<div><a href="<?php $this->options->githubUrl(); ?>" target="_blank" title="Github"><img src="<?php $this->options->themeUrl('svg/github.svg'); ?>"></a></div>
				<?php endif; ?>
				<?php if ($this->options->mailUrl): ?>
				<div><a href="mailto:<?php $this->options->mailUrl(); ?>" target="_blank" title="<?php _e('邮箱'); ?>"><img src="<?php $this->options->themeUrl('svg/mail.svg'); ?>"></a></div>
				<?php endif; ?>
				<?php if ($this->options->telegramUrl): ?>
				<div><a href="<?php $this->options->telegramUrl(); ?>" target="_blank" title="<?php _e('电报'); ?>"><img src="<?php $this->options->themeUrl('svg/telegram.svg'); ?>"></a></div>
				<?php endif; ?>
				<?php if ($this->options->twitterUrl): ?>
				<div><a href="<?php $this->options->twitterUrl(); ?>" target="_blank" title="<?php _e('推特'); ?>"><img src="<?php $this->options->themeUrl('svg/twitter.svg'); ?>"></a></div>
				<?php endif; ?>
				<?php if ($this->options->weiboUrl): ?>
				<div><a href="<?php $this->options->weiboUrl(); ?>" target="_blank" title="<?php _e('微博'); ?>"><img src="<?php $this->options->themeUrl('svg/weibo.svg'); ?>"></a></div>
				<?php endif; ?>
			</div>
		</aside>
		<div id="center" class="border-t-dashed-5 padding-1 width-50">

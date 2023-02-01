<?php

if (!defined('__TYPECHO_ROOT_DIR__')) exit;

require_once('var/TinyHtmlMinifier.php');

require_once('var/Backup.php');
require_once('var/Update.php');
require_once('var/View.php');

// Auto update
\Blank\Update::autoUpdate();

/**
 * Theme config
 * 
 * @param \Typecho\Widget\Helper\Form $form
 * 
 * @return void
 */
function themeConfig(\Typecho\Widget\Helper\Form $form): void
{
	// Go to top button html
	$btn_top = '<a href="#bt-a-top" title="' . _t('回到顶部') . '"><img class="bt-btn-top" src="' . \Utils\Helper::options()->themeUrl . '/svg/top.svg"></a>';

	// Backup action handle
	\Blank\Backup::handle();

	// Render header
?>
<link rel="stylesheet" href="<?php \Utils\Helper::options()->themeUrl('css/options-theme.css?v=0.1.0'); ?>">
<h2 class="bt-title" id="bt-a-top"><?php _e('Blank 主题配置'); ?></h2>

<?php \Blank\Update::updatedBanner(); ?>

<div class="bt-banner bt-banner-info">
	<h3><?php _e('快速跳转'); ?></h3>
	<p>
		<a href="#bt-a-1"><?php _e('全局'); ?></a>，
		<a href="#bt-a-2"><?php _e('镜像'); ?></a>，
		<a href="#bt-a-3"><?php _e('侧边栏'); ?></a>，
		<a href="#bt-a-4"><?php _e('页脚'); ?></a>，
		<a href="#bt-a-5"><?php _e('自定义'); ?></a>
	</p>
</div>

<?php \Blank\Backup::render(); ?>

<h3 class="bt-sub-title" id="bt-a-1"><?php _e('全局'); ?><?= $btn_top; ?></h3>
<?php
	// Form items
	$isMaintaining = new \Typecho\Widget\Helper\Form\Element\Radio(
		'isMaintaining',
		[
			'on'	=> _t('开启'),
			'off'	=> _t('关闭')
		],
		'off',
		_t('维护模式'),
		_t('启动维护模式后，只有登录的管理员才能进入站点')
	);
	$form->addInput($isMaintaining);

	$isMinified = new \Typecho\Widget\Helper\Form\Element\Radio(
		'isMinified',
		[
			'on'	=> _t('开启'),
			'off'	=> _t('关闭')
		],
		'off',
		_t('HTML 压缩'),
		_t('启动 HTML 压缩以减少服务器传输压力<br>注 1：HTML 压缩只会作用于前台<br>注 2：使用 URL 引用的文件不会参与压缩，请使用对应的 min 版本<br>注 3：已知在前台 HTML 中出现非标签的左/右尖括号时，会导致标签错误地提前开始/终止，这是压缩后端的一个 bug，未来可能会修复，取决于后端的原作者，或我们在未来开发自己的压缩后端代替')
	);
	$form->addInput($isMinified);

	$defaultCC = new \Typecho\Widget\Helper\Form\Element\Select(
		'defaultCC',
		[
			'zero'		=> _t('CC0 公共领域'),
			'by'		=> _t('BY 署名'),
			'by-sa'		=> _t('BY-SA 署名-相同方式共享'),
			'by-nc'		=> _t('BY-NC 署名-非商业使用'),
			'by-nd' 	=> _t('BY-ND 署名-禁止演绎'),
			'by-nc-sa'	=> _t('BY-NC-SA 署名-非商业使用-相同方式共享'),
			'by-nc-nd'	=> _t('BY-NC-ND 署名-非商业使用-禁止演绎')
		],
		'by-sa',
		_t('默认知识共享许可协议'),
		_t('推荐且默认使用 BY-SA 署名-相同方式共享<br>您可以在 <a href="https://creativecommons.org/licenses/" target="_blank">https://creativecommons.org/licenses/</a> 查看各种协议的规定<br>如果您想为某一篇文章或页面指定特定的知识共享许可协议，请在该文章或页面中添加自定义字段“cc”，并填写如下对应的协议字符串（不包括引号）：<br>&emsp;“zero”：CC0 公共领域<br>&emsp;“by”：BY 署名<br>&emsp;“by-sa”：BY-SA 署名-相同方式共享<br>&emsp;“by-nc”：BY-NC 署名-非商业使用<br>&emsp;“by-nd”：BY-ND 署名-禁止演绎<br>&emsp;“by-nc-sa”：BY-NC-SA 署名-非商业使用-相同方式共享<br>&emsp;“by-nc-nd”：BY-NC-ND 署名-非商业使用-禁止演绎<br>若“cc”字段无法匹配上面的协议字符串，则该文章或页面不会显示知识共享许可协议')
	);
	$form->addInput($defaultCC);

	$faviconUrl = new \Typecho\Widget\Helper\Form\Element\Text(
		'faviconUrl',
		null,
		null,
		_t('网站图标链接'),
		'<hr><h3 class="bt-sub-title" id="bt-a-2">' . _t('镜像') . $btn_top . '</h3>'
	);
	$form->addInput($faviconUrl);

	$_gravatarMirrorWarning = '';
	if (defined('__TYPECHO_GRAVATAR_PREFIX__'))
	{
		$_gravatarMirrorWarning = _t('<br><span style="color:red">警告：检测到 PHP 常量 __TYPECHO_GRAVATAR_PREFIX__ 已被定义为 "') . __TYPECHO_GRAVATAR_PREFIX__ . _t('"，导致镜像替换无法进行，请注释或删除相关代码</span>');
	}
	$gravatarMirror = new \Typecho\Widget\Helper\Form\Element\Text(
		'gravatarMirror',
		null,
		'https://gravatar.loli.net/avatar/',
		_t('Gravatar 镜像'),
		_t('推荐且默认使用 https://gravatar.loli.net/avatar/，当然你可以换成其他 Gravatar 镜像') . $_gravatarMirrorWarning
	);
	$form->addInput($gravatarMirror);

	$modernNormalize = new \Typecho\Widget\Helper\Form\Element\Text(
		'modernNormalize',
		null,
		null,
		_t('Modern Normalize 镜像'),
		_t('留空则使用主题自带的 modern-normalize.min.css（v1.1.0）<br>您可以在 <a href="https://github.com/sindresorhus/modern-normalize" target="_blank">https://github.com/sindresorhus/modern-normalize</a> 查看该样式表详情')
	);
	$form->addInput($modernNormalize);

	$pjaxService = new \Typecho\Widget\Helper\Form\Element\Text(
		'pjaxService',
		null,
		null,
		_t('Pjax 镜像'),
		_t('留空则使用主题自带的 pjax.min.js（v0.2.8）<br>注：该 Pjax 不是 jQuery 插件，是独立模块')
	);
	$form->addInput($pjaxService);

	$hljsScript = new \Typecho\Widget\Helper\Form\Element\Text(
		'hljsScript',
		null,
		null,
		_t('Highlight.js 镜像'),
		_t('留空则使用主题自带的 highlight.min.js（v11.7.0）')
	);
	$form->addInput($hljsScript);

	$hljsNumber = new \Typecho\Widget\Helper\Form\Element\Text(
		'hljsNumber',
		null,
		null,
		_t('Highlight.js 行号插件镜像'),
		_t('留空则使用主题自带的 highlightjs-line-numbers.min.js（v2.8.0）<br>您可以在 <a href="https://github.com/wcoder/highlightjs-line-numbers.js" target="_blank">https://github.com/wcoder/highlightjs-line-numbers.js</a> 查看该插件的相关详情')
	);
	$form->addInput($hljsNumber);

	$katexCss = new \Typecho\Widget\Helper\Form\Element\Text(
		'katexCss',
		null,
		null,
		_t('Katex 样式表镜像'),
		_t('留空则使用主题自带的 katex.min.css（v0.16.4）')
	);
	$form->addInput($katexCss);

	$katexScript = new \Typecho\Widget\Helper\Form\Element\Text(
		'katexScript',
		null,
		null,
		_t('Katex 脚本镜像'),
		_t('留空则使用主题自带的 katex.min.js（v0.16.4）')
	);
	$form->addInput($katexScript);

	$katexAuto = new \Typecho\Widget\Helper\Form\Element\Text(
		'katexAuto',
		null,
		null,
		_t('Katex 自动渲染脚本镜像'),
		_t('留空则使用主题自带的 auto-render.min.js（v0.16.4）') . '<hr><h3 class="bt-sub-title" id="bt-a-3">' . _t('侧边栏') . $btn_top . '</h3>'
	);
	$form->addInput($katexAuto);

	$customGravatar = new \Typecho\Widget\Helper\Form\Element\Text(
		'customGravatar',
		null,
		null,
		_t('头像图片链接'),
		_t('留空则使用 Typecho 默认定义的头像链接（邮箱 + Gravatar 服务）')
	);
	$form->addInput($customGravatar);

	$gravatarMail = new \Typecho\Widget\Helper\Form\Element\Text(
		'gravatarMail',
		null,
		null,
		_t('Gravatar 邮箱'),
		_t('由于 Typecho 是多用户系统，而 Blank 主题是单用户主题，所以调用 Typecho 自带的 Gravatar 生成会有指向不明确的问题，因此请手动设置')
	);
	$form->addInput($gravatarMail);

	$gravatarName = new \Typecho\Widget\Helper\Form\Element\Text(
		'gravatarName',
		null,
		null,
		_t('博主名称'),
		_t('由于 Typecho 是多用户系统，而 Blank 主题是单用户主题，所以调用 Typecho 自带的显示名称会有指向不明确的问题，因此请手动设置')
	);
	$form->addInput($gravatarName);

	$biliUrl = new \Typecho\Widget\Helper\Form\Element\Text(
		'biliUrl',
		null,
		null,
		_t('哔哩哔哩主页链接')
	);
	$form->addInput($biliUrl);

	$giteeUrl = new \Typecho\Widget\Helper\Form\Element\Text(
		'giteeUrl',
		null,
		null,
		_t('码云主页链接')
	);
	$form->addInput($giteeUrl);

	$githubUrl = new \Typecho\Widget\Helper\Form\Element\Text(
		'githubUrl',
		null,
		null,
		_t('Github 主页链接')
	);
	$form->addInput($githubUrl);

	$mailUrl = new \Typecho\Widget\Helper\Form\Element\Text(
		'mailUrl',
		null,
		null,
		_t('Mailto 邮箱')
	);
	$form->addInput($mailUrl);

	$telegramUrl = new \Typecho\Widget\Helper\Form\Element\Text(
		'telegramUrl',
		null,
		null,
		_t('电报主页链接')
	);
	$form->addInput($telegramUrl);

	$twitterUrl = new \Typecho\Widget\Helper\Form\Element\Text(
		'twitterUrl',
		null,
		null,
		_t('推特主页链接')
	);
	$form->addInput($twitterUrl);

	$weiboUrl = new \Typecho\Widget\Helper\Form\Element\Text(
		'weiboUrl',
		null,
		null,
		_t('微博主页链接'),
		'<hr><h3 class="bt-sub-title" id="bt-a-4">' . _t('页脚') . $btn_top . '</h3>'
	);
	$form->addInput($weiboUrl);

	$icp = new \Typecho\Widget\Helper\Form\Element\Text(
		'icp',
		null,
		null,
		_t('ICP 备案信息'),
		'<hr><h3 class="bt-sub-title" id="bt-a-5">' . _t('自定义') . $btn_top . '</h3>'
	);
	$form->addInput($icp);

	$customHead = new \Typecho\Widget\Helper\Form\Element\Textarea(
		'customHead',
		null,
		'',
		_t('头部标签附加代码'),
		_t('在 &lt;head&gt;&lt;/head&gt; 标签中追加代码')
	);
	$form->addInput($customHead);

	$customFooter = new \Typecho\Widget\Helper\Form\Element\Textarea(
		'customFooter',
		null,
		'',
		_t('页脚附加代码'),
		_t('在 &lt;footer&gt;&lt;/footer&gt; 标签中追加代码')
	);
	$form->addInput($customFooter);
}

function minifyBegin($options): void
{
	ob_start('ob_gzhandler');
	if ($options->isMinified === 'on')
	{
		ob_start();
	}
}

function minifyEnd($options): void
{
	if ($options->isMinified === 'on')
	{
		$html = ob_get_clean();
		$minifier = new \Minifier\TinyHtmlMinifier([]);
		$html = $minifier->minify($html) . '<!-- Minified -->';
		echo $html;
	}
	ob_end_flush();
}

function removeBeforeMore(string $contents): string
{
	$matches = [];
	if (preg_match('/<!--more-->/', $contents, $matches, PREG_OFFSET_CAPTURE) === 1)
	{
		return substr($contents, $matches[0][1] + 11);
	}
	return $contents;
}

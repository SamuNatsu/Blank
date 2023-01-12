		</div>
	</div>
	<footer class="flex-col flex-x-center margin-2">
		<div>© <?= date('Y') ?> <a class="animate-u" href="<?php $this->options->siteUrl(); ?>"><?php $this->options->title(); ?></a></div>
		<div>Powered by <a class="animate-u" href="https://typecho.org" target="_blank">Typecho</a> | Theme <a class="animate-u" href="https://github.com/SamuNatsu/Blank" target="_blank">Blank</a></div>
		<?php if ($this->options->icp): ?>
		<div><a class="animate-u" href="https://beian.miit.gov.cn/" target="_blank"><?php $this->options->icp(); ?></a></div>
		<?php endif; ?>
		<?php $this->options->customFooter(); ?>
	</footer>
</body>
</html>

<?php minifyEnd($this->options); ?>

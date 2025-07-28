<form action="#" id="searchform" class="searchform" role="search"
	aria-label="<?= esc_attr_e('Search Form', 'easyline') ?>">
	<button type="submit" aria-label="<?= esc_attr_e('Submit search', 'easyline') ?>">
		<span style="font-size: 0px;"><?= esc_attr_e('Submit search', 'easyline') ?></span>
		<img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/search.svg" alt="search" />
	</button>
	<input type="text" id="s" name="s" placeholder="<?= esc_attr_e('Search...', 'easyline') ?>" aria-label="<?= esc_attr_e('Search input', 'easyline') ?>" />
</form>
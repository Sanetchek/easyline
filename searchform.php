<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" id="searchform" class="searchform" role="search"
	aria-label="<?= esc_attr__( 'Search Form', 'easyline' ) ?>">
	<button type="submit" aria-label="<?= esc_attr__( 'Submit search', 'easyline' ) ?>">
		<span style="font-size: 0px;"><?= esc_html__( 'Submit search', 'easyline' ) ?></span>
		<img src="<?php echo esc_url(get_template_directory_uri()); ?>/img/search.svg" alt="search" />
	</button>
	<input type="text" id="s" name="s" placeholder="<?= esc_attr__( 'Search...', 'easyline' ) ?>" aria-label="<?= esc_attr__( 'Search input', 'easyline' ) ?>" />
</form>

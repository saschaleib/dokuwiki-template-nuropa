<?php // must be run from within DokuWiki

	if (!defined('DOKU_INC')) die();

?>	<header>
		<div id="searchbar__layout">
			<div class="content">
				<div id="site__logo">
<?php my_sitelogo(str_repeat(TPL_TAB,5)); ?>
				</div>
				<div id="search__group">
					<div id="search__backdrop">
						<?php my_searchform(true, false); ?>

					</div>
				</div>
				<div id="sitetools__group">
					<ul>
						<li class="search"><label for="qsearch__in" title="<?php echo $lang['btn_search']; ?>" tabindex="-1">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M9.5,3A6.5,6.5 0 0,1 16,9.5C16,11.11 15.41,12.59 14.44,13.73L14.71,14H15.5L20.5,19L19,20.5L14,15.5V14.71L13.73,14.44C12.59,15.41 11.11,16 9.5,16A6.5,6.5 0 0,1 3,9.5A6.5,6.5 0 0,1 9.5,3M9.5,5C7,5 5,7 5,9.5C5,12 7,14 9.5,14C12,14 14,12 14,9.5C14,7 12,5 9.5,5Z" /></svg>
							<span><?php echo $lang['btn_search']; ?></span></label></li>
						<li class="pre"><?php tpl_includeFile('sitetools-pre.html'); ?></li>
						<li class="main">
<?php
							$sbopt = tpl_getConf('searchbaropt');
							if ($sbopt == 'user') {
								my_userinfo(str_repeat(TPL_TAB,7), 'sb__userinfo');
							} else if ($sbopt == 'langs') {
								my_langmenu(str_repeat(TPL_TAB,6), 'sb__languages');
							}
 ?>						</li>
						<li class="post"><?php tpl_includeFile('sitetools-post.html'); ?></li>
					</ul>
				</div>
			</div>
		</div>
<?php
		// rundown of the other header elements:
		my_sitemenu(str_repeat(TPL_TAB,2),'before');
		my_pagetitle(str_repeat(TPL_TAB,2),
			my_youarehere(str_repeat(TPL_TAB,4),'menu')
		);
		my_sitemenu(str_repeat(TPL_TAB,2),'between');
		my_banner(str_repeat(TPL_TAB,2),
			my_youarehere(str_repeat(TPL_TAB,4),'banner')
		);
		my_sitemenu(str_repeat(TPL_TAB,2),'after');
?>
	</header>

<?php // must be run from within DokuWiki

	if (!defined('DOKU_INC')) die();

?>	<header>
		<div id="searchbar__layout">
			<div class="content">
				<div id="site__logo">
<?php my_sitelogo(str_repeat(TPL_TAB,5)); ?>
				</div>
				<div id="search__group">
					<div id="search__box">
						<?php my_searchform(true, false); ?>

					</div>
				</div>
				<div id="sitetools__group">
					<!-- BEGIN SITETOOLS BLOCK -->
					<ul>
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
					<!-- END SITETOOLS BLOCK -->
				</div>
			</div>
		</div>
		<div id="menu-layout">
			<div id="menu-block">
				<nav id="menubar-breadcrumbs">
					<!-- - - - - - - - - MENUBAR BREADCRUMBS - - - - - - - -->
<?php
                            //tpl_flush();
                            my_youarehere(str_repeat(TPL_TAB,6), 'header');
                            my_breadcrumbs(str_repeat(TPL_TAB,6), 'header');
?>					<!-- - - - - - - - - END OF MENUBAR BREADCRUMBS  - - - - - - - -->
				</nav>
				<div id="page-headline-layout">
<?php						my_pagetitle(str_repeat(TPL_TAB,5));
?>					</div>
				<div id="horizontal-menu">
					<!-- - - - - - - - - MENU BAR - - - - - - - -->
					<?php
						tpl_flush();
						tpl_include_page('menu', true, true); ?>

					<!-- - - - - - - - - END OF MENU BAR - - - - - - - -->
				</div>
			</div>
		</div>
		<div id="post-header-layout" style="<?php my_banner_style(); ?>">
			<div id="post-header">
				<div id="post-header-breadcrumbs">
					<nav class="breadcrumbs-nav">
						<!-- - - - - - - - - BREADCRUMBS CONTENT - - - - - - - -->
<?php
							//tpl_flush();
							my_youarehere(str_repeat(TPL_TAB,7),'banner');
							my_breadcrumbs(str_repeat(TPL_TAB,7),'banner');

?>						<!-- - - - - - - - - END OF BREADCRUMBS CONTENT  - - - - - - - -->
					</nav>
				</div>
			</div>
		</div>
	</header>

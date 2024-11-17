<?php // must be run from within DokuWiki

	if (!defined('DOKU_INC')) die();

?>		<header>
			<div id="searchbar__layout">
				<div class="content">
					<div id="site__logo"><?php my_sitelogo(); ?></div>
					<div id="search__group"><?php tpl_searchform(true, false); ?></div>
					<div id="sitetools__group">
						<!-- BEGIN SITETOOLS BLOCK -->
						<div id="user__menu__group">
<?php
							if (tpl_getConf('userinfo') == 'header') {
								my_userinfo(str_repeat(DOKU_TAB,7));
							} else {
								tpl_includeFile('userinfo.html');
							}
 ?>						</div>
						<div id="lang__menu__group">
<?php
								my_langmenu(str_repeat(DOKU_TAB,6));

?>						</div>
						<!-- END SITETOOLS BLOCK -->
					</div>
				</div>
			</div>
			<div id="menu-layout">
				<div id="menu-block">
					<nav id="menubar-breadcrumbs">
						<!-- - - - - - - - - MENUBAR BREADCRUMBS - - - - - - - -->
<?php
                            tpl_flush();
                            my_youarehere(str_repeat(DOKU_TAB,6), 'header');
                            my_breadcrumbs(str_repeat(DOKU_TAB,6), 'header');
?>							<!-- - - - - - - - - END OF MENUBAR BREADCRUMBS  - - - - - - - -->
					</nav>
					<div id="page-headline-layout">
<?php						my_pagetitle(str_repeat(DOKU_TAB,5));
?>						</div>
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
								tpl_flush();
								my_youarehere(str_repeat(DOKU_TAB,7),'banner');
								my_breadcrumbs(str_repeat(DOKU_TAB,7),'banner');
?>								<!-- - - - - - - - - END OF BREADCRUMBS CONTENT  - - - - - - - -->
						</nav>
					</div>
				</div>
			</div>
		</header>

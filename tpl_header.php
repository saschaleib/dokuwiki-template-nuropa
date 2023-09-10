<?php // must be run from within DokuWiki

	if (!defined('DOKU_INC')) die();

?>			<header>
				<div id="searchbar-layout">
					<div id="searchbar">
						<div id="site-logo"><?php my_sitelogo(); ?></div>
						<div id="search-group"><?php tpl_searchform(true, false); ?></div>
						<div id="user-group">
							<!-- BEGIN USER/SITEINFO BLOCK -->
<?php

    if (tpl_getConf('userinfo') == 'header') {
        my_userinfo(str_repeat(chr(9),8));
    } else {
        tpl_includeFile('siteinfo.html');
    }

 ?>
							<!-- END USER/SITEINFO BLOCK -->
						</div>
						<!-- LANGUAGES MENU PLACEHOLDER -->
					</div>
				</div>
				<div id="menu-layout">
					<div id="menu-block">
						<nav id="menubar-breadcrumbs">
							<!-- - - - - - - - - MENUBAR BREADCRUMBS - - - - - - - -->
<?php
                            tpl_flush();
                            my_youarehere(str_repeat(chr(9),7), 'header');
                            my_breadcrumbs(str_repeat(chr(9),7), 'header');
?>							<!-- - - - - - - - - END OF MENUBAR BREADCRUMBS  - - - - - - - -->
						</nav>
						<div id="page-headline-layout">
<?php						my_pagetitle(str_repeat(chr(9),6));
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
								my_youarehere(str_repeat(chr(9),8),'banner');
								my_breadcrumbs(str_repeat(chr(9),8),'banner');
?>								<!-- - - - - - - - - END OF BREADCRUMBS CONTENT  - - - - - - - -->
							</nav>
						</div>
					</div>
				</div>
			</header>

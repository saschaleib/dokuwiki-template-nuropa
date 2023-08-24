		<header>
			<div id="searchbar-layout">
				<div id="searchbar">
					<div id="site-logo"><?php my_sitelogo(); ?></div>
					<div id="search-group"><?php tpl_searchform(true, false); ?></div>
					<div id="user-group">
<?php

    if (tpl_getConf('userinfo') == 'header') {
        my_userinfo(str_repeat(chr(9),7));
    } else {
        tpl_includeFile('siteinfo.html');
    }

 ?>
					</div>
					<!-- languages menu placeholder -->
				</div>
			</div>
			<div id="menu-layout">
			    <div id="menu-block">
                    <nav id="menubar-breadcrumbs">
                        <!-- - - - - - - - - BREADCRUMBS CONTENT - - - - - - - -->
                        <?php
                            tpl_flush();
                            my_youarehere(str_repeat(chr(9),5), 'menu');
                            my_breadcrumbs(str_repeat(chr(9),5), 'menu');
                        ?>				</nav>
                        <!-- - - - - - - - - END OF BREADCRUMBS CONTENT  - - - - - - - -->
                    </nav>
                    <div id="page-headline-layout">
    <?php					tpl_includeFile('title.html'); ?>
                    </div>
                    <div id="horizontal-menu">
                        <!-- - - - - - - - - MENU INCLUDE - - - - - - - -->
                        <?php
                            tpl_flush();
                            tpl_include_page('menu', true, true);
                        ?>
                    <!-- - - - - - - - - END OF MENU  - - - - - - - -->
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
							//my_youarehere(str_repeat(chr(9),4),'banner');
							//my_breadcrumbs(str_repeat(chr(9),4),'banner');
						?>				</nav>
						<!-- - - - - - - - - END OF BREADCRUMBS CONTENT  - - - - - - - -->
						</nav>
					</div>
				</div>
			</div>
		</header>

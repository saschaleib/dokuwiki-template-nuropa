		<header><!-- begin header -->
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
			<div id="header-layout">
				<div id="header-breadcrumbs">
                    <nav class="breadcrumbs-nav">
                    <!-- - - - - - - - - BREADCRUMBS CONTENT - - - - - - - -->
                    <?php
                        //tpl_flush();
                        //my_youarehere(str_repeat(chr(9),4), 'header');
                        //my_breadcrumbs(str_repeat(chr(9),4), 'header');
                    ?>				</nav>
                    <!-- - - - - - - - - END OF BREADCRUMBS CONTENT  - - - - - - - -->
                    </nav>
                </div>
				<div id="page-headline-layout">
					<p id="site-headline"><?php tpl_link( my_homelink(), htmlentities($conf['title']), ''); ?></p>
					<p id="site-claim"><?php echo htmlentities($conf['tagline']); ?></p>
				</div>
				<div id="header-navigation">
					<!-- - - - - - - - - SIDEBAR CONTENT - - - - - - - -->
					<?php
								//tpl_flush();
								//tpl_include_page($conf['sidebar'], true, true);
					?>
				<!-- - - - - - - - - END OF SIDEBAR CONTENT  - - - - - - - -->
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
		</header><!-- end header -->

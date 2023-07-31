			<!-- begin toolbar -->
			<div id="site-toolbar">
				<div id="tb-siteinfo">
<?php 

    /* collect all tool items: */
	$list = array_merge(
	    (new \dokuwiki\Menu\PageMenu())->getItems(),
	    (new \dokuwiki\Menu\SiteMenu())->getItems(),
	    (new \dokuwiki\Menu\UserMenu())->getItems(),
	);

    /* list of items that should be ignored: */
    $exTB  = ['top', 'profile', 'login','logout']; /* don't show in toolbar */
    $exPop = ['top']; /* don't show in popup menu */

    $tbType = tpl_getConf('toolbarstyle');

    if (tpl_getConf('userinfo') == 'toolbar') {
        my_userinfo(str_repeat(chr(9),5));
    } else {
        tpl_includeFile('siteinfo.html');
    }
 ?>
				</div>
				<div id="site-toolbar-group">
<?php
	pActionlist("\t\t\t\t", 'pagetools-menu', $list, $exTB, $tbType, false);
 ?>
					<div id="tb-menu-group">
						<button id="pagetools-btn" aria-haspopup="menu" aria-controls="pagetools-popup" title="<?php echo htmlentities($lang['tools']); ?>">
							<span class="icon"><svg viewBox="0 0 24 24"><path d="M3,6H21V8H3V6M3,11H21V13H3V11M3,16H21V18H3V16Z" /></svg></span>
							<span class="label"><?php echo htmlentities($lang['tools']); ?></span>
						</button>
<?php
	pActionlist("\t\t\t\t\t\t", 'pagetools-popup', $list, $exPop, '', true);
 ?>
					</div>
				</div>
			</div>
			<!-- end of toolbar -->

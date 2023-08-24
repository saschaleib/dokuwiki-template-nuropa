			<div id="site-toolbar">
				<div id="tb-siteinfo">
<?php

    $tbStyle = tpl_getConf('toolbarstyle');

    /* collect all toolbar items from various DW menus: */
	$list = array_merge(
	    (new \dokuwiki\Menu\PageMenu())->getItems(),
	    (new \dokuwiki\Menu\SiteMenu())->getItems(),
	    (new \dokuwiki\Menu\UserMenu())->getItems(),
	);

    /* lists of tb items that should *not* be shown: */
    $exTB  = ['top', 'profile', 'login','logout']; /* don't show in toolbar */
    $exPop = ['top']; /* don't show in popup menu */

    if (tpl_getConf('userinfo') == 'toolbar') {
        my_userinfo(str_repeat(chr(9),5));
    } else {
        tpl_includeFile('siteinfo.html');
    }
 ?>
				</div>
				<div id="site-toolbar-group">
<?php
	pActionlist(str_repeat(chr(9),4), 'pagetools-menu', $list, $exTB, $tbStyle, false);
 ?>
					<div id="tb-menu-group">
						<button id="pagetools-btn" aria-haspopup="menu" aria-controls="pagetools-popup" title="<?php echo htmlentities($lang['tools']); ?>">
							<span class="icon"><svg viewBox="0 0 24 24"><path d="M12,20C7.59,20 4,16.41 4,12C4,7.59 7.59,4 12,4C16.41,4 20,7.59 20,12C20,16.41 16.41,20 12,20M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M13,7H11V11H7V13H11V17H13V13H17V11H13V7Z" /></svg></span>
							<span class="label"><?php echo htmlentities($lang['tools']); ?></span>
						</button>
<?php
	pActionlist(str_repeat(chr(9),6), 'pagetools-popup', $list, $exPop, '', true);
 ?>
					</div>
				</div>
			</div>

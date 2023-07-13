<?php
/**
 * Overwriting DokuWiki template functions
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Sascha Leib <sascha@leib.be>
 */

use dokuwiki\Extension\Event;
use dokuwiki\File\PageResolver;

/**
 * Creates the Site toolbar:
 *
 */
function my_toolbar() {
    global $lang;

	echo '<span class="toolbar-group">'
		.'<button id="pagetools-btn" aria-haspopup="menu" aria-controls="pagetools-menu" data-type="menu">'
		.'<span class="label">' . $lang['page_tools'] . '</span></button>'
		.'<ul id="pagetools-menu" class="toolbar-menu">';
	
	// get the page menu items:
	$items = (new \dokuwiki\Menu\PageMenu())->getItems();
	foreach($items as $item) {
		switch ($item->getType()) {
			case 'top':
				// do nothing
				break;
			default:
				echo '<li data-type="' . $item->getType() . '">'
					.'<a href="'.$item->getLink().'" title="'.$item->getTitle().'" rel="nofollow">'
					.'<span class="icon">'.inlineSVG($item->getSvg()).'</span>'
					.'<span class="label">'.$item->getLabel().'</span>'
					.'</a></li>';
		}
	}
	
	// get the user menu items:
	$items = (new \dokuwiki\Menu\UserMenu())->getItems();
	foreach($items as $item) {
		switch ($item->getType()) {
			case 'profile':
			case 'login':
				// ignore
				break;
			default:
				echo '<li data-type="' . $item->getType() . '">'
					.'<a href="'.$item->getLink().'" title="'.$item->getTitle().'" rel="nofollow">'
					.'<span class="icon">'.inlineSVG($item->getSvg()).'</span>'
					.'<span class="label">'.$item->getLabel().'</span>'
					.'</a></li>';
		}
	}

	
	
	echo '</ul></span>';

}

/**
 * Creates the Site logo image link
 *
 */
function my_sitelogo() {
    global $conf;

	// get logo either out of the template images folder or data/media folder
	$logoSize = array();
	$logo = tpl_getMediaFile(array(':logo.svg', ':wiki:logo.svg', ':logo.png', ':wiki:logo.png', 'images/sitelogo.svg'), false, $logoSize);
	tpl_link( my_homelink(),
		'<img src="'.$logo.'" ' . (is_array($logoSize) && array_key_exists(3, $logoSize) ? $logoSize[3] : '') . ' alt="' . htmlentities($conf['title']) . '" />', 'class="logo"');
}

/**
 * get a link to the homepage.
 *
 * wraps the original wl() function to allow overriding in the options
 *
 * @author Sascha Leib <sascha@leib.be>
 *
 * @returns string (link)
 */
function my_homelink() {
    global $conf;
	
	$hl = trim(tpl_getConf('homelink'));

	if ( $hl !== '' ) {
		return $hl;
	} else {
		return wl(); // default homelink
	}
}

/**
 * My implementation of the basic userinfo (in the global banner)
 *
 *
 * @author Sascha Leib <sascha@leib.be>
 *
 * @param  string $prefix to be added before each line
 *
 * @return void
 */
function my_userinfo($prefix = '') {
    global $lang;
    global $INPUT;
    global $INFO;

	// get the user menu items:
	$items = (new \dokuwiki\Menu\UserMenu())->getItems();

	// prefix:
	echo $prefix . "<dl id=\"gUserTools\">\n";
	
	// find the login/user name item first:
	foreach($items as $it) {
		$typ = $it->getType();
		if ($typ === 'profile') {
			echo $prefix . "\t<dt><span class=\"sr-only\">" . $lang['loggedinas'] . "</span></dt>\n"
				. $prefix . "\t<dd data-type=\"profile\">"
				. '<a href="'.htmlentities($it->getLink()).'" title="'.$it->getTitle().'" rel="nofollow">'
					.'<span class="icon">'.inlineSVG($it->getSvg()).'</span>'
					.'<span class="label">'.$INFO['userinfo']['name'].'</span>'
				.'</a></dd>';
		} else if ($typ === 'login') {
			echo $prefix . "\t<dt><span class=\"sr-only\">" . $it->getTitle() . "</span></dt>\n"
				. $prefix . "\t<dd data-type=\"login\">"
				.'<a href="'.htmlentities($it->getLink()).'" title="'.$it->getTitle().'" rel="nofollow">'
					.'<span class="icon">'.inlineSVG($it->getSvg()).'</span>'
					.'<span class="label">'.$it->getLabel().'</span>'
				.'</a></dd>';
		}
	}
	echo $prefix . "</dl>";
}


/**
 * Hierarchical breadcrumbs
 *
 * Cleanup of the original code to create neater and more accessible HTML
 *
 * @author Sascha Leib <sascha@leib.be>
 * @author Andreas Gohr <andi@splitbrain.org>
 * @author Nigel McNie <oracle.shinoda@gmail.com>
 * @author Sean Coates <sean@caedmon.net>
 * @author <fredrik@averpil.com>
 *
 * @param  string $prefix to be added before each line
 *
 */
function my_youarehere($prefix = '') {
    global $conf;
    global $ID;
    global $lang;

    // check if enabled
    if(!$conf['youarehere']) return false;

    $parts = explode(':', $ID);
    $count = count($parts);
	$isdir = ( $parts[$count-1] == $conf['start']);
	
	$hl = trim(tpl_getConf('homelink'));

	echo $prefix . "\t<p class=\"sr-only\">" . $lang['youarehere'] . "</p>\n";
	echo $prefix . "\t<ol>\n";

    // always print the startpage
	if ( $hl !== '' ) {
		echo $prefix . "\t\t<li class=\"home\">" . tpl_link( $hl, htmlentities(tpl_getLang('homepage')), ' title="' . htmlentities(tpl_getLang('homepage')) . '"', true) . "</li>\n";
		echo $prefix . "\t\t<li>" . tpl_pagelink(':'.$conf['start'], null, true) . "</li>\n";
	} else {
		echo $prefix . "\t\t<li class=\"home\">" . tpl_pagelink(':'.$conf['start'], null, true) . "</li>\n";
	}

    // print intermediate namespace links
    $page = '';
    for($i = 0; $i < $count - 1; $i++) {
        $part = $parts[$i];
        $page .= $part . ':';

		if ($i == $count-2 && $isdir)  break; // Skip last if it is an index page
	
		echo $prefix . "\t\t<li>" . tpl_pagelink($page, null, true) . "</li>\n";
    }

    // chould the current page be included in the listing?
	$trail = tpl_getConf('navtrail');
	
	if ($trail !== 'none' && $trail !== '') {

		echo $prefix . "\t\t<li class=\"current\">";
		if ($trail == 'text') {
			echo tpl_pagetitle($page . $parts[$count-1], true);
		} else if ($trail == 'link') {
			echo tpl_pagelink($page . $parts[$count-1], null, true);
		}
		echo "</li>\n";
	}
	
	echo $prefix . "\t</ol>\n";
}


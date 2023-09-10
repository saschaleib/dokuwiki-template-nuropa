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
    global $USERINFO;

    // is there a user logged in?
    $userid = $INPUT->server->str('REMOTE_USER');

    // get the user menu items:
    $items = (new \dokuwiki\Menu\UserMenu())->getItems();

    if (isset($USERINFO['name'])) { // user is loggd in:

        // output the button:
        echo $prefix . "<button id=\"user-button\" aria-haspopup=\"menu\" aria-controls=\"user-action-menu\">\n";
        echo $prefix . "\t<svg class=\"icon\" viewBox=\"0 0 24 24\"><path d=\"M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z\" /></svg>\n";
        echo $prefix . "\t<span class=\"label\"><span class=\"sr-only\">" . htmlentities($lang['loggedinas']) . ' </span>' . htmlentities($USERINFO['name']) . "</span>\n";
        echo $prefix . "</button>\n";

        // add the menu:
        pActionlist($prefix, 'user-action-menu', $items, ['admin'], '', true);

    } else {
        pActionlist($prefix, 'user-action-buttons', $items, ['admin']);
    }

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
function my_youarehere($prefix, $position) {
    global $conf;
    global $ID;
    global $lang;

    // check if enabled
    if(!$conf['youarehere']) return false;

    // check if right position:
    if(tpl_getConf('youareherepos') !== $position) return false;

    $parts = explode(':', $ID);
    $count = count($parts);
	$isdir = ( $parts[$count-1] == $conf['start']);

	$hl = trim(tpl_getConf('homelink'));

	echo $prefix . "<p class=\"sr-only\">" . $lang['youarehere'] . "</p>\n";
	echo $prefix . "<ol>";

    // always print the startpage
	if ( $hl !== '' ) {
		echo '<li class="home">' . tpl_link( $hl, htmlentities(tpl_getLang('homepage')), ' title="' . htmlentities(tpl_getLang('homepage')) . '"', true) . '</li>';
		echo '<li>' . tpl_pagelink(':'.$conf['start'], null, true) . '</li>';
	} else {
		echo '<li class="home">' . tpl_pagelink(':'.$conf['start'], null, true) . '</li>';
	}

    // print intermediate namespace links
    $page = '';
    for($i = 0; $i < $count - 1; $i++) {
        $part = $parts[$i];
        $page .= $part . ':';

		if ($i == $count-2 && $isdir)  break; // Skip last if it is an index page

		echo '<li>' . tpl_pagelink($page, null, true) . '</li>';
    }

    // chould the current page be included in the listing?
	$trail = tpl_getConf('navtrail');

	if ($trail !== 'none' && $trail !== '') {

		echo '<li class="current">';
		if ($trail == 'text') {
			echo tpl_pagetitle($page . $parts[$count-1], true);
		} else if ($trail == 'link') {
			echo tpl_pagelink($page . $parts[$count-1], null, true);
		}
		echo "</li>";
	}

	echo "</ol>\n";
}

/**
 * Print the breadcrumbs trace
 *
 * Cleanup of the original code to create neater and more accessible HTML
 *
 * @author Sascha Leib <sascha@leib.be>
 * @author Andreas Gohr <andi@splitbrain.org>
 *
 * @param string $prefix inserted before each line
 *
 * @return void
 */
function my_breadcrumbs($prefix, $position) {
    global $lang;
    global $conf;

    //check if enabled
    if(!$conf['breadcrumbs']) return false;

    // check if right position:
    if(tpl_getConf('breadcrumbpos', 'none') !== $position) return false;

    $crumbs = breadcrumbs(); //setup crumb trace

	/* begin listing */
	echo $prefix . '<p class="sr-only">' . $lang['breadcrumb'] . "</p>\n";
	echo $prefix . "<ol reversed>";

    $last = count($crumbs);
    $i    = 0;
    foreach($crumbs as $id => $name) {
        $i++;
		echo '<li' . ($i == $last ? ' class="current"' : '') . '><bdi>' . tpl_link(wl($id), hsc($name), '', true) .  '</bdi></li>';
    }
	echo "</ol>\n";
}

/**
 * Insert the main headline for the page
 *
 * provides additional configuration options for this element
 *
 * @author Sascha Leib <sascha@leib.be>
 *
 * @param string $prefix inserted before each line
 *
 * @return void
 */
function my_pagetitle($prefix) {
    global $ID;
    global $conf;
	
	/* get the header style */
	$type = my_headerstyle();

	/* build the headline section */
	echo $prefix . '<div class="type-' . $type . "\">\n";
	switch ($type) {
		case 'file':
			tpl_includeFile('title.html');
			break;
		case 'sitename':
			echo $prefix . "\t<p class=\"title\">" . $conf['title'] . "</p>\n";
			echo $prefix . "\t<p class=\"tagline\">" . $conf['tagline'] . "</p>\n";
			break;
		case 'pagename':
			echo $prefix . "\t<h1>" . tpl_pagetitle($ID, true) . "</h1>\n";
			break;
		default:
			echo "<pre>UNKNOWN:" . tpl_getConf('pageheadline') . "</pre>";
			break;
	}
	echo $prefix . "</div>\n";

}

/**
 * Print the breadcrumbs trace
 *
 * Cleanup of the original code to create neater and more accessible HTML
 *
 * @author Sascha Leib <sascha@leib.be>
 * @author Andreas Gohr <andi@splitbrain.org>
 *
 * @param string $prefix inserted before each line
 *
 * @return void
 */
function my_banner_style() {
    global $ID;
    global $conf;

    /* don't run if banner image is disabled */
    if (trim(tpl_getConf('bannerimg')) == '') return;

    $background = null;

    /* list of extensions to look for (in reverse order) */
    $exts = array('jpg','jpeg','png','svg');

    /* build a list of paths to look at: */
    $cpath = '';
    $paths = array();
    foreach (explode(':', $ID) as $it) {
        foreach ($exts as $ext) {
            array_push($paths, $cpath . trim(tpl_getConf('bannerimg')) . '.' . $ext);
        }
        $cpath .= $it . ':';
    }
    $paths = array_reverse($paths); /* reverse the order */

    /* check if an image exists in one of the paths: */
    foreach ($paths as $rpath) {
        $lpath = mediaFN($rpath);
        if (file_exists($lpath)) {
            $background = ml($rpath, '', true, '', false);
            break;
        }
    }
	/* if not found, use the template banner: */
	if (!$background) {
		$background = tpl_basedir() . 'images/banner.jpg';
	}

    /* find the element height */
    $height = tpl_getConf('bannersize', 'inherit');


    if ($background) {
        echo "background-image: url('" . $background . "');min-height: " . $height . ';';
    }
}

/* private helper function to putput a list of action items: */
function pActionlist($prefix, $id, $list, $exclude, $type = '', $hidden = false) {

	/* build menu */
	echo $prefix . '<ul id="' . $id . '" class="' . htmlentities($type) . '"'  . ($hidden ? ' style="display:none"' : '') . ">\n";
	foreach($list as $it) {
		$typ = $it->getType();
		if (!in_array($typ, $exclude)) {
		   echo $prefix . "\t<li data-type=\"" . $typ . '">'
				 .'<a href="'.$it->getLink().'" title="'.$it->getTitle().'" rel="nofollow">'
				 .inlineSVG($it->getSvg())
				 .'<span class="label">'.$it->getLabel().'</span>'
				 ."</a></li>\n";
		}
	}
	echo $prefix . "</ul>\n";
};

/* helper function to determine the headline style: */
function my_headerstyle() {
	global $ID;
    global $conf;
	
	/* what kind of headline should be included? */
	$type = tpl_getConf('pageheadline');
	if ($type == 'siteonhp') {
		if ($ID == $conf['start']) {
			$type = 'sitename';
		} else {
			$type = 'pagename';
		}
	}
	return $type;
}
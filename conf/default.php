<?php
/**
 * Configuration settings for the Nuropa template
 *
 * @author     Sascha Leib <sascha@leib.be>
 */

/* what should be displayed in the left corner of the toolbar? ('user', 'langs' or 'none') */
$conf['toolbaropt'] = 'user';

/* appearance of the toolbar ('auto' or 'compact') */
$conf['toolbarstyle'] = 'auto';

/* How should the site logo be displayed ('image' or 'file') */
$conf['sitelogo'] = 'image';


/* what should be displayed to the right of the search field? ('user', 'langs' or 'none') */
$conf['searchbaropt'] = 'langs';

/* what to show in place of a page headline */
$conf['pageheadline'] = 'sitename';

/* name of the banner file (without extension) and min height of the banner bar */
$conf['bannerimg'] = 'banner'; /* empty = no banner bar */
$conf['bannersize'] = '48px'; /* any CSS length, or 'auto' */


/* allow dark mode styling ('off' or 'auto') */
$conf['showtoc'] = 'show';

/* allow overriding the home link */
$conf['homelink'] = '';

/* show a languages menu? */
$conf['langmenu']		= 'sb';
$conf['langfilter']		= 'all';

/* allow dark mode styling ('off' or 'auto') */
$conf['darkmode'] = 'auto';

/* TODO: Delete me! */
$conf['breadcrumbpos'] = 'banner';
$conf['youareherepos'] = 'banner';
/* placement of the user info item */
$conf['userinfo'] = 'toolbar';

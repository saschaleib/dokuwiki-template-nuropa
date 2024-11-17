<?php
/**
 * Configuration settings for the Nuropa template
 *
 * @author     Sascha Leib <sascha@leib.be>
 */

/* what should be displayed in the left corner of the toolbar? ('static', 'user' or 'langs') */
$conf['toolbaropt'] = 'static';

/* what should be displayed to the right of the search field? ('static', 'user' or 'langs') */
$conf['searchbaropt'] = 'static';


/* position of the breadcrumbs and 'you are here' lists ('header' or 'post') */
$conf['breadcrumbpos'] = 'banner';
$conf['youareherepos'] = 'banner';

/* what to show in place of a page headline */
$conf['pageheadline'] = 'sitename';

/* placement of the user info item */
$conf['userinfo'] = 'toolbar';

/* name of the banner file (without extension) and min height of the banner bar */
$conf['bannerimg'] = 'banner'; /* empty = no banner bar */
$conf['bannersize'] = '48px'; /* any CSS length, or 'auto' */

/* appearance of the toolbar ('auto' or 'compact') */
$conf['toolbarstyle'] = 'auto';

/* allow dark mode styling ('off' or 'auto') */
$conf['showtoc'] = 'show';

/* allow overriding the home link */
$conf['homelink'] = '';

/* show a languages menu? */
$conf['langmenu']		= 'sb';
$conf['langfilter']		= 'existing';

/* allow dark mode styling ('off' or 'auto') */
$conf['darkmode'] = 'off';

/* show all or only existing translations? */
$conf['langfilter']		= 'all';

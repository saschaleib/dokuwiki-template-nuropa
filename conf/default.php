<?php
/**
 * Configuration settings for the Nuropa template
 *
 * @author     Sascha Leib <sascha@leib.be>
 */

/* position of the breadcrumbs and 'you are here' lists ('header' or 'post') */
$conf['breadcrumbpos']  = 'banner';
$conf['youareherepos']  = 'header';

/* what to show in place of a page headline */
$conf['pageheadline']  = 'sitename';

/* how to show the last element in the nav trail ('you are here')? */
$conf['navtrail']		= 'link';

/* placement of the user info item */
$conf['userinfo']		= 'toolbar';

/* name of the banner file (without extension) and min height of the banner bar */
$conf['bannerimg']		= 'banner'; /* empty = no banner bar */
$conf['bannersize']		= '48px'; /* any CSS length, or 'auto' */

/* appearance of the toolbar ('auto' or 'compact') */
$conf['toolbarstyle']   = 'auto';

/* allow dark mode styling ('off' or 'auto') */
$conf['darkmode']   = 'off';

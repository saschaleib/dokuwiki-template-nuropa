<?php
/**
 * Configuration settings for the Nuropa template
 *
 * @author     Sascha Leib <sascha@leib.be>
 */

$meta['toolbarstyle'] = array('multichoice',
						'_choices' => array ('auto', 'compact'));

$meta['breadcrumbpos'] = array('multichoice',
						'_choices' => array ('header', 'banner'));
$meta['youareherepos'] = array('multichoice',
						'_choices' => array ('header', 'banner'));

$meta['pageheadline'] = array('multichoice',
						'_choices' => array ('sitename', 'pagename', 'siteonhp','file'));

$meta['navtrail'] = array('multichoice',
						'_choices' => array ('none', 'text', 'link'));

$meta['userinfo'] = array('multichoice',
						'_choices' => array ('toolbar', 'header'));

$meta['bannerimg'] = array('string');

$meta['bannersize'] = array('string');

$meta['showtoc'] = array('multichoice',
						'_choices' => array ('show', 'hide'));

$meta['darkmode'] = array('multichoice',
						'_choices' => array ('off', 'auto'));

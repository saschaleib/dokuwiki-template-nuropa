<?php
/**
 * Template footer, included in the main and detail files
 */

// must be run from within DokuWiki
if (!defined('DOKU_INC')) die();
 ?>			<footer><!-- BEGIN FOOTER -->
				<div id="staticfooter">
<?php				tpl_includeFile('footer.html'); ?>
				</div>
				<div id="ftLicenseButtons">
					<p class="license">
						<!-- begin license info -->
						<?php tpl_license('', false, false, false); ?>

						<!-- begin licenseinfo.html include -->
<?php				tpl_includeFile('licenseinfo.html'); ?>
						<!-- end of license info -->
					</p>
<?php if($conf['license'] != null): ?>					<div class="buttons noprint">
					<?php
						tpl_license('button', true, false, false);
					
						$target = ($conf['target']['extern']) ? 'target="'.$conf['target']['extern'].'"' : '';
					?>

						<a href="https://www.dokuwiki.org/donate" title="Donate" <?php echo $target?> target="_blank"><img src="<?php echo tpl_basedir(); ?>images/button/donate.svg" width="80" height="15" alt="Donate"></a>
						<a href="https://php.net" title="Powered by PHP" <?php echo $target?> target="_blank"><img src="<?php echo tpl_basedir(); ?>images/button/php8.svg" width="80" height="15" alt="Powered by PHP"></a>
						<a href="//validator.w3.org/check/referer" title="Valid HTML5" <?php echo $target?> target="_blank"><img src="<?php echo tpl_basedir(); ?>images/button/html5.svg" width="80" height="15" alt="Valid HTML5"></a>
						<a href="//jigsaw.w3.org/css-validator/check/referer?profile=css3" title="Valid CSS" <?php echo $target?> target="_blank"><img src="<?php echo tpl_basedir(); ?>images/button/css.svg" width="80" height="15" alt="Valid CSS"></a>
						<a href="https://dokuwiki.org/" title="Driven by DokuWiki" <?php echo $target?> target="_blank"><img src="<?php echo tpl_basedir(); ?>images/button/dw.svg" width="80" height="15" alt="Driven by DokuWiki"></a>
					</div><?php endif; ?>
				</div>
			</footer><!-- END OF FOOTER -->

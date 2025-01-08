<?php
/**
 * Template footer, included in the main and detail files
 */

// must be run from within DokuWiki
if (!defined('DOKU_INC')) die();
 ?>	<div id="footer__layout">
			<footer><!-- BEGIN FOOTER -->
				<div id="staticfooter">
<?php				tpl_includeFile('footer.html'); ?>
				</div>
				<div id="ftLicenseButtons">
					<div class="license">
						<!-- license text -->
						<?php tpl_license('');?>

						<!-- alternatively include file: licenseinfo.html -->
						<?php tpl_includeFile('licenseinfo.html'); ?>
						<!-- end of license info -->
					</div>
<?php if($conf['license'] != null): ?>					<p class="buttons noprint">
					<?php 
						$target = ($conf['target']['extern']) ? 'target="'.$conf['target']['extern'].'"' : '';
						my_license_button($target);
					?>

						<a href="https://www.dokuwiki.org/donate" title="Donate" <?php echo $target?>><img src="<?php echo tpl_basedir(); ?>images/button/donate.svg" width="80" height="15" alt="Donate"></a>
						<a href="https://php.net" title="Powered by PHP" <?php echo $target?>><img src="<?php echo tpl_basedir(); ?>images/button/php8.svg" width="80" height="15" alt="Powered by PHP"></a>
						<a href="//validator.w3.org/check/referer" title="Valid HTML5" <?php echo $target?>><img src="<?php echo tpl_basedir(); ?>images/button/html5.svg" width="80" height="15" alt="Valid HTML5"></a>
						<a href="//jigsaw.w3.org/css-validator/check/referer?profile=css3" title="Valid CSS" <?php echo $target?>><img src="<?php echo tpl_basedir(); ?>images/button/css.svg" width="80" height="15" alt="Valid CSS"></a>
						<a href="https://dokuwiki.org/" title="Driven by DokuWiki" <?php echo $target?>><img src="<?php echo tpl_basedir(); ?>images/button/dw.svg" width="80" height="15" alt="Driven by DokuWiki"></a>
					</p><?php endif; ?>
				</div>
			</footer><!-- END OF FOOTER -->
			<div class="no"><?php tpl_indexerWebBug() /* provide DokuWiki housekeeping, required in all templates */ ?></div>
		</div>
/**
 *  Page scripts for the Nuropa Template
 *
 * @author     Sascha Leib <sascha@leib.be>
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */

/* everything is contained in the $p namespace: */
$p = {

	/* called to initialize the entire script */
	init:	function() {
		//console.info('init()')
		$p.gui.init();
		$p.keyboard.init();
	},

	/* all functions that are related to the keyboard control */
	keyboard: {
		init: function() {
			//console.info('keyboard.init()')
		},
		
		/* ask to be informed about a specific key press event.
		 * parameters:
		 *   element: String - selector for the element to attach to (e.g. 'body', '#search')
		 *   key: String - name of the key to look for (e.g. 'Escape', 's', etc.)
		 *   modifiers: Array - list of modifiers that are required to trigger the event
		 *   callback: Function - to be called when the event happens
		 * Returns: String ID of the stored reference
		 */
		waitForKey: function(element, key, modifiers, callback) {
			
			/* build the storage object */
			item = {
				id: null, /* unique ID (see below) */
				e: jQuery(element),
				k: key,
				m: modifiers,
				c: callback,
				$: null /* jQuery object (set below) */
			};
			
			/* give the item a unique id */
			var exists = false;
			do {
				item.id = Math.random().toString(16).slice(2);
				$p.keyboard._list.every( it => {
					if (item.id == it.id) {
						exists = true; /* found a double! */
					}
					return !exists; /* must return true if not found! */
				});
			} while (exists);
			
			//console.log('new keyboard monitoring id ' + item.id + ' on object "' + element + '".');
			
			/* attach the event listener: */
			item.e.on('keyup', null, item.id, $p.keyboard._callback._handleKeyUp);
			$p.keyboard._list.push(item);
			
			return item.id; /* to remove the monitor, if needed. */
		},
		
		/* cancel a specific active monitor (pass the id): */
		cancel: function(id) {
			//console.info('keyboard.cancel(' + id + ')');
		
			$p.keyboard._list = $p.keyboard._list.filter( it => { 
				return (it.id !== id);
			});
		},
		
		/* clear all waitForKey callbacks */
		clear: function() {
			$p.keyboard._list = [];
		},
		
		/* list of monitoring callbacks */
		_list: [],
		
		/* internal callback functions */
		_callback: {
			
			/* called internally on any registered keyup event */
			_handleKeyUp: function(e) {
				//console.info('_handleKeyUp');
				
				/* collect the interesting info: */
				let target = e.target;
				let data = (e.data ? e.data : null);
				let evt = e.originalEvent;
				let key = evt.key;
				let modifiers = {
					'alt': evt.altKey,
					'ctrl': evt.ctrlKey,
					'meta': evt.metaKey,
					'shift': evt.shiftKey
				};
				
				//console.log(key);
				
				/* check if any registered callback matches: */
				var match = null;
				$p.keyboard._list.forEach( it => {
					if ((it.id == data) && (it.k == key)) {
						
						// TODO: also check for modifiers! 
						
						//console.log("correct key. calling callback:");
						if (it.c && it.c instanceof Function) {
							it.c();
						}
					}
				});
			}
		}
	},

	gui: {

		init: function() {

			//console.info('gui.init()')

			$p.gui.toolbar.init();
			$p.gui.popover.init();
			$p.gui.search.init();

		},

		popover: {
			init: function() {
				// add callback function to buttons using jQuery:
				jQuery('button[popovertarget]')
					.click($p.gui.popover._onBtnClick);
					
				// event on popover avoids jQuery to no have to deal with jQuery event wrappers
				jQuery('[popover]').each( (n, it) => {
					it.addEventListener('toggle', $p.gui.popover._onToggle);
				});
			},
			
			alignPopup: function(popup, button, align = 'center') {
				//console.info('$p.gui.popover.alignPopup(' + align + ')');
				//console.log(popup);
				
				const kOffsetX = 5;
				const kOffsetY = 7;

				// get the menu dimensions:
				const mnu = jQuery(popup).position();
				mnu.width = jQuery(popup).outerWidth();
				
				// try to align the menu to the left of the button:
				let mLeft = jQuery(button).position().left; 
				switch (align) {
					case 'right':
						mLeft = mLeft + jQuery(button).outerWidth() - jQuery(popup).outerWidth();
						break;
					case 'center':
						mLeft = mLeft + ( jQuery(button).outerWidth() / 2) - ( jQuery(popup).outerWidth() / 2);
					default: // = 'left'
						// already set.
				}
				
				// but make sure it is fully visible:
				if (mLeft < kOffsetX) mLeft = kOffsetX;
				if ((mLeft + mnu.width) > (window.innerWidth - kOffsetX))
					mLeft = window.innerWidth - mnu.width - kOffsetX;
				
				jQuery(popup).css('left', mLeft);
				
				/* make sure the menu is under the button:*/
				let mTop = jQuery(button).position().top + jQuery(button).outerHeight() + kOffsetY;
				jQuery(popup).css('top', mTop);

			},
			
			_onBtnClick: function(e) {
				//console.info('$p.gui.popover._onBtnClick()');

				try {
					// which button has been clicked? 
					const btn = e.currentTarget;

					// find the popover target:
					const popup = document.getElementById(btn.getAttribute('popovertarget'));
					//console.log(' -> open: ' + popup.matches(':popover-open'));
					
					// align the menu:
					let mAlign = btn.getAttribute('data-alignmenu');
					if (!mAlign) mAlign = 'center';
					
					// align the popup under the button:
					$p.gui.popover.alignPopup(popup, btn, mAlign);
					
					// modify the button to reflect the state change
					// (this needs to be inverted, because it happens *before* the state change!):
					btn.setAttribute('data-isopen', !popup.matches(':popover-open'));

				} catch(err) {
					console.log(err);
				}
			},
			
			_onToggle: function(e) {
				//console.info('$p.gui.popover._onToggle()');
				
				try {
					// set the 'isopen' attribute in the opener (button) of the menu:
					document.getElementById(e.target.getAttribute('data-controlledby'))
						.setAttribute('data-isopen', ( e.newState == 'open' ) );
				} catch(err) {
					console.warn('Could not find opener element.');
				}
			}

		},

		toolbar: {

			init: function() {
				//console.info('gui.toolbar.init()');
	
				jQuery(window).on('resize', function() {
					$p.gui.toolbar._resized();
				});
	
				$p.gui.toolbar._resized();
			},

			/* callback for the resize event: */
			_resized: function() {
				//console.info('gui.toolbar._callback.resized()');

				/* recalculate the overflow menu */

				/* first, check which items are visible in the toolbar: */
				let tb = jQuery('#tb__tools__menu');
				let parentWidth = jQuery(tb).innerWidth();
				var itemPos = 0; /* current position */
				let shown = []; /* list of items shown in the toolbar */
				let items = jQuery(tb).children('li');

				jQuery(items).show();
				jQuery(tb).children('li').each((i, it) => {

					let itemId = jQuery(it).data('type');
					let itemWidth = jQuery(it).outerWidth(true);
					if ( (itemId) && (itemPos + itemWidth - 10) < parentWidth ) {
						shown.push(itemId);
					} else {
						//jQuery(it).hide();
					}
					itemPos += itemWidth;
				});

				/* hide already shown items in the popup: */
				jQuery('#pagetools__popup ul').children('li').each((i, it) => {
					let itemId = jQuery(it).data('type');
					if (itemId) {
						if (shown.includes(itemId)) {
							it.setAttribute('aria-hidden', 'true');
						} else {
							it.setAttribute('aria-hidden', 'false');
						}
					}
				});
			}
		},
		
		search: {
			init: function() {
				console.info('$p.gui.search.init()');

				// use Escape key to exit the search field:
				$p.keyboard.waitForKey(
					document.getElementById('search__backdrop'),
					'Escape', null,
					$p.gui.search.closeSearch);
					
				// or click the Close-button:
				document.getElementById('search__close')
					.addEventListener('click', $p.gui.search.closeSearch);
				
				// attach to keyup event in search field:
				document.getElementById('qsearch__in')
					.addEventListener('keyup', $p.gui.search._SearchFieldOnKeyUp);
			},
			
			closeSearch: function() {
				//console.info('$p.gui.search.closeSearch()');
				document.activeElement.blur();
			},
			
			_SearchFieldOnKeyUp: function(e) {
				console.info('$p.gui.search._SearchFieldOnKeyUp()');
				console.log(e);
			}
		}
	}
};

// init when document is ready:
jQuery(function() { $p.init(); });

// disable DW search scripts:
jQuery.fn.dw_qsearch = function (overrides) {
	// do nothing
}
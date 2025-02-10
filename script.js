/**
 *  Page scripts for the Nuropa Template
 *
 * @author     Sascha Leib <sascha@leib.be>
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */

/* everything is contained in the $p namespace: */
$p = {
	/* called to initialize the entire script */
	init: function () {
		//console.info('init()')
		$p.gui.init();
		$p.keyboard.init();
	},

	/* all functions that are related to the keyboard control */
	keyboard: {
		init: function () {
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
		waitForKey: function (element, key, modifiers, callback) {
			/* build the storage object */
			item = {
				id: null /* unique ID (see below) */,
				e: jQuery(element),
				k: key,
				m: modifiers,
				c: callback,
				$: null /* jQuery object (set below) */,
			};

			/* give the item a unique id */
			var exists = false;
			do {
				item.id = Math.random().toString(16).slice(2);
				$p.keyboard._list.every((it) => {
					if (item.id == it.id) {
						exists = true; /* found a double! */
					}
					return !exists; /* must return true if not found! */
				});
			} while (exists);

			//console.log('new keyboard monitoring id ' + item.id + ' on object "' + element + '".');

			/* attach the event listener: */
			item.e.on("keyup", null, item.id, $p.keyboard._callback._handleKeyUp);
			$p.keyboard._list.push(item);

			return item.id; /* to remove the monitor, if needed. */
		},

		/* cancel a specific active monitor (pass the id): */
		cancel: function (id) {
			//console.info('keyboard.cancel(' + id + ')');

			$p.keyboard._list = $p.keyboard._list.filter((it) => {
				return it.id !== id;
			});
		},

		/* clear all waitForKey callbacks */
		clear: function () {
			$p.keyboard._list = [];
		},

		/* list of monitoring callbacks */
		_list: [],

		/* internal callback functions */
		_callback: {
			/* called internally on any registered keyup event */
			_handleKeyUp: function (e) {
				//console.info('_handleKeyUp');

				/* collect the interesting info: */
				let target = e.target;
				let data = e.data ? e.data : null;
				let evt = e.originalEvent;
				let key = evt.key;
				let modifiers = {
					alt: evt.altKey,
					ctrl: evt.ctrlKey,
					meta: evt.metaKey,
					shift: evt.shiftKey,
				};

				//console.log(key);

				/* check if any registered callback matches: */
				var match = null;
				$p.keyboard._list.forEach((it) => {
					if (it.id == data && it.k == key) {
						// TODO: also check for modifiers!

						//console.log("correct key. calling callback:");
						if (it.c && it.c instanceof Function) {
							it.c();
						}
					}
				});
			},
		},
	},

	gui: {
		init: function () {
			//console.info('gui.init()')

			$p.gui.menu.init();
			$p.gui.toolbar.init();
			$p.gui.popover.init();
			$p.gui.search.init();
		},

		popover: {
			init: function () {
				// add callback function to buttons using jQuery:
				jQuery("button[popovertarget]").click($p.gui.popover._onBtnClick);

				// event on popover avoids jQuery to no have to deal with jQuery event wrappers
				jQuery("[popover]").each((n, it) => {
					it.addEventListener("toggle", $p.gui.popover._onToggle);
				});
			},

			alignPopup: function (popup, button, align = "center") {
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
					case "right":
						mLeft =
							mLeft + jQuery(button).outerWidth() - jQuery(popup).outerWidth();
						break;
					case "center":
						mLeft =
							mLeft +
							jQuery(button).outerWidth() / 2 -
							jQuery(popup).outerWidth() / 2;
					default: // = 'left'
					// already set.
				}

				// but make sure it is fully visible:
				if (mLeft < kOffsetX) mLeft = kOffsetX;
				if (mLeft + mnu.width > window.innerWidth - kOffsetX)
					mLeft = window.innerWidth - mnu.width - kOffsetX;

				jQuery(popup).css("left", mLeft);

				/* make sure the menu is under the button:*/
				let mTop =
					jQuery(button).position().top +
					jQuery(button).outerHeight() +
					kOffsetY;
				jQuery(popup).css("top", mTop);
			},

			_onBtnClick: function (e) {
				//console.info('$p.gui.popover._onBtnClick()');

				try {
					// which button has been clicked?
					const btn = e.currentTarget;

					// find the popover target:
					const popup = document.getElementById(
						btn.getAttribute("popovertarget"),
					);
					//console.log(' -> open: ' + popup.matches(':popover-open'));

					// align the menu:
					let mAlign = btn.getAttribute("data-alignmenu");
					if (!mAlign) mAlign = "center";

					// align the popup under the button:
					$p.gui.popover.alignPopup(popup, btn, mAlign);

					// modify the button to reflect the state change
					// (this needs to be inverted, because it happens *before* the state change!):
					btn.setAttribute("data-isopen", !popup.matches(":popover-open"));
				} catch (err) {
					console.log(err);
				}
			},

			_onToggle: function (e) {
				//console.info('$p.gui.popover._onToggle()');

				try {
					// set the 'isopen' attribute in the opener (button) of the menu:
					document
						.getElementById(e.target.getAttribute("data-controlledby"))
						.setAttribute("data-isopen", e.newState == "open");
				} catch (err) {
					console.warn("Could not find opener element.");
				}
			},
		},

		toolbar: {
			init: function () {
				//console.info('gui.toolbar.init()');

				jQuery(window).on("resize", function () {
					$p.gui.toolbar._resized();
				});

				$p.gui.toolbar._resized();
			},

			/* callback for the resize event: */
			_resized: function () {
				//console.info('gui.toolbar._callback.resized()');

				/* recalculate the overflow menu */

				/* first, check which items are visible in the toolbar: */
				let tb = jQuery("#tb__tools__menu");
				let parentWidth = jQuery(tb).innerWidth();
				var itemPos = 0; /* current position */
				let shown = []; /* list of items shown in the toolbar */
				let items = jQuery(tb).children("li");

				jQuery(items).show();
				jQuery(tb)
					.children("li")
					.each((i, it) => {
						let itemId = jQuery(it).data("type");
						let itemWidth = jQuery(it).outerWidth(true);
						if (itemId && itemPos + itemWidth - 10 < parentWidth) {
							shown.push(itemId);
						} else {
							//jQuery(it).hide();
						}
						itemPos += itemWidth;
					});

				/* hide already shown items in the popup: */
				jQuery("#pagetools__popup ul")
					.children("li")
					.each((i, it) => {
						let itemId = jQuery(it).data("type");
						if (itemId) {
							if (shown.includes(itemId)) {
								it.setAttribute("aria-hidden", "true");
							} else {
								it.setAttribute("aria-hidden", "false");
							}
						}
					});
			},
		},

		search: {
			init: function () {
				//console.info("$p.gui.search.init()");

				// use Escape key to exit the search field:
				$p.keyboard.waitForKey(
					document.getElementById("search__backdrop"),
					"Escape",
					null,
					$p.gui.search.closeSearch,
				);

				// or click the Close-button:
				document
					.getElementById("search__close")
					.addEventListener("click", $p.gui.search.closeSearch);

				// attach to keyup event in search field:
				document
					.getElementById("qsearch__in")
					.addEventListener("keyup", $p.gui.search._searchFieldOnKeyUp);
			},

			closeSearch: function () {
				//console.info('$p.gui.search.closeSearch()');
				document.activeElement.blur();
			},

			refreshSearch: function () {
				//console.info("$p.gui.search.refreshSearch()");
				const term = document.getElementById("qsearch__in").value.trim();
				const qOut = document.getElementById("qsearch__out");

				// function to fetch the search results:
				const fetchResults = async (term) => {;
					const response = await fetch(DOKU_BASE + 'lib/exe/ajax.php', {
						method: 'POST',
						body: new URLSearchParams({
							call: 'qsearch',
							q: encodeURI(term)
						}),
					});
					qOut.innerHTML = await response.text();
				};

				if (term == "") {
					console.log("search empty");
					qOut.innerHTML = "";
				} else if (term !== $p.gui.search.__lastSearch) {
					$p.gui.search.__lastSearch = term;
					fetchResults(term);
				}
				$p.gui.search.setBusyStatus(false);
			},

			_searchFieldOnKeyUp: function (e) {
				//console.info("$p.gui.search._SearchFieldOnKeyUp()");

				// set the refresh delay timeout:
				if ($p.gui.search.__timeoutHandle) {
					clearTimeout($p.gui.search.__timeoutHandle); // clear if already exists
				}
				$p.gui.search.__timeoutHandle = setTimeout(
					$p.gui.search.refreshSearch, 350,
				);
				$p.gui.search.setBusyStatus(true);
			},

			setBusyStatus: function(stat) {
				//console.info('$p.gui.search.setBusyStatus(' + stat + ')');
				const form = document.getElementById('dw__search');
				if (stat) {
					form.classList.add('busy');
				} else {
					form.classList.remove('busy');
				}
			},

			__timeoutHandle: null,
			__lastSearch: ""
		},

		/*
		menu: {
			init: function () {
				console.info('$p.gui.menu.init()');

				// find the menu bar element:
				const mbar = document.getElementById('sitemenu__layout');
				if (mbar) {

					// define the expansion SVG (chevron left):
					$svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M15.41,16.58L10.83,12L15.41,7.41L14,6L8,12L14,18L15.41,16.58Z" /></svg>';

					// find the menu list:
					const menu = jQuery(mbar).find('ul').first()[0];
					const meli = jQuery(menu).children('li.level1');

					// loop over all main menu items:
					for (let i = 0; i < meli.length; i++) {
						const li1 = meli[i];
						const divli = jQuery(li1).children('div.li').first();

						if (li1.classList.contains('node')) {

							// prep the button element:
							const btnId = 'menu__button' + i;
							const popId = 'menu__popup' + i;
							const btn = jQuery('<button>', {
								'id': btnId,
								'aria-haspopup': 'menu',
								'aria-controls': popId,
								'title': 'Expand',
								'popovertarget': popId,
								'data-isopen': 'false'
							});

							// insert the popup button:
							let link = jQuery(divli).children('a').first();
							if (link.length > 0) {
								jQuery(btn).html($svg);
								jQuery(li1).append(btn);
							} else {
								// if there is no link, we use a different style:
								let txt = jQuery(divli).contents().first()[0]
								btn.html('<span>' + txt.textContent + '</span>');
								jQuery(btn).append($svg);

								jQuery(divli).replaceWith(btn);
								li1.classList.add('nolink');
							}

							// the ul becomes the popup:
							const sub = jQuery(li1).children('ul').first()[0];
							console.log(sub);
							sub.setAttribute('id', popId);
							sub.setAttribute('popover', '');
							sub.setAttribute('data-controlledby', btnId);
						}
					}
					// TODO: also prepare the overflow menu:


					// TODO: calculate the overflow menu (same as onResize)
					//$p.gui.menu.__onResizeMenu(menu);

				}
			},

			__onResizeMenu: function (mBar) {
				console.info('$p.gui.menu.__onResizeMenu()');

				// find the menu list:
				const menu = jQuery(mbar).find('ul').first()[0];
				const meli = jQuery(menu).children('li.level1');

				// find the overflow list:
				const overflow = jQuery('#menu__overflow ul').first()[0];
				const ofli = jQuery(overflow).children('li.level1');

			},
		}
		*/
	}
};

// init when document is ready:
jQuery(function () {
	$p.init();
});

// disable DW search scripts:
jQuery.fn.dw_qsearch = function (overrides) {
	// do nothing
};

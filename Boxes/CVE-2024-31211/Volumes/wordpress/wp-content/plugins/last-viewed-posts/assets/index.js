amViewLastPosts = window.amViewLastPosts || {};

/*
 * Format of post items
 *
 * Cookie: post_id
 *
 * localStorage:
 * {
 *  'id': post_id,
 *  'url': post_permalink,
 *  'title': post_title,
 * }
 */
(amViewLastPosts.script = function( settings, window, document ) {
	var localStorageName = 'amViewLastPosts.recentPosts';
	var legacyCookieName = 'WP-LastViewedPosts';

	/**
	 * Determine if storage API is available.
	 *
	 * Source: https://developer.mozilla.org/en-US/docs/Web/API/Web_Storage_API/Using_the_Web_Storage_API
	 *
	 * @param {string} type Storage type to check for.
	 * @returns {boolean} Whether storage is available.
	 */
	function storageAvailable(type) {
		var storage;
		try {
			storage = window[type];
			var x = '__storage_test__';
			storage.setItem(x, x);
			storage.removeItem(x);
			return true;
		}
		catch(e) {
			return e instanceof DOMException && (
				// everything except Firefox
				e.code === 22 ||
				// Firefox
				e.code === 1014 ||
				// test name field too, because code might not be present
				// everything except Firefox
				e.name === 'QuotaExceededError' ||
				// Firefox
				e.name === 'NS_ERROR_DOM_QUOTA_REACHED') &&
				// acknowledge QuotaExceededError only if there's something already stored
				(storage && storage.length !== 0);
		}
	}

	/**
	 * Remove the legacy cookie.
	 */
	function removeCookie() {
		var expires = new Date( 0 ).toGMTString();
		document.cookie = legacyCookieName + '=;expires=' + expires + ';path=' + settings.legacy.path + ';domain=' + settings.legacy.domain;;
	}

	/**
	 * Convert the legacy cookie to local storage.
	 */
	function cookiesToLocalStorage() {
		if ( ! settings.legacy ) {
			return;
		}

		var legacyPosts = settings.legacy.posts;
		var l = legacyPosts.length;
		for ( var i = 0; i < l; i++ ) {
			addPost( legacyPosts[i] );
		}

		removeCookie();
	}

	/**
	 * Get recent posts stored for user.
	 *
	 * @returns {Array} Array of posts stored.
	 */
	function getRecentPosts() {
		var data = localStorage.getItem( localStorageName );
		if ( ! data ) {
			return [];
		}

		try {
			data = JSON.parse( data );
		} catch ( error ) {
			localStorage.removeItem( localStorageName );
			return [];
		}

		if ( data.expiry < Date.now() ) {
			localStorage.removeItem( localStorageName );
			return [];
		}

		return data.posts;
	}

	/**
	 * Store all posts in local storage.
	 *
	 * @param {Array} posts Legacy posts to store in local storage.
	 */
	function setRecentPosts( posts ) {
		var expiry = Date.now() + ( settings.expiry_period * 1000 );
		posts = posts.slice( 0, settings.posts_to_store );

		localStorage.setItem( localStorageName, JSON.stringify( { expiry: expiry, posts: posts } ) );
	}

	/**
	 * Add a new post to local storage.
	 *
	 * Prepend a new post to the list of posts in local storage.
	 *
	 * @param {Object} newPost New post to add.
	 */
	function addPost( newPost ) {
		var savedPosts = getRecentPosts();

		// Remove current post if it exists.
		savedPosts = savedPosts.filter( function( post ) {
			return post.id !== newPost.id;
		} );

		// Add current post to top of list.
		savedPosts.splice( 0, 0, newPost );

		setRecentPosts( savedPosts );
	}

	/**
	 * Add current post to local storage if applicable.
	 *
	 * Determines if the current page ought to be saved and then adds
	 * it to local storage if that is the case.
	 */
	function maybeAddCurrentPost() {
		if ( ! settings.save_url ) {
			return;
		}

		addPost( {
			'id': settings.post_id,
			'title': settings.post_title,
			'url': settings.post_permalink,
		} );
	}

	/**
	 * Display users post in widgets.
	 *
	 * Adds posts to each widget and removes the display:none class if there
	 * are posts to display.
	 */
	function displayRecentlyViewed() {
		var posts = getRecentPosts();
		if ( ! posts.length ) {
			// No posts, nothing to display.
			return;
		}

		var pl = posts.length;
		var li, link, title;
		for ( var i = 0; i < pl; i++ ) {
			li = document.createElement( 'li' );
			link = document.createElement( 'a' );
			link.setAttribute( 'href', posts[i].url );
			title = document.createTextNode( posts[i].title );

			link.appendChild( title );
			li.appendChild( link );
			posts[i].element = li;
		}

		var widgets = document.querySelectorAll( '.am\\.last-viewed-posts\\.display-none' );
		var postClone;
		var widgetList;
		var wl = widgets.length;
		for ( var i = 0; i < wl; i++ ) {
			if ( 'UL' === widgets[i].tagName.toUpperCase() ) {
				widgetList = widgets[i];
			} else {
				widgetList =  widgets[i].querySelector( 'ul.viewed_posts' );
			}
			for ( var j = 0; j < pl; j++ ) {
				postClone = posts[j].element.cloneNode( true );
				widgetList.appendChild( postClone );
				widgets[i].classList.remove( 'am.last-viewed-posts.display-none' );
			}
		}
	}

	if ( ! storageAvailable( 'localStorage' ) ) {
		// User's browser does not support local storage.
		removeCookie();
		return;
	}
	cookiesToLocalStorage();
	maybeAddCurrentPost();
	displayRecentlyViewed();

})( amViewLastPosts.settings, window, document );

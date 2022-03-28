( function( $ ){

	const breakingNews = $('#toptalbn');
	let headerSelector = '#masthead, #site-header, #page-header, .site-header, .site>.header, body>.wp-site-blocks>header>div:first-child';

	// Override default selector from settings.
	if ( 'undefined' !== typeof TOPTALBN.selector && '' !== TOPTALBN.selector ) {
		headerSelector = TOPTALBN.selector;
	}

	// Move breaking news to desired element.
	if ( breakingNews.length ) {
		breakingNews.appendTo( headerSelector );
	}

} )( jQuery );
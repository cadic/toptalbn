( function( $ ){
	const breakingNews = $('#toptalbn');
	
	if ( breakingNews.length ) {
		breakingNews.appendTo('#masthead, #site-header, #page-header, .site-header, .site>.header');
	}

} )( jQuery );
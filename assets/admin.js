( function( $ ){
	// Add Color Picker to all inputs that have 'color-picker' class
	$('.color-picker').wpColorPicker();

	// Add datepicker to expire field
	$.datetimepicker.setLocale('en');
	if ( 'undefined' !== typeof moment ) {
		$.datetimepicker.setDateFormatter('moment');
	}

	$('#toptalbn_expire_at').datetimepicker({});

	$('#toptalbn_expire').on( 'change', function() {
		const checked = $(this).is(':checked');
		$('#toptalbn_expire_at_field').toggle( checked );
	} );

} )( jQuery );
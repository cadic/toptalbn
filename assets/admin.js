( function( $ ){
	$(function() {
		// Add Color Picker to all inputs that have 'color-picker' class
		$('.color-picker').wpColorPicker();

		// Add datepicker to expire field
		$.datetimepicker.setLocale('en');
		$.datetimepicker.setDateFormatter('moment');
		$('#toptalbn_expire_at').datetimepicker();

		$('#toptalbn_expire').on( 'change', function( event ) {
			const checked = $(this).is(':checked');
			$('#toptalbn_expire_at_field').toggle( checked );
		} );
	});
} )( jQuery );
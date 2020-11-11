jQuery(function($) {

	$.ajax({
		method: 'GET',
		url: _spa.api.url,
		beforeSend: function ( xhr ) {
			xhr.setRequestHeader( 'X-WP-Nonce', _spa.api.nonce );
		}
	}).then( function ( r ) {
		if( r.hasOwnProperty( 'settings' ) ){
			$( '#settings' ).val( r.settings );
		}
	});

	$( '#tetris-form' ).on( 'submit', function (e) {
		e.preventDefault();
		var data = {
			settings: $( '#settings' ).val()
		};

		$.ajax({
			method: 'POST',
			url: _spa.api.url,
			beforeSend: function ( xhr ) {
				xhr.setRequestHeader('X-WP-Nonce', _spa.api.nonce);
			},
			data:data
		}).then( function (r) {
			$( '#feedback' ).html( '<p>' + _spa.strings.saved + '</p>' );
		}).error( function (r) {
			var message = _spa.strings.error;
			if( r.hasOwnProperty( 'message' ) ){
				message = r.message;
			}
			$( '#feedback' ).html( '<p>' + message + '</p>' );

		})
	})
});

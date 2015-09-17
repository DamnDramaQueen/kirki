wp.customize.controlConstructor['typography'] = wp.customize.Control.extend( {
	ready: function() {
		var control = this;
		console.log( control );
		var compiled_value = {};

		// get initial values and pre-populate the object
		compiled_value['bold']           = control.setting._value['bold'],
		compiled_value['italic']         = control.setting._value['italic'],
		compiled_value['underline']      = control.setting._value['underline'],
		compiled_value['strikethrough']  = control.setting._value['strikethrough'],
		compiled_value['font-family']    = control.setting._value['font-family'],
		compiled_value['font-size']      = control.setting._value['font-size'],
		compiled_value['font-weight']    = control.setting._value['font-weight'],
		compiled_value['line-height']    = control.setting._value['line-height'],
		compiled_value['letter-spacing'] = control.setting._value['letter-spacing'],

		// bold
		this.container.on( 'change', '.bold input', function() {
			if ( jQuery( this ).is( ':checked' ) ) {
				compiled_value['bold'] = true;
			} else {
				compiled_value['bold'] = false;
			}
			control.setting.set( compiled_value );
			console.log( compiled_value );
		});

		// italic
		this.container.on( 'change', '.italic input', function() {
			if ( jQuery( this ).is( ':checked' ) ) {
				compiled_value['italic'] = true;
			} else {
				compiled_value['italic'] = false;
			}
			control.setting.set( compiled_value );
			console.log( compiled_value );
		});

		// underline
		this.container.on( 'change', '.underline input', function() {
			if ( jQuery( this ).is( ':checked' ) ) {
				compiled_value['underline'] = true;
			} else {
				compiled_value['underline'] = false;
			}
			control.setting.set( compiled_value );
			console.log( compiled_value );
		});

		// strikethrough
		this.container.on( 'change', '.strikethrough input', function() {
			if ( jQuery( this ).is( ':checked' ) ) {
				compiled_value['strikethrough'] = true;
			} else {
				compiled_value['strikethrough'] = false;
			}
			control.setting.set( compiled_value );
			console.log( compiled_value );
		});

		// font-family
		this.container.on( 'change', '.font-family select', function() {
			compiled_value['font-family'] = jQuery( this ).val();
			control.setting.set( compiled_value );
			console.log( compiled_value );
		});

		// font-size
		var font_size_numeric_value = control.container.find('.font-size input[type=number]' ).val();
		var font_size_units_value   = control.container.find('.font-size select' ).val();

		this.container.on( 'change', '.font-size input', function() {
			font_size_numeric_value = jQuery( this ).val();
			compiled_value['font-size'] = font_size_numeric_value + font_size_units_value;
			control.setting.set( compiled_value );
			console.log( compiled_value );
		});
		this.container.on( 'change', '.font-size select', function() {
			font_size_units_value = jQuery( this ).val();
			compiled_value['font-size'] = font_size_numeric_value + font_size_units_value;
			control.setting.set( compiled_value );
			console.log( compiled_value );
		});

		// font-weight
		this.container.on( 'change', '.font-weight select', function() {
			compiled_value['font-weight'] = jQuery( this ).val();
			control.setting.set( compiled_value );
			console.log( compiled_value );
		});

		// line-height
		this.container.on( 'change', '.line-height input', function() {
			compiled_value['line-height'] = jQuery( this ).val();
			control.setting.set( compiled_value );
			console.log( compiled_value );
		});

		// letter-spacing
		var letter_spacing_numeric_value = control.container.find('.letter-spacing input[type=number]' ).val();
		var letter_spacing_units_value   = control.container.find('.letter-spacing select' ).val();

		this.container.on( 'change', '.letter-spacing input', function() {
			letter_spacing_numeric_value = jQuery( this ).val();
			compiled_value['letter-spacing'] = letter_spacing_numeric_value + letter_spacing_units_value;
			control.setting.set( compiled_value );
			console.log( compiled_value );
		});
		this.container.on( 'change', '.letter-spacing select', function() {
			letter_spacing_units_value = jQuery( this ).val();
			compiled_value['letter-spacing'] = letter_spacing_numeric_value + letter_spacing_units_value;
			control.setting.set( compiled_value );
			console.log( compiled_value );
		});

	}
});
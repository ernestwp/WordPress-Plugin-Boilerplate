const $ = jQuery;

class SaveAdminPageSettings {

	constructor(){
		this.addApiFormEventSwitchToggle();
		this.addApiFormEventSettingsSave();
	}

	addApiFormEventSwitchToggle(){

		// Event to turn the module ON nad OFF
		$( '.switch-input' ).on( 'change', ( event ) => {

			let checkbox = event.currentTarget;

			// Disable hthe input while the rest call is being made
			checkbox.disabled = true;

			// Create a pointer to call functions within the ajax
			let _this = this;

			// Collect the data for the rest call
			let restData = {
				class: checkbox.dataset.class,
				checked: checkbox.checked
			};

			$.ajax( {
					method: "POST",
					url: plugin_prefixApiSetup.root + checkbox.dataset.endpoint + '/',
					data: restData,
					// Attach Nonce the the header of the request
					beforeSend: function ( xhr ){
						xhr.setRequestHeader( 'X-WP-Nonce', plugin_prefixApiSetup.nonce );
					}
				} )
				.done( function ( response ){
					if (true === response.success) {
						_this.successNotice( response.message );
					} else {
						_this.errorNotice( response.message );
					}

				} )
				.fail( function ( response ){
					_this.errorNotice( response );
				} )
				.always( function ( response ){
					checkbox.disabled = false;
				} );

		} );
	}

	addApiFormEventSettingsSave(){

		// Event to save settings of a module
		$( '.module-save-settings' ).on( 'click', ( event ) => {

			let button = event.currentTarget;

			button.disabled = true;

			// The form or container of the input data
			let associatedForm = button.closest( '.api-form' );

			// Sometimes wp editor doesn't update the textarea input right away.
			// Let's save iFrame editors to textarea so we can serialize
			if (typeof tinymce !== 'undefined') {
				for (edId in tinyMCE.editors) {
					tinyMCE.editors[edId].save();
				}

			}

			// Create a pointer to call functions within the ajax
			let _this = this;

			// Get the input data from the closet form or contain of user inputs to the clicked element
			let formFields = $( associatedForm ).find( ':input' ).serializeObject();

			// Collect the data for the rest call
			let restData = {
				formFields: formFields,
				class: button.dataset.class
			};

			$.ajax( {
					method: "POST",
					url: plugin_prefixApiSetup.root + button.dataset.endpoint + '/',
					data: restData,
					// Attach Nonce the the header of the request
					beforeSend: function ( xhr ){
						xhr.setRequestHeader( 'X-WP-Nonce', plugin_prefixApiSetup.nonce );
					}
				} )
				.done( function ( response ){
					if (true === response.success) {
						_this.successNotice( response.message );
					} else {
						_this.errorNotice( response.message );
					}

				} )
				.fail( function ( response ){
					_this.errorNotice( response );
				} )
				.always( function ( response ){
					button.disabled = false;
				} );

		} );


	}

	errorNotice( message ){
		console.log( message );
	}

	successNotice( message ){
		console.log( message );
	}

}

/**
 * Extend serialize array to create a serialized object. This is the format that the rest call expects
 *
 * @returns object Returns key value pairs where the key is the input name and the value is the input value
 */
$.fn.serializeObject = function (){

	let o = {};

	let a = this.serializeArray();

	$.each( a, function (){
		if (o[this.name] !== undefined) {
			if (!o[this.name].push) {
				o[this.name] = [o[this.name]];
			}
			o[this.name].push( this.value || '' );
		} else {
			o[this.name] = this.value || '';
		}
	} );

	let $b = this;
	$.each($b, function () {
		if (!o.hasOwnProperty(this.name)) {
			console.log(this.type);
			if ('button' !== this.type) {
				o[this.name] = 'off';
			}
		}
	});

	return o;
};

export default SaveAdminPageSettings;
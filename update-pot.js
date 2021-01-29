const fs    = require( 'fs-extra' );
const wpPot = require( 'wp-pot' );

const pluginSlug = 'testing';
const textDomain = 'TESTING';
const package    = 'testing';

create_pot();

function create_pot(){

	let dir = './i18n/languages';

	// With Promises:
	fs.ensureDir( dir )
		.then( () => {
			console.log( 'success!' );
			update_pot();
		} )
		.catch( err => {
			console.error( err )
		} );
}


function update_pot(){
	wpPot( {
		destFile: './i18n/languages/' + pluginSlug + '.pot',
		domain: textDomain,
		package: package
	} );
}

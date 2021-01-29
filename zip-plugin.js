const fs        = require( 'fs-extra' );
const zipFolder = require( 'zip-folder' );

const pluginSlug = '{plugin_slug}';

create_dir();

function create_dir(){
	// Create a directory where the final zip will be stored
	fs.ensureDir( './zip-package', err => {
		console.log( err ) // => null
		// dir has now been created, including the directory it is to be placed in
	} )

	// Create a temp directory where the needed package files will be stored
	fs.ensureDir( './' + pluginSlug, err => {
		console.log( err ) // => null
		// dir has now been created, including the directory it is to be placed in
	} )

	add_files();
}

function add_files(){


	fs.copy( './README.md', './' + pluginSlug + '/README.md', err => {
		if (err) return console.error( err )

		console.log( 'src directory copied' )
	} )

	fs.copy( './' + pluginSlug + '.php', './' + pluginSlug + '/' + pluginSlug + '.php', err => {
		if (err) return console.error( err )

		console.log( 'src directory copied' )
	} )

	fs.copy( './i18n/languages/', './' + pluginSlug + '/i18n/languages', err => {
		if (err) return console.error( err )

		console.log( 'src directory copied' )
	} )

	fs.copy( './src/', './' + pluginSlug + '/src', err => {
		if (err) return console.error( err )

		console.log( 'src directory copied' )
		zip_folder();
	} )

	// Copy src directory
	// fs.copy('./src/', './' + pluginSlug + '/src')
	//     .then(() => {
	//
	//         // Copy main plugin file
	//         fs.copy('./src/', './' + pluginSlug + '/src')
	//             .then(() => {
	//
	//                 // With Promises:
	//                 fs.remove('./' + pluginSlug + '/src/assets/src')
	//                     .then(() => {
	//                         console.log('success!');
	//                         zip_folder()
	//                     })
	//                     .catch(err => {
	//                         console.error(err)
	//                     })
	//             })
	//             .catch(err => console.error(err))
	//     })
	//     .catch(err => console.error(err))


}

function zip_folder(){
	zipFolder( './' + pluginSlug + '/', './zip-package/' + pluginSlug + '.zip', function ( err ){
		if (err) {
			console.log( 'oh no!', err );
		} else {
			console.log( 'All zipped up a ready to go!' );
		}
	} );
}
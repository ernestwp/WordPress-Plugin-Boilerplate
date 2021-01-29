const inquirer = require( 'inquirer' );
const replace  = require( 'replace-in-file' );
const chalk    = require( 'chalk' );
const fs       = require( 'fs' );
const slugify  = require( 'slugify' );
const log      = console.log;

// The current name of the main boilerplate plugin file
let pluginFile = 'private-plugin-boilerplate.php';

let pluginQuestions = [
	{
		type: 'input',
		name: 'plugin_name',
		message: ' (required) The name of your plugin, which will be displayed in the plugins list in the WordPress Admin. Try to make it unique :)',
		default: '',
	},
	{
		type: 'input',
		name: 'plugin_uri',
		message: 'The home page of the plugin, which should be a unique URL, preferably on your own website. This must be unique to your plugin. ( eg. https://example.com/plugin-name )',
		default: '',
	},
	{
		type: 'input',
		name: 'plugin_description',
		message: 'A short description of the plugin, as displayed in the Plugins section in the WordPress Admin. Keep this description to fewer than 140 characters.',
		default: '',
	},
	{
		type: 'input',
		name: 'plugin_version',
		message: 'The current version number of the plugin, such as 1.0 or 1.0.3.',
		default: '0.1',
	},
	{
		type: 'input',
		name: 'requires_at_least',
		message: 'The lowest WordPress version that the plugin will work on, such as 5.3 or 5.5.',
		default: '5.5',
	},
	{
		type: 'input',
		name: 'requires_php',
		message: 'The minimum required PHP version, such as 5.6 or 7.0.',
		default: '7.0',
	},
	{
		type: 'input',
		name: 'plugin_author',
		message: 'The name of the plugin author. Multiple authors may be listed using commas.',
		default: '',
	},
	{
		type: 'input',
		name: 'plugin_author_uri',
		message: 'The author’s website or profile on another website, such as WordPress.org.',
		default: '',
	},
	{
		type: 'input',
		name: 'plugin_text_domain',
		message: 'The gettext text domain of the plugin to internationalize your Plugin page. Only user lowercase alpha and dash characters. (eg. my-plugin-name )',
		default: '',
	},
	{
		type: 'input',
		name: 'plugin_license',
		message: 'The short name (slug) of the plugin’s license (e.g. GPL-2.0-or-later). More information about licensing can be found in the WordPress.org guidelines.',
		default: 'GPL-2.0-or-later',
	},
	{
		type: 'input',
		name: 'license_uri',
		message: 'A link to the full text of the license (e.g. https://www.gnu.org/licenses/gpl-2.0.html).',
		default: 'https://www.gnu.org/licenses/gpl-2.0.html',
	},
	{
		type: 'input',
		name: 'copyright_year',
		message: `A link to the full text of the license (e.g. ${new Date().getFullYear() + 1}).`,
		default: new Date().getFullYear() + 1,
	},
	{
		type: 'checkbox',
		name: 'readme',
		message: 'Do you need a readme.txt for the WP repo?',
		choices: ['y', 'n'],
		default: 'n',
	},
	{
		type: 'input',
		name: 'plugin_namespace',
		message: 'The namespace for plugin class files. Only user alphanumeric and underscore characters.  (eg. My_Plugin_Name or Author_Name\\My_Plugin_Name)',
		default: '',
	},
	{
		type: 'input',
		name: 'plugin_prefix',
		message: 'The variable used to prefix filters and actions. Only user alphanumeric, hyphens and underscore characters.  (eg. PRFX)',
		default: '',
	},
];

inquirer.prompt( pluginQuestions ).then( answers => {


	log( '' );
	log( 'Initializing plugin creation... ' );
	log( '' );


	if ('' !== answers['plugin_name']) {

		log( chalk.green.underline( 'Creating' ) + ' -> ' + chalk.grey.bgBlue.bold( ' ' + answers['plugin_name'] + ' ' ) );

		let pluginFileName = slugify(
			answers['plugin_name'],
			{
				replacement: '-', // replace spaces with replacement
				remove: /[^a-zA-Z0-9 ]/g, // regex to remove characters
				lower: true // result in lower case
			} );

		let pluginNamespace = toTitleCaseNamespace( answers['plugin_name'] );

		// We are assuming that the plugin's name is unique and we can make a text domain out ot it
		let pluginTextDomain = slugify(
			answers['plugin_name'],
			{
				replacement: '-', // replace spaces with replacement
				remove: /[^a-zA-Z0-9 ]/g, // regex to remove characters
				lower: true  // result in lower case
			} );

		let newPluginFile     = pluginFileName + '.php';
		let oldPluginFileName = pluginFile;
		pluginFile            = newPluginFile;

		if ('n' === answers['readme']) {
			fs.remove( 'readme.txt', err => {
				if (err) return console.error( err );
				log( chalk.red( 'removed: ' ) + 'readme.txt' );
			} )
		}

		let pluginPrefix = slugify(
			answers['plugin_name'],
			{
				replacement: '_', // replace spaces with replacement
				remove: /[^a-zA-Z ]/g, // regex to remove characters
			} );

		pluginPrefix = pluginPrefix.substring(0,4);

		fs.rename( oldPluginFileName, newPluginFile, ( err ) => {
			if (err) throw err;

			try {
				replace( {
					files: [
						'*.*',
						'./src/*.*',
						'./src/classes/**',
						'./src/extensions/**',
						'./src/includes/**',
						'./src/templates/**',
						'./src/utilities/**',
					],
					ignore: 'create-plugin.js',
					from: [
						/{plugin_name}/g,
						/{plugin_description}/g,
						/__plugin_namespace__/g,
						/{plugin_slug}/g,
						/{plugin_text_domain}/g,
						/{plugin_version}/g,
						/{requires_at_least}/g,
						/{requires_php}/g,
						/{plugin_author}/g,
						/{plugin_author_uri}/g,
						/{plugin_uri}/g,
						/{license_uri}/g,
						/{plugin_license}/g,
						/{copyright_year}/g,
						/{plugin_prefix}/g,
					],
					to: [
						answers['plugin_name'],
						answers['plugin_description'],
						( '' === answers['plugin_namespace'] ) ? pluginNamespace : answers['plugin_namespace'],
						pluginFileName,
						pluginTextDomain,
						answers['plugin_version'],
						answers['requires_at_least'],
						answers['requires_php'],
						answers['plugin_author'],
						answers['plugin_author_uri'],
						answers['plugin_uri'],
						answers['license_uri'],
						answers['plugin_license'],
						answers['copyright_year'],
						( '' === answers['plugin_prefix'] ) ? pluginPrefix : answers['plugin_prefix'],

					],
				} );
				log( chalk.green( 'updated: ' ) + 'plugin name' );
				log( chalk.green( 'updated: ' ) + 'plugin description' );
				log( chalk.green( 'updated: ' ) + 'plugin text domain' );
				log( chalk.green( 'updated: ' ) + 'plugin namespace' );
			} catch (error) {
				error( 'Error occurred:', error );
			}

		} );

		// TODO if licensing is not being added then remove both files and change the plugin license to ''
		// TODO if it is then update plugin_license and remove the other license files
		// fs.unlink('LICENSE-GPL-3_0', function (err) {
		//     if (err) throw err;
		//     // if no error, file has been deleted successfully
		//     log(chalk.green('updated: ') + 'plugin licensing');
		// });
	} else {
		log( chalk.red.underline( 'Plugin Creation failed' ) + ' -> ' + chalk.grey.bgBlue.bold( ' Plugin name is required. ' ) );
	}
} );

const toTitleCaseNamespace = ( phrase ) => {

	phrase = slugify(
		phrase,
		{
			replacement: ' ',    // replace spaces with replacement
			remove: /[^a-zA-Z0-9 ]/g,        // regex to remove characters
			lower: true          // result in lower case
		} );

	return phrase
		.toLowerCase()
		.split( ' ' )
		.map( word => word.charAt( 0 ).toUpperCase() + word.slice( 1 ) )
		.join( '_' );
};
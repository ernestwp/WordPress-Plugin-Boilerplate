import './scss/main.scss';
import './js/save-admin-page-settings.js';

import SaveAdminPageSettings from './js/save-admin-page-settings.js';

// Do on DOM ready
document.addEventListener( 'DOMContentLoaded', () => {
	new SaveAdminPageSettings();
} );
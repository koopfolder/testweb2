/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	config.language = 'th';
	// config.uiColor = '#AADC6E';
	config.extraPlugins = 'image';
	var path = CKEDITOR.basePath.split('/');
	path[path.length-2] = 'upload-image';
	//config.filebrowserUploadUrl = path.join('/').replace(/\/+$/, '');
	config.filebrowserUploadUrl = 'http://resourcecenter.thaihealth.or.th/ckeditor/upload-image';
	// Add plugin //
	config.extraPlugins = 'filebrowser';
	console.log(config.filebrowserUploadUrl);
};

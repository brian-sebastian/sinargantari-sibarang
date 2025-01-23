/**
 * @license Copyright (c) 2003-2022, CKSource Holding sp. z o.o. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

	// Image Resize
	// config.extraPlugins = 'imageresize';
	// config.imageResize = { maxWidth : 800, maxHeight : 800 };

	// Enable the plugin by using the extraPlugins configuration setting.
    // Example: 
	// CKEDITOR.config.extraPlugins = "imageresize";

	// config
    // CKEDITOR.config.imageResize.maxWidth = 800;
    // CKEDITOR.config.imageResize.maxHeight = 800;


	// Documentation

 // Resize all images in a node:
    // CKEDITOR.plugins.imageresize.resizeAll(
    //     CKEditor Instance,
    //     (node) parent container,
    //     (integer) max-width,
    //     (integer) max-height
    // );
 
 // Resize one image:
    // CKEDITOR.plugins.imageresize.resize(
    //     CKEditor Instance,
    //     (node) image,
    //     (integer) max-width,
    //     (integer) max-height
    // );
 
 // Detect browser support:
 // returns boolean true or false
    // CKEDITOR.plugins.imageresize.support();
};

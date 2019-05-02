/* eslint-disable */
/**
 * Blacklist blocks
 */
const blacklist = () => {
	const body = document.querySelector( 'body' );

	let disallowedBlocks = [];

	if ( body.classList.contains( 'post-type-post' ) ) {
		disallowedBlocks = [
			'core/verse'
		];
	}

	disallowedBlocks.forEach( block => {
		window.wp.blocks.unregisterBlockType( block );
	} );
};

/**
 * Whitelist blocks
 */
const whitelist = () => {
	let allowedBlocks = [];
	const blockTypes = window.wp.blocks.getBlockTypes();
	const body = document.querySelector( 'body' );

	if ( body.classList.contains( 'post-type-post' ) ) {
		allowedBlocks = [
			'core/paragraph',
			'core/image',
			'core/html',
			'core/freeform'
		];
	}

	blockTypes.forEach( function( blockType ) {
		if ( -1 === allowedBlocks.indexOf( blockType.name ) ) {
			window.wp.blocks.unregisterBlockType( blockType.name );
		}
	} );
};

/**
 * Init
 */
const init = () => {
	window.wp.domReady( () => {
		//blacklist();
		whitelist();
	} );
};

export default init;

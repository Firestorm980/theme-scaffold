// import foo from './components/bar';

const bar = 'foo';

/**
 * Hello
 */
const hello = ( localBar = 'localBar' ) => {
	console.log( 'world', localBar );
};

hello( bar );

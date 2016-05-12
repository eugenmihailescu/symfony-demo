<?php
require_once 'symfony-shell.php';
/**
 * Removes a directory recursively
 *
 * @param string $dir        	
 */
function _rmdir($dir) {
	$files = array_diff ( scandir ( $dir ), array (
			'.',
			'..' 
	) );
	foreach ( $files as $file ) {
		(is_dir ( "$dir/$file" )) ? _rmdir ( "$dir/$file" ) : unlink ( "$dir/$file" );
	}
	return rmdir ( $dir );
}

/**
 * Installs the Composer required components
 */
function composer_install() {
	SymfonyShell\echoTerminaCmd ( SymfonyShell\run_composer ( 'install', array (
			'optimize-autoloader' => null 
	) ) );
}

/**
 * Dumps the Symfony assets
 *
 * @param string $environment
 *        	The Symfony environments (eg. dev, prod, etc)
 */
function symfony_dump_assets($environment = 'prod') {
	SymfonyShell\echoTerminaCmd ( SymfonyShell\run_symfony_console ( 'assetic:dump', array (
			'env' => $environment,
			'no-debug' => null 
	) ) );
}

/**
 * Clears the Symfony cache directory
 *
 * @param string $environment
 *        	The Symfony environment (eg. prod,dev,tests)
 */
function symfony_cache_clear($environment = 'prod') {
	SymfonyShell\echoTerminaCmd ( SymfonyShell\run_symfony_console ( 'cache:clear', array (
			'env' => $environment,
			'no-debug' => null 
	) ) );
}
/**
 * Install the bundle assets to the public (eg.
 * web) directory
 *
 * @param string $environment
 *        	The Symfony environments (eg. dev, prod, etc)
 * @param string $symlink
 *        	When true symlinks the assets otherwise copy them
 * @param string $relative
 *        	When true make relative symlinks
 */
function symfony_assets_install($environment = 'prod', $symlink = false, $relative = false) {
	$args = array (
			'env' => $environment,
			'no-debug' => null 
	);
	
	$symlink && $args ['symlink'] = null;
	$relative && $args ['relative'] = null;
	
	SymfonyShell\echoTerminaCmd ( SymfonyShell\run_symfony_console ( 'assets:install', $args ) );
}

/**
 * This is a custom hook that has nothing to do with Composer/Symfony
 */
function move_vendor_assets() {
	$dir = '/vendor/bower-asset';
	$src = __DIR__ . "$dir";
	$dst = __DIR__ . '/web/bundles' . $dir;
	
	$start = microtime ( true );
	
	$echo = function ($string, $is_error = false) use (&$src, &$dst, &$start) {
		SymfonyShell\echoTerminaCmd ( array (
				sprintf ( 'mv %s %s', $src, $dst ),
				array (
						$is_error ? '' : $string 
				),
				array (
						$is_error ? $string : '' 
				),
				microtime ( true ) - $start 
		) );
	};
	
	if (is_dir ( $src )) {
		is_dir ( $dst ) && _rmdir ( $dst );
		
		mkdir ( $dst, 0770, true ); // create the destination if not exists
		
		if (! rename ( $src, $dst )) {
			$sys_err = error_get_last ();
			$echo ( sprintf ( '%s (%s)', $sys_err ['message'], $sys_err ['type'] ) );
		} else
			$echo ( sprintf ( '%s moved successfully to %s', $src, $dst ) );
	} else
		$echo ( sprintf ( '%s does not exist', $src ) );
}

// register our custom hooks
SymfonyShell\register_hook ( 'composer_install' ); // install the required dependencies as per composer.json
SymfonyShell\register_hook ( 'move_vendor_assets' ); // should be registered before `symfony_dump_assets` hook
SymfonyShell\register_hook ( 'symfony_cache_clear' ); // clear the default (production) cache
SymfonyShell\register_hook ( 'symfony_cache_clear', 'dev' ); // explicitely clear the development cache
SymfonyShell\register_hook ( 'symfony_assets_install', 'prod', true ); // install the bundle assets to the default dir (default "web")
SymfonyShell\register_hook ( 'symfony_dump_assets' ); // dump the assets to the public folder (eg. web)
                                                      
// run the registered hook functions
SymfonyShell\run ();

?>
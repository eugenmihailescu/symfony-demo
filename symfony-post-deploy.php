<?php
require_once 'symfony-shell.php';

/**
 * Recursive copy a file or directory
 *
 * @param string $src        	
 * @param string $dst        	
 * @return bool Returns true on success, false otherwise
 */
function _copy($src, $dst, $mode = 0770) {
	$success = true;
	
	if (is_dir ( $src )) {
		mkdir ( $dst, $mode, true );
		
		$files = scandir ( $src );
		
		foreach ( $files as $file ) {
			if ($file != "." && $file != "..")
				$success &= _copy ( "$src/$file", "$dst/$file" );
		}
	} else if (file_exists ( $src ))
		$success &= copy ( $src, $dst );
	
	return $success;
}

/**
 * Installs the Composer required components
 */
function composer_install() {
	SymfonyShell\echoTerminaCmd ( SymfonyShell\run_composer ( 'install', array (
			'optimize-autoloader' => null,
			'no-interaction' => null 
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
function copy_vendor_assets() {
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
	
	$stat = stat ( $src );// get file info
	if (_copy ( $src, $dst, $stat [2] ))
		$echo ( sprintf ( 'Folder %s copied successfully to %s', str_replace ( __DIR__, '', $src ), str_replace ( __DIR__, '', $dst ) ) );
	else {
		$sys_err = error_get_last ();
		$echo ( sprintf ( '%s (%s)', $sys_err ['message'], $sys_err ['type'] ) );
	}
}

// register our custom hooks
SymfonyShell\register_hook ( 'composer_install' ); // install the required dependencies as per composer.json
SymfonyShell\register_hook ( 'copy_vendor_assets' ); // should be registered before `symfony_dump_assets` hook
SymfonyShell\register_hook ( 'symfony_assets_install', 'prod', true ); // install the bundle assets to the default dir (default "web")
SymfonyShell\register_hook ( 'symfony_dump_assets' ); // dump the assets to the public folder (eg. web)
SymfonyShell\register_hook ( 'symfony_cache_clear' ); // clear the default (production) cache
SymfonyShell\register_hook ( 'symfony_cache_clear', 'dev' ); // explicitely clear the development cache
                                                             
// run the registered hook functions
SymfonyShell\run ();

?>
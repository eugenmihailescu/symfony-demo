<?php
require_once 'symfony-shell.php';

/**
 * Installs the Composer required components
 *
 * @return bool Returns true on success, false otherwise
 */
function composer_install() {
	$output = SymfonyShell\run_composer ( 'install', array (
			'optimize-autoloader' => null,
			'no-interaction' => null 
	) );
	SymfonyShell\echoTerminaCmd ( $output );
	
	return ! $output [4]; // returns the cmd exec exit code
}

// register our custom hooks
SymfonyShell\register_hook ( 'composer_install' ); // install the required dependencies as per composer.json
                                                   
// run the registered hook functions
SymfonyShell\run ();

?>
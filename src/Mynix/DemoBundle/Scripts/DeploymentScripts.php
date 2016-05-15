<?php

namespace Mynix\DemoBundle\Scripts;

use Composer\Script\Event;

class DeploymentScripts extends \Sensio\Bundle\DistributionBundle\Composer\ScriptHandler {
	
	/**
	 * Creates the SQLite demo database
	 *
	 * @param Event $event        	
	 */
	public static function createDemoDatabase(Event $event) {
		$options = static::getOptions ( $event );
		
		$datapath = getcwd () . '/' . $options ['mynix-demo-datapath'];
		
		is_dir ( dirname ( $datapath ) ) || mkdir ( dirname ( $datapath ) );
		
		$consoleDir = static::getConsoleDir ( $event, 'create demo database' );
		
		static::executeCommand ( $event, $consoleDir, 'doctrine:database:create', $options ['process-timeout'] );
		
		if (is_file ( $datapath ) && filesize ( $datapath ))
			$event->getIO ()->write ( sprintf ( ' [SKIP] Database schema already exists at %s', $options ['mynix-demo-datapath'] ) );
		else
			static::executeCommand ( $event, $consoleDir, 'doctrine:schema:create', $options ['process-timeout'] );
	}
}
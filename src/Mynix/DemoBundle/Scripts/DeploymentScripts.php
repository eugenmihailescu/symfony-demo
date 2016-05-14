<?php

namespace Mynix\DemoBundle\Scripts;

use Composer\Script\Event;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;
use Sensio\Bundle\DistributionBundle\Composer as Composer;

class DeploymentScripts extends Composer\ScriptHandler {
	
	/**
	 * Copies the source directory to the destination
	 *
	 * @param string $originDir        	
	 * @param string $targetDir        	
	 * @return bool Returns true on success, false otherwise
	 */
	private static function copy_dir(Event $event, $originDir, $targetDir) {
		$result = false;
		
		$fs = new Filesystem ();
		
		try {
			$fs->mirror ( $originDir, $targetDir );
			$result = true;
		} catch ( \Exception $e ) {
			$event->getIO ()->write ( $e->getMessage () );
		}
		
		return $result;
	}
	
	/**
	 * Copies the vendor dependencies to the `web` public folder
	 *
	 * @param Event $event        	
	 */
	public static function installVendorDependencies(Event $event) {
		$options = static::getOptions ( $event );
		
		$copy_tasks = $options ['mynix-demo-copy-tasks'];
		
		foreach ( $copy_tasks as $copy_taks )
			static::copy_dir ( $event, $copy_taks ['source'], $copy_taks ['destination'] );
		
		$consoleDir = static::getConsoleDir ( $event, 'dump assets' );
		
		static::executeCommand ( $event, $consoleDir, 'assetic:dump', $options ['process-timeout'] );
	}
	
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
		
		if (is_file ( $datapath ))
			$event->getIO ()->write ( sprintf ( ' [SKIP] Database schema already exists at %s', $options ['mynix-demo-datapath'] ) );
		else
			static::executeCommand ( $event, $consoleDir, 'doctrine:schema:create', $options ['process-timeout'] );
	}
}
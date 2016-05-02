<?php

namespace AppBundle\Twig;

use Symfony\Component\Intl\Intl;
use Symfony\Component\HttpFoundation\RequestStack;

class AppExtension extends \Twig_Extension {
	
	/**
	 *
	 * @var array
	 */
	private $locales;
	
	/**
	 *
	 * @var string
	 */
	private $themes_path;
	
	/**
	 *
	 * @var RequestStack
	 */
	private $request_stack;
	
	/**
	 * Constructor
	 *
	 * @param array $locales        	
	 * @param RequestStack $request_stack        	
	 */
	public function __construct($locales, $themes_path, RequestStack $request_stack) {
		$this->locales = $locales;
		
		$this->themes_path = $themes_path;
		
		$this->request_stack = $request_stack;
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 */
	public function getFunctions() {
		return array (
				new \Twig_SimpleFunction ( 'locales', array (
						$this,
						'getLocales' 
				) ),
				new \Twig_SimpleFunction ( 'current_theme', array (
						$this,
						'getCurrentTheme' 
				) ),
				new \Twig_SimpleFunction ( 'installed_themes', array (
						$this,
						'getInstalledThemes' 
				) ) 
		);
	}
	
	/**
	 * Takes the list of codes of the locales (languages) enabled in the
	 * application and returns an array with the name of each locale written
	 * in its own language (e.g.
	 * English, Français, Español, etc.)
	 *
	 * @return array
	 */
	public function getLocales() {
		$localeCodes = explode ( '|', $this->locales );
		
		$locales = array ();
		foreach ( $localeCodes as $localeCode ) {
			$locales [] = array (
					'code' => $localeCode,
					'name' => Intl::getLocaleBundle ()->getLocaleName ( $localeCode, $localeCode ) 
			);
		}
		
		return $locales;
	}
	/**
	 * Return the current them
	 *
	 * @return string
	 */
	public function getCurrentTheme() {
		$request = $this->request_stack->getCurrentRequest ();
		
		return $request->attributes->get ( '_theme' );
	}
	
	/**
	 * Returns the installed themes
	 * 
	 * @return array
	 */
	public function getInstalledThemes() {
		return array_map ( 'basename', glob ( $this->themes_path . '*', GLOB_ONLYDIR ) );
	}
	
	/**
	 *
	 * {@inheritdoc}
	 *
	 */
	public function getName() {
		// the name of the Twig extension must be unique in the application. Consider
		// using 'app.extension' if you only have one Twig extension in your application.
		return 'app.extension';
	}
}

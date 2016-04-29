<?php

namespace AppBundle\Twig;

use Symfony\Component\Intl\Intl;

class AppExtension extends \Twig_Extension {
	
	/**
	 *
	 * @var array
	 */
	private $locales;
	public function __construct($locales) {
		$this->locales = $locales;
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

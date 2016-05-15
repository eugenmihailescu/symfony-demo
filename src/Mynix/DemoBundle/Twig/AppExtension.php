<?php

namespace Mynix\DemoBundle\Twig;

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
	 * @var unknown
	 */
	private $theme_css;
	
	/**
	 *
	 * @var string
	 */
	private $default_theme;
	
	/**
	 *
	 * @var RequestStack
	 */
	private $request_stack;
	
	/**
	 * Constructor
	 *
	 * @param array $locales        	
	 * @param string $theme_css
	 *        	@oaram string $default_theme
	 * @param RequestStack $request_stack        	
	 */
	public function __construct($locales, $theme_css, $default_theme, RequestStack $request_stack) {
		$this->locales = $locales;
		
		$this->theme_css = $theme_css;
		
		$this->default_theme = $default_theme;
		
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
				) ),
				new \Twig_SimpleFunction ( 'current_theme_css', array (
						$this,
						'getThemeCSS' 
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
		
		// in case the _theme is not sent the try to parse it from request URI
		if (! $request->attributes->has ( '_theme' ) || empty ( $request->attributes->get ( '_theme' ) )) {
			$matches = null;
			
			if (preg_match ( '/(\.php)?\/\w+\/(\w+).*/i', $request->server->get ( 'REQUEST_URI' ), $matches ))
				// is the found theme among the installed themes?
				if (in_array ( $matches [2], $this->getInstalledThemes () ))
					return $matches [2];
			
			return $this->default_theme;
		}
		
		return $request->attributes->get ( '_theme' );
	}
	
	/**
	 * Returns the installed themes
	 *
	 * @return array
	 */
	public function getInstalledThemes() {
		$bootswatch_themes = array (
				'cerulean',
				'cosmo',
				'cyborg',
				'darkly',
				'flatly',
				'journal',
				'lumen',
				'paper',
				'readable',
				'sandstone',
				'simplex',
				'slate',
				'spacelab',
				'superhero',
				'united',
				'yeti' 
		);
		return $bootswatch_themes;
	}
	
	/**
	 * Returns the themes root global parameter
	 *
	 * @return string
	 */
	public function getThemeCSS() {
		return sprintf ( 'https://maxcdn.bootstrapcdn.com/bootswatch/3.3.6/%s/bootstrap.min.css', $this->getCurrentTheme () );
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

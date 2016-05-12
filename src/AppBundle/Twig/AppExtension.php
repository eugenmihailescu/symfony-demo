<?php

namespace AppBundle\Twig;

use Symfony\Component\Intl\Intl;
use Symfony\Component\HttpFoundation\RequestStack;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
	private $themes_search_pattern;
	
	/**
	 *
	 * @var string
	 */
	private $themes_root;
	
	/**
	 *
	 * @var unknown
	 */
	private $theme_css;
	
	/**
	 *
	 * @var RequestStack
	 */
	private $request_stack;
	
	/**
	 * Constructor
	 *
	 * @param array $locales        	
	 * @param string $themes_search_pattern        	
	 * @param string $themes_root        	
	 * @param string $theme_css        	
	 * @param RequestStack $request_stack        	
	 */
	public function __construct($locales, $themes_search_pattern, $themes_root, $theme_css, RequestStack $request_stack) {
		$this->locales = $locales;
		
		$this->themes_search_pattern = $themes_search_pattern;
		
		$this->themes_root = $themes_root;
		
		$this->theme_css = $theme_css;
		
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
		
		return $request->attributes->get ( '_theme' );
	}
	
	/**
	 * Returns the installed themes
	 *
	 * @return array
	 */
	public function getInstalledThemes() {
		return array_map ( 'basename', array_map ( 'dirname', glob ( $this->themes_search_pattern ) ) );
	}
	
	/**
	 * Returns the themes root global parameter
	 *
	 * @return string
	 */
	public function getThemeCSS() {
		return $this->themes_root . '/' . $this->getCurrentTheme () . '/' . $this->theme_css;
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

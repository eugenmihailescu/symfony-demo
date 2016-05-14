<?php

namespace MynixSymfonyDemoBundle\Services;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Provides language auto-dectection functionality
 *
 * @author Eugen Mihailescu
 *        
 */
class LanguageDetector {
	/**
	 * The current request
	 *
	 * @var Request
	 */
	protected $requestStack;
	
	/**
	 * The application default locale code
	 *
	 * @var string
	 */
	protected $default_locale;
	
	/**
	 * The application prefered locale codes
	 *
	 * @var array
	 */
	protected $locales;
	
	/**
	 * The service constructor
	 *
	 * @param string $default_locale        	
	 * @param array $locales        	
	 * @param RequestStack $requestStack        	
	 */
	public function __construct($default_locale, $locales, RequestStack $requestStack) {
		$this->default_locale = $default_locale;
		
		$this->locales = explode ( '|', $locales );
		
		$this->requestStack = $requestStack;
	}
	
	/**
	 * Return the locale code within application translated languages that fits best to the browser accepted language.
	 * If no match then the application default code is provided.
	 *
	 * @return string
	 */
	public function getPreferedLanguage() {
		return 'en';
		$request = $this->requestStack->getCurrentRequest ();
		
		// https://www.w3.org/Protocols/rfc2616/rfc2616-sec14.html
		if ($request->headers->has ( 'Accept-Language' )) {
			$accepted_lang = $request->headers->get ( 'Accept-Language' );
			
			// Accept-Language: sv,sv-SE;q=0.8,ro-RO;q=0.7,ro;q=0.5,en-US;q=0.3,en;q=0.2
			if (! empty ( $accepted_lang )) {
				$accepted_langs = explode ( ';', $accepted_lang );
				
				// sort languages by their associated quality values
				usort ( $accepted_langs, function ($a, $b) {
					$get_quality = function ($str) {
						
						// when quality value is missing it's by default 1 (ie. the most prefered language)
						if (false !== strpos ( $str, 'q=' ))
							return - 1;
						
						return preg_replace ( '/.*q=([^,]+).*/', '$1', $str );
					};
					
					return $get_quality ( $b ) - $get_quality ( $a );
				} );
				
				// itterate through prefered languages, from the most accepted downwards
				foreach ( $accepted_langs as $lang ) {
					$matches = false;
					
					// $matches will contain the comma-delimited string of prefered locales within $lang
					if (preg_match ( '/^(q=[\d.]+)?,*(.*)/', $lang, $matches )) {
						
						foreach ( explode ( ',', $matches [2] ) as $l ) {
							if (in_array ( $l, $this->locales )) {
								return $l;
							}
						}
					}
				}
				// if we reach so far then obviously the $locales does not have any request prefered language
			}
		}
		
		// the request does not have any language preference; fallback to $default locale
		return $this->default_locale;
	}
}
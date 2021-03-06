<?php

namespace Mynix\DemoBundle\EventListener;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RedirectToPreferredLocaleListener {
	/**
	 *
	 * @var UrlGeneratorInterface
	 */
	private $urlGenerator;
	
	/**
	 * List of supported locales.
	 *
	 * @var string[]
	 */
	private $locales = array ();
	
	/**
	 *
	 * @var string
	 */
	private $defaultLocale = '';
	
	/**
	 *
	 * @var string
	 */
	private $theme = '';
	
	/**
	 * Constructor.
	 *
	 * @param UrlGeneratorInterface $urlGenerator        	
	 * @param string $locales
	 *        	Supported locales separated by '|'
	 * @param string|null $defaultLocale        	
	 */
	public function __construct(UrlGeneratorInterface $urlGenerator, $locales, $defaultLocale = null, $theme = null) {
		$this->urlGenerator = $urlGenerator;
		
		$this->locales = explode ( '|', trim ( $locales ) );
		if (empty ( $this->locales )) {
			throw new \UnexpectedValueException ( 'The list of supported locales must not be empty.' );
		}
		
		$this->defaultLocale = $defaultLocale ?: $this->locales [0];
		
		if (! in_array ( $this->defaultLocale, $this->locales )) {
			throw new \UnexpectedValueException ( sprintf ( 'The default locale ("%s") must be one of "%s".', $this->defaultLocale, $locales ) );
		}
		
		$this->theme = $theme;
		
		// Add the default locale at the first position of the array,
		// because Symfony\HttpFoundation\Request::getPreferredLanguage
		// returns the first element when no an appropriate language is found
		array_unshift ( $this->locales, $this->defaultLocale );
		$this->locales = array_unique ( $this->locales );
	}
	
	/**
	 *
	 * @param GetResponseEvent $event        	
	 */
	public function onKernelRequest(GetResponseEvent $event) {
		$request = $event->getRequest ();
		
		// Ignore sub-requests and all URLs but the homepage
		if (! $event->isMasterRequest () || '/' !== $request->getPathInfo ()) {
			return;
		}
		// Ignore requests from referrers with the same HTTP host in order to prevent
		// changing language for users who possibly already selected it for this application.
		if (0 === stripos ( $request->headers->get ( 'referer' ), $request->getSchemeAndHttpHost () )) {
			return;
		}
		
		$preferredLanguage = $request->getPreferredLanguage ( $this->locales );
		
		if ($preferredLanguage !== $this->defaultLocale || 'flatly' !== $this->theme) {
			$response = new RedirectResponse ( $this->urlGenerator->generate ( 'homepage', array (
					'_locale' => $preferredLanguage,
					'_theme' => $this->theme 
			) ) );
			
			$event->setResponse ( $response );
		}
	}
}

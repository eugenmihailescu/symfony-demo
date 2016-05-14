<?php

namespace Mynix\DemoBundle\Controller;

use Symfony\Bundle\TwigBundle\Controller\ExceptionController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpKernel\Log\DebugLoggerInterface;
use Symfony\Component\HttpFoundation\Response;

class ExceptionController extends ExceptionController {
	protected function findTemplate(Request $request, $format, $code, $showException) {
		return 'MynixDemoBundle:error:error.html.twig';
	}
	public function showAction(Request $request, FlattenException $exception, DebugLoggerInterface $logger = null) {
		$currentContent = $this->getAndCleanOutputBuffering ( $request->headers->get ( 'X-Php-Ob-Level', - 1 ) );
		$showException = $request->attributes->get ( 'showException', $this->debug );
		
		$code = $exception->getStatusCode ();
		
		return new Response ( $this->twig->render ( ( string ) $this->findTemplate ( $request, $request->getRequestFormat (), $code, $showException ), array (
				'is_exception' => $showException,
				'status_code' => $code,
				'status_text' => isset ( Response::$statusTexts [$code] ) ? Response::$statusTexts [$code] : '',
				'status_description' => $exception->getMessage (),
				'file' => $exception->getFile (),
				'lineno' => $exception->getLine (),
				'exception' => $exception,
				'trace' => print_r ( $exception->getTrace (), 1 ),
				'logger' => $logger,
				'currentContent' => $currentContent 
		) ) );
	}
}
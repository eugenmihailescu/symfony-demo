<?php

namespace Mynix\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/*
 * class SecurityController extends Controller {
 * public function loginAction(Request $request) {
 * $authenticationUtils = $this->get ( 'security.authentication_utils' );
 *
 * // get the login error if there is one
 * $error = $authenticationUtils->getLastAuthenticationError ();
 *
 * // last username entered by the user
 * $lastUsername = $authenticationUtils->getLastUsername ();
 *
 * return $this->render ( 'MynixDemoBundle:security:login.html.twig', array (
 * // last username entered by the user
 * 'last_username' => $lastUsername,
 * 'error' => $error
 * ) );
 * }
 * public function logoutAction(Request $request) {
 * return $this->redirectToRoute ( 'login' );
 * }
 * }
 */
class SecurityController extends GenericController {
	/**
	 * This is the route the users are redirected when they need to login.
	 * This will renders the login form.
	 *
	 * @Route("/login", name="security_login_form")
	 * @Method("GET")
	 */
	public function loginAction() {
		$helper = $this->get ( 'security.authentication_utils' );
		
		return $this->render ( 'MynixDemoBundle:security:login.html.twig', array (
				// last username entered by the user (if any)
				'last_username' => $helper->getLastUsername (),
				// last authentication error (if any)
				'error' => $helper->getLastAuthenticationError () 
		) );
	}
	
	/**
	 * This is the route the login form submits to.
	 *
	 * But, this will never be executed. Symfony will intercept this first
	 * and handle the login automatically. See form_login in app/config/security.yml
	 *
	 * @Route("/login_check", name="security_login_check")
	 */
	public function loginCheckAction() {
		throw new \Exception ( 'This should never be reached!' );
	}
	
	/**
	 * This is the route the user can use to logout.
	 *
	 * But, this will never be executed. Symfony will intercept this first
	 * and handle the logout automatically. See logout in app/config/security.yml
	 *
	 * @Route("/logout", name="security_logout")
	 */
	public function logoutAction() {
		throw new \Exception ( 'This should never be reached!' );
	}
}
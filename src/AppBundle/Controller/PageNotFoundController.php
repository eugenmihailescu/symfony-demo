<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageNotFoundController extends Controller {
	public function pageNotFoundAction($path) {
		return $this->redirectToRoute ( 'homepage' );
	}
}
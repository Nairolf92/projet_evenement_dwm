<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Session\Session;
use AdminBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;



class DefaultController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function indexAction()
    {
    	$session = new Session();
    	if($session->get('allowed') == false){
        	return $this->redirectToRoute('login');
    	}
    	// redirect to the "login" route if the user isnt set
    	return $this->redirectToRoute('users');
    }

	/**
     * @Route("/admin/users", name="users")
     */
    public function usersAction()
    {
		$repository = $this
		  ->getDoctrine()
		  ->getManager()
		  ->getRepository('AdminBundle:User')
		;
		$listUsers = $repository->findAll();
		return $this->render('AdminBundle:Default:users.html.twig', array(
   			'users' => $listUsers,
		));
    }

 	/**
     * @Route("/admin/login", name="login")
     */
    public function loginAction()
    {
    	$session = new Session();
    	//connexion
    	if($session->get('allowed') == true)
    	{
			return $this->redirectToRoute('admin');
    	}
    	$session->set('allowed', true);
        $allowed = $session->get('allowed');

    	return $this->render('AdminBundle:Default:login.html.twig', array(
   			'allowed' => $allowed,
		));
    }
}

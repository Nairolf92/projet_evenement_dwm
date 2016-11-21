<?php

namespace AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Session\Session;
use AdminBundle\Entity\User;
use AdminBundle\Entity\Admin;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;


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
    	// CHECK IF SESSION IS PRESENT
    	$session = new Session();
    	if($session->get('allowed') == false)
    	{
			return $this->redirectToRoute('admin');
    	}

		// GET ALL USERS
		$repository = $this
		  ->getDoctrine()
		  ->getManager()
		  ->getRepository('AdminBundle:User')
		;
		$listUsers = $repository->findAll();
	/*	var_dump($listUsers);*/
		// SEND VIEW
		return $this->render('AdminBundle:Default:users.html.twig', array(
   			'users' => $listUsers,
		));
    }

	/**
     * @Route("/admin/logout", name="logout")
     */
    public function logoutAction()
    {
    	$session = new Session();
    	if($session->get('allowed') == true) {
    		$session->set('allowed', false);
    	}  	
    	return $this->redirectToRoute('admin');
    }

 	/**
     * @Route("/admin/login", name="login")
     */
    public function loginAction(Request $request)
    {
    	// PARTIE SESSION
    	$session = new Session();
    	if($session->get('allowed') == true)
    	{
			return $this->redirectToRoute('admin');
    	}
    	//$session->set('allowed', false);
        //$allowed = $session->get('allowed');

        // PARTIE FORMULAIRE
    	$admin = new Admin();
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $admin);

        // On ajoute les champs de l'entité que l'on veut à notre formulaire
        $formBuilder
            ->add('login',     TextType::class)
            ->add('password',     TextType::class)
            ->add('save',      SubmitType::class)
        ;

        $form = $formBuilder->getForm();

        if ($request->isMethod('POST')) {
          	$form->handleRequest($request);
          	if ($form->isValid()) {
          		$repository = $this
				  ->getDoctrine()
				  ->getManager()
				  ->getRepository('AdminBundle:Admin')
				;
          		$admin = $repository->findOneBy(array(
          			'login' => $form["login"]->getData(),
          			'password' => $form["password"]->getData()
          		));
          		if(!null == $admin){
          			$session->set('allowed', true);
          			return $this->redirectToRoute('users');
          		}else{
          			return $this->render('AdminBundle:Default:login.html.twig', array(
		   			'form' => $form->createView(),
		   			'essai' => 'true',
		   			'nb_essai'=> '1'
					));
          		}
          	}
        }

    	return $this->render('AdminBundle:Default:login.html.twig', array(
   			'form' => $form->createView(),
   			'essai' => 'false',
   			'nb_essai'=> '0'
		));
    }
}

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
    public function loginAction(Request $request)
    {
    	// PARTIE SESSION
    	$session = new Session();
    	
    	/*if($session->get('allowed') == true)
    	{
			return $this->redirectToRoute('admin');
    	}*/
    	$session->set('allowed', false);
        $allowed = $session->get('allowed');

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

          	}
        }

    	return $this->render('AdminBundle:Default:login.html.twig', array(
   			'allowed' => $allowed,
   			'form' => $form->createView()
		));
    }
}

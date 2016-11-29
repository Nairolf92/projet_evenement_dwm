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
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
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
		$listUsers = $repository->findBy(
			array('deleted' => 0)
			);

		dump($listUsers);
		// SEND VIEW
		return $this->render('AdminBundle:Default:users.html.twig', array(
   			'users' => $listUsers,
		));
    }

    public function modifyAction(Request $request, $id, $gender, $firstName, $name, $zipcode, $birthDate, $email, $date,$status, $device, $visited)
    {
    	$user = new User();

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $user);
        $formBuilder
            ->add('name',     TextType::class)
            ->add('first_name',   TextType::class)
            ->add('zipcode',   TextType::class)
            ->add('gender', CheckboxType::class)
            ->add('gender', ChoiceType::class, array(
                'choices'  => array(
                    'Monsieur' => true,
                    'Madame' => false,
                ),
            ))
            ->add('device', ChoiceType::class, array(
                'choices'  => array(
                    'PC' => true,
                    'Console' => false,
                ),
            ))
            ->add('visited', ChoiceType::class, array(
                'choices'  => array(
                    'Oui' => true,
                    'Non' => false,
                ),
            ))
            ->add('birth_date', dateType::class)
            ->add('email', TextType::class)
            ->add('save',      SubmitType::class)
        ;

        $form = $formBuilder->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isValid()) {
              $em = $this->getDoctrine()->getManager();
/*              $user->setDeleted(1);*/
			  $em = $this->getDoctrine()->getManager();
			  $em->persist($user);
	          $em->flush();
              $em->persist($user);
              $em->flush();
            }
            return $this->redirectToRoute('users');
        }
    	return $this->render('AdminBundle:Default:modify.html.twig', array(
    		'form' => $form->createView(),
    		'id' => $id,
    		'firstName' => $firstName,
    		'name' => $name,
    		'zipcode' => $zipcode,
    		'birthDate' => $birthDate,
    		'email' => $email,
    		'date' => $date,
    		'status' => $status,
    		'device' => $device,
    		'visited' => $visited
    		));
    }

    public function deleteAction($id){
	    $user = $this->getDoctrine()
		    ->getRepository('AdminBundle:User')
		    ->find($id);

		if(!null == $user)
			$user->setDeleted(1);
			$em = $this->getDoctrine()->getManager();
			$em->persist($user);
           	$em->flush();
	    return $this->redirectToRoute('users');
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
            ->add('password',     PasswordType::class)
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

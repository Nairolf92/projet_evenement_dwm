<?php

namespace AppBundle\Controller;

use AdminBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class DefaultController extends Controller
{
    /**
     * @Route("/public", name="homepage")
     */
    public function indexAction(Request $request)
    {

        /*$user->setGender(1);
        $user->setName('Kelnerowski');
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();*/

        // create a task and give it some dummy data for this example
        $user = new User();

        // On crée le FormBuilder grâce au service form factory
        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $user);

        // On ajoute les champs de l'entité que l'on veut à notre formulaire
        $formBuilder
            ->add('name',     TextType::class)
            ->add('first_name',   TextareaType::class)
            ->add('gender', CheckboxType::class)
            ->add('gender', CheckboxType::class)
            ->add('save',      SubmitType::class)
            ->add('gender', ChoiceType::class, array(
                'choices'  => array(
                    'Homme' => true,
                    'Femme' => false,
                ),
            ))
        ;

        $form = $formBuilder->getForm();

        if ($request->isMethod('POST')) {
          	$form->handleRequest($request);
          	if ($form->isValid()) {
          	  // On enregistre notre objet $advert dans la base de données, par exemple
          		$em = $this->getDoctrine()->getManager();
          	  	$em->persist($user);
           	 	$em->flush();
          	}
        }

        return $this->render('AppBundle:Default:index.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}

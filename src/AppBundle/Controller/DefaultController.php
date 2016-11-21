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
        $user = new User();

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $user);
        $formBuilder
            ->add('name',     TextType::class)
            ->add('first_name',   TextType::class)
            ->add('zipcode',   TextType::class)
            ->add('gender', CheckboxType::class)
            ->add('gender', ChoiceType::class, array(
                'choices'  => array(
                    'Homme' => true,
                    'Femme' => false,
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

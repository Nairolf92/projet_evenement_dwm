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

        return $this->render('AppBundle:Default:index.html.twig');
    }

    /**
     * @Route("/public/inscription", name="register")
     */
    public function registerAction(Request $request)
    {
        $user = new User();

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $user);
        $formBuilder
            ->add('name',     TextType::class, array(
                'required'    => true,
            ))
            ->add('first_name',   TextType::class, array(
                'required'    => true,
            ))
            ->add('zipcode',   TextType::class, array(
                'required'    => true,
            ))
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
            ->add('birth_date', dateType::class, array(
                'widget' => 'choice',
                'years' => range(1910,2012)))
            ->add('email', TextType::class, array(
                'required'    => true,
            ))
            ->add('Valider',     SubmitType::class)
        ;

        $form = $formBuilder->getForm();

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
            }
            return $this->redirectToRoute('homepage');
        }
        return $this->render('AppBundle:Default:register.html.twig', array(
          'form' => $form->createView(),
        ));
    }
}

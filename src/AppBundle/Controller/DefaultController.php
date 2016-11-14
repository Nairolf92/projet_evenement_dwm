<?php

namespace AppBundle\Controller;

use AdminBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/public", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $user = new User();
        $user->setGender(1);
        $user->setName('Kelnerowski');
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return $this->render('AppBundle:Default:index.html.twig');

    }
}

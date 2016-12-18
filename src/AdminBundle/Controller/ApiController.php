<?php

namespace AdminBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AdminBundle\Entity\User;

class ApiController extends Controller
{
    /**
     * @Route("/utilisateurs", name="utilisateurs")
     * @Method({"GET"})
     */
    public function utilisateursAction(Request $request)
    {
        $users = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AdminBundle:User')
            ->findAll();

        $formatted = [];
        foreach ($users as $user) {
            $formatted[] = [
                'id' => $user->getId(),
                'gender' => $user->getGender(),
                'name' => $user->getName(),
                'firstName' => $user->getFirstName(),
                'password' => $user->getPassword(),
                'zipcode' => $user->getZipcode(),
                'address' => $user->getAddress(),
                'city' => $user->getCity(),
                'birthDate' => $user->getBirthDate(),
                'email' => $user->getEmail(),
                'device' => $user->getDevice(),
                'visited' => $user->getVisited(),
                'date' => $user->getDate(),
                'status' => $user->getStatus(),
                'deleted' => $user->getDeleted(),
            ];
        }

        return new JsonResponse($formatted);
    }

    /**
     * @Route("/utilisateurs/{id}", name="utilisateurs_seul")
     * @Method({"GET"})
     */
    public function utilisateurAction(Request $request)
    {
        $user = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AdminBundle:User')
            ->find($request->get('id'));

        if (empty($user)) {
            return new JsonResponse(['message' => 'Utilisateur introuvable'], Response::HTTP_NOT_FOUND);
        }

        $formatted = [
            'id' => $user->getId(),
            'gender' => $user->getGender(),
            'name' => $user->getName(),
            'firstName' => $user->getFirstName(),
            'password' => $user->getPassword(),
            'zipcode' => $user->getZipcode(),
            'address' => $user->getAddress(),
            'city' => $user->getCity(),
            'birthDate' => $user->getBirthDate(),
            'email' => $user->getEmail(),
            'device' => $user->getDevice(),
            'visited' => $user->getVisited(),
            'date' => $user->getDate(),
            'status' => $user->getStatus(),
            'deleted' => $user->getDeleted(),
        ];

        return new JsonResponse($formatted);
    }

    /**
     * @Route("/utilisateurs/{id}/valid", name="utilisateurs_seul_valid")
     * @Method({"PUT"})
     */
    public function validAction(Request $request)
    {
        $user = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AdminBundle:User')
            ->find($request->get('id'));

        if (empty($user)) {
            return new JsonResponse(['message' => 'Utilisateur introuvable'], Response::HTTP_NOT_FOUND);
        }

        if($user->getStatus() == 1)
        {
            return new JsonResponse(['message' => 'Inscription de l\'utilisateur déjà acceptée '], Response::HTTP_BAD_REQUEST);
        }
        if($user->getStatus() == 2)
        {
            return new JsonResponse(['message' => 'Inscription de l\'utilisateur déjà refusée, impossible de l\'accepter '], Response::HTTP_BAD_REQUEST);
        }
        $em = $this->get('doctrine.orm.entity_manager');
        $user->setStatus(1);
        $em->merge($user);
        $em->flush();
        return new JsonResponse(['message' => 'Inscription de l\'utilisateur acceptée'], Response::HTTP_OK);
    }

    /**
     * @Route("/utilisateurs/{id}/refuse", name="utilisateurs_seul_refuse")
     * @Method({"PUT"})
     */
    public function refuseAction(Request $request)
    {
        $user = $this->get('doctrine.orm.entity_manager')
            ->getRepository('AdminBundle:User')
            ->find($request->get('id'));

        if (empty($user)) {
            return new JsonResponse(['message' => 'Utilisateur introuvable'], Response::HTTP_NOT_FOUND);
        }

        if($user->getStatus() == 1)
        {
            return new JsonResponse(['message' => 'Inscription de l\'utilisateur déjà acceptée, impossible de la refuser '], Response::HTTP_BAD_REQUEST);
        }
        if($user->getStatus() == 2)
        {
            return new JsonResponse(['message' => 'Inscription de l\'utilisateur déjà refusée '], Response::HTTP_BAD_REQUEST);
        }
        $em = $this->get('doctrine.orm.entity_manager');
        $user->setStatus(2);
        $em->merge($user);
        $em->flush();
        return new JsonResponse(['message' => 'Inscription de l\'utilisateur refusée'], Response::HTTP_OK);
    }

    /**
     * @Route("/inscription", name="utilisateurs_post")
     * @Method({"POST"})
     */
    public function postUtilisateursAction(Request $request)
    {
        $user = new User();
        $user
            ->setName($request->get('name'))
            ->setAddress($request->get('address'));

        $em = $this->get('doctrine.orm.entity_manager');
        $em->persist($user);
        $em->flush();

        $formatted = [
            'id' => $user->getId(),
            'gender' => $user->getGender(),
            'name' => $user->getName(),
            'firstName' => $user->getFirstName(),
            'password' => $user->getPassword(),
            'zipcode' => $user->getZipcode(),
            'address' => $user->getAddress(),
            'city' => $user->getCity(),
            'birthDate' => $user->getBirthDate(),
            'email' => $user->getEmail(),
            'device' => $user->getDevice(),
            'visited' => $user->getVisited(),
            'date' => $user->getDate(),
            'status' => $user->getStatus(),
            'deleted' => $user->getDeleted(),
        ];

        return new JsonResponse($formatted);
    }
}
<?php

// src/AdminBundle/Controller/ApiController.php

namespace AdminBundle\Controller;

use AdminBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;



class ApiController extends Controller
{
    public function indexAction($data)
    {
    	$encoders = array(new XmlEncoder(), new JsonEncoder());
		$normalizers = array(new ObjectNormalizer());
		$serializer = new Serializer($normalizers, $encoders);
    	$user = $serializer->deserialize($data['data'],'Moodress\Bundle\AdminBundle\Entity\User','json');
    	$data = json_decode($yourJsonFile, true);
        return $user;
    }
}
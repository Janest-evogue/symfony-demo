<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/doctrine")
 */
class DoctrineController extends Controller
{
    /**
     * @Route("/")
     */
    public function index()
    {
        // gestionnaire d'entités de Doctrine
        $em = $this->getDoctrine()->getManager();
        // Retourne un objet User dont les attributs
        // sont settés à partir de la bdd
        // User avec l'id 1
        $user = $em->find(User::class, 1);
        
        return $this->render(
            'doctrine/index.html.twig',
            [
                'user' => $user
            ]);
    }
}

<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    /**
     * @Route("/")
     */
    public function index()
    {
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
    
    /**
     * @Route("/hello")
     */
    public function hello()
    {
        return $this->render('index/hello.html.twig');
    }
    
    /**
     * @Route("/bonjour/{qui}")
     */
    public function bonjour($qui)
    {
        return $this->render(
            'index/bonjour.html.twig',
            [
                'qui' => $qui
            ]
        );
    }
    
    /**
     * 
     * @Route("/salut/{qui}", defaults={"qui": "toi"})
     * @param string $qui
     */
    public function salut($qui)
    {
        return $this->render(
            'index/salut.html.twig',
            [
                'qui' => $qui
            ]
        );
    }
    
    /**
     * @Route("/coucou/{firstname}-{lastname}", defaults={"lastname": ""})
     */
    public function coucou($firstname, $lastname)
    {
        $name = $firstname;
        
        if ($lastname != '') {
            $name .= ' ' . $lastname;
        }
        
        return $this->render(
            'index/coucou.html.twig',
            [
                'name' => $name
            ]
        );
    }
    
    /**
     * @Route("/twig")
     */
    public function twig()
    {
        return $this->render(
            'index/twig.html.twig',
            [
                'auj' => new \DateTime()
            ]
        );
    }
}

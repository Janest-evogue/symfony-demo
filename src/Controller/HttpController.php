<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Préfixe de route pour toutes les méthodes du contrôleur
 * @Route("/http")
 */
class HttpController extends Controller
{
    /**
     * @Route("/")
     */
    public function index()
    {
        return $this->render('http/index.html.twig', [
            'controller_name' => 'HttpController',
        ]);
    }
    
    /**
     * @Route("/request")
     */
    public function request(Request $request)
    {
        // var_dump($_GET['name']);
        dump($request->query->get('name'));
        // var_dump($_GET);
        dump($request->query->all());
        
        // GET ou POST
        dump($request->getMethod());
        
        if ($request->isMethod('POST')) {
            echo 'Le formulaire a été envoyé';
            // var_dump($_POST['nom']);
            dump($request->request->get('nom'));
            // var_dump($_POST);
            dump($request->request->all());
        }
        
        if (!$request->isXmlHttpRequest()) {
            echo "<p>La page n'a pas été appelée en ajax</p>";
        }
        
        return $this->render('http/request.html.twig');
    }
    
    /**
     * @Route("/response")
     */
    public function response(Request $request)
    {
        // renvoie du texte brut
        $response = new Response('Ma réponse');
        
        // if ($_GET['type'] == 'twig')
        if ($request->query->get('type') == 'twig') {
            // $this->render('...') retourne un objet Reponse
            // contenant une page construite avec twig
            $response = $this->render('http/response.html.twig');
        } elseif ($request->query->get('type') == 'json') {
            $exemple = [
                'nom' => 'Anest',
                'prenom' => 'Julien'
            ];
            // transforme le tableau $exemple en JSON
            // et le retourne dans la réponse
            $response = new JsonResponse($exemple);
        }
        
        if ($request->query->get('found') == 'no') {
            // pour retourner une 404
            throw new NotFoundHttpException();
        }
        
        if ($request->query->get('redirect') == 'index') {
            // pour rediriger vers la page dont la route
            // a pour nom app_http_index
            return $this->redirectToRoute('app_http_index');
        }
        
        if ($request->query->get('redirect') == 'bonjour') {
            // pour rediriger vers la page dont la route
            // a pour nom app_index_bonjour en lui passant
            // une valeur pour la partie variable de la route {qui}
            return $this->redirectToRoute(
                'app_index_bonjour',
                ['qui' => 'toi']
            );
        }
        
        return $response;
    }
    
    /**
     * @Route("/session")
     */
    public function session(Request $request)
    {
        // pour accéder à la session
        $session = $request->getSession();
        
        // ajoute un élément 'nom' valant 'Anest' à la session
        // $_SESSION['nom'] = 'Anest';
        $session->set('nom', 'Anest');
        $session->set('prenom', 'Julien');
        
        // accède à l'élément 'nom' de la session
        // var_dump($_SESSION['nom']);
        dump($session->get('nom'));
        
        // var_dump($_SESSION);
        dump($session->all());
        
        // supprime un élément de la session
        // unset($_SESSION['prenom']);
        $session->remove('prenom');
        
        dump($session->all());
        
        return $this->render('http/session.html.twig');
    }
    
    /**
     * 
     * @Route("/flash")
     */
    public function flash(Request $request)
    {
        // ajoute un message flash de type success
        $this->addFlash('success', 'Message de succès');
        
        // header('Location: /http/flashed');
        // die();
        return $this->redirectToRoute('app_http_flashed');
    }
    
    /**
     * @Route("/flashed")
     */
    public function flashed()
    {
        // on va afficher le message
        // ajouté dans la méthode flash()
        // dans la vue
        return $this->render('http/flashed.html.twig');
    }
}

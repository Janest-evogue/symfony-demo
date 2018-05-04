<?php

namespace App\Controller;

use App\Entity\Group;
use App\Entity\Publication;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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
        // en version longue :
        // $repository = $em->getRepository(User::class);
        // $user = $repository->find(1);
        
        // s'il n'y a pas d'utilisateur avec l'id 1 en bdd
        // la méthode find retourne null
        if (is_null($user)) {
            // lance une 404
            throw new NotFoundHttpException();
        }
        
        return $this->render(
            'doctrine/index.html.twig',
            [
                'user' => $user
            ]
        );
    }
    
    /**
     * @Route("/create-user")
     */
    public function createUser(Request $request)
    {
        // si on a reçu du POST
        if ($request->isMethod('POST')) {
            // var_dump($_POST)
            dump($request->request->all());
            $data = $request->request->all();
            
            // on instancie un nouvel objet User
            // et on sette ses attributs avec les données 
            // que l'on a reçu du formulaire
            $user = new User();
            
            $user
                ->setLastname($data['lastname'])
                ->setFirstname($data['firstname'])
                ->setEmail($data['email'])
                // le setter de birthdate attend un objet Datetime
                ->setBirthdate(new \DateTime($data['birthdate']))
            ;
            
            $em = $this->getDoctrine()->getManager();
            // dit qu'il faudra enregistrer l'utilisateur
            // en bdd au prochain appel de la méthode flush()
            $em->persist($user);
            // enregistrement effectif
            $em->flush();
        }
        
        return $this->render(
            'doctrine/create_user.html.twig'
        );
    }
    
    /**
     * @Route("/list-user")
     */
    public function listUser()
    {
        $em = $this->getDoctrine()->getManager();
        // $repository contient une instance
        // de App\Repository\UserRepository
        $repository = $em->getRepository(User::class);
        // retourne tous les utilisateurs de la table user
        // sous forme d'un tableau d'objets User
        $users = $repository->findAll();
        
        dump($users);
        
        return $this->render(
            'doctrine/list_user.html.twig',
            [
                'users' => $users
            ]
        );
    }
    
    /**
     * @Route("/search-email/{email}")
     */
    public function searchEmail($email)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(User::class);
        // findOneBy() quand on est sûr qu'il
        // y a un résultat max (sur un index unique)
        // retourne un objet User ou null
        $user = $repository->findOneBy([
            'email' => $email
        ]);
        
        if (is_null($user)) {
            throw new NotFoundHttpException();
        }
        
        return $this->render(
            'doctrine/index.html.twig',
            [
                'user' => $user
            ]
        );
    }
    
    /**
     * 
     * @Route("/search-lastname/{lastname}")
     */
    public function searchLastname($lastname)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(User::class);
        
        // retourne un tableau d'objets User
        // filtré sur le nom de famille
        // S'il n'y a aucun user en bdd avec ce nom
        // de famille, retourne un tableau vide
        $users = $repository->findBy([
            'lastname' => $lastname
        ]);
        
        dump($users);
        
        return $this->render(
            'doctrine/list_user.html.twig',
            [
                'users' => $users
            ]
        );
    }
    
    /**
     * Le paramètre dans l'url s'appelle id
     * comme la clé primaire de la table user
     * En typant User le paramètre passé à la méthode
     * d'action, je récupère dans $author un objet User
     * qui a cet id en bdd
     * C'est le composant ParamConverter qui fait ça
     * 
     * @Route("/publication/author/{id}")
     */
    public function publicationsByAuthor(User $author)
    {
        dump($author);
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Publication::class);
        
        $publications = $repository->findBy([
            'author' => $author
        ]);

        dump($publications);
        
        return $this->render(
            'doctrine/publications.html.twig',
            [
                'publications' => $publications
            ]
        );
    }
    /**
     * @Route("/author/{id}/publications")
     */
    public function userPublications(User $user)
    {
        // en demandant le contenu de l'attribut $publications
        // d'un objet User, Doctrine va automatiquement
        // faire une requête en bdd
        // pour y mettre les publications liées à cet utilisateur
        // grâce à l'annotation @ORM\OneToMany sur
        // l'attribut dans la classe
        
        return $this->render(
            'doctrine/user_publications.html.twig',
            [
                'user' => $user
            ]
        );
    }
    
    /**
     * 
     * @Route("/user-with-publication")
     */
    public function userWithPublication(Request $request)
    {
        if ($request->isMethod('POST')) {
            $data = $request->request->all();
            $user = new User();
            $user
                ->setLastname($data['lastname'])
                ->setFirstname($data['firstname'])
                ->setEmail($data['email'])
                ->setBirthdate(new \DateTime($data['birthdate']))
            ;
            
            $publication = new Publication();
            
            $publication
                ->setTitle($data['title'])
                ->setContent($data['content'])
            ;
            
            $user->addPublication($publication);
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            // grâce à cascade={"persist"} ajouté dans
            // l'annotation OneToMany sur l'attribut $publications
            // de la classe User plus besoin d'appeler la méthode persist()
            // sur la publication pour qu'elle soit enregistrée en bdd
            
            //$em->persist($publication);
            $em->flush();
        }
        
        return $this->render(
            'doctrine/user_with_publication.html.twig'
        );
    }
    
    /**
     * @Route("/users/group/{id}")
     */
    public function usersByGroup(Group $group)
    {
        return $this->render(
            'doctrine/users_by_group.html.twig',
            [
                'group' => $group
            ]
        );
    }
}

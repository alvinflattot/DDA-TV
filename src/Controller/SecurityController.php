<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Contrôleurs des pages du système d'authentification du site
 */
class SecurityController extends AbstractController
{
    /**
     *  Page de connexion
     * 
     * @Route("/connexion", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Redirection vers une autre page du site si l'utilisateur est déjà connecté.
        if ($this->getUser()) {
            return $this->redirectToRoute('catalogue_index');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/deconnexion", name="app_logout")
     */
    public function logout()
    {
        // Le code ici ne sera jamais lu car la page de déconnexion est déjà gérée en interne par le bundle security.
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    
    /**
     * Page d'activation de compte
     *
     * @Route("/activer-compte/{activationToken}/", name="app_activation")
     * @Security("!is_granted('ROLE_USER')")
     */
    public function activation(User $user){

        // Si le compte est déjà activé, message flash de type "error"
        if($user->getActivated()){

            $this->addFlash('error', 'Ce compte a déjà été activé !');

        } else {

            // Activation du compte
            $user->setActivated(true);

            // Sauvegarde de la modification dans la base de données grâce au manager général des entités
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            // Message flash de type "success"
            $this->addFlash('success', 'Votre compte a bien été activé ! Vous pouvez maintenant vous y connecter.');
        }

        // Redirection de l'utilisateur sur la page de connexion
        return $this->redirectToRoute('app_login');

    }

}

<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use DateTime;
use App\Recaptcha\RecaptchaValidator;  // Importation de notre service de validation du captcha
use Symfony\Component\Form\FormError;  // Importation de la classe permettant de créer des erreurs dans les formulaires

class RegistrationController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * Page d'inscription
     * 
     * @Route("/creer-un-compte", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder,  RecaptchaValidator $recaptcha): Response
    {

        // Redirige de force vers l'accueil si l'utilisateur est déjà connecté
        if ($this->getUser()) {
            return $this->redirectToRoute('catalogue');
        }

        // Création d'un nouvel objet "User"
        $user = new User();

        // Création d'un formulaire d'inscription, lié à l'objet vide créé juste avant
        $form = $this->createForm(RegistrationFormType::class, $user);

        // Liaison des données de requête (POST) avec le formulaire
        $form->handleRequest($request);

        // Si le formulaire a été envoyé
        if ($form->isSubmitted()) {

            //Vérification que le captcha est valide
            $captchaResponse = $request->request->get('g-recaptcha-response', null);

            // Si le captcha est null ou si il est invalide, ajout d'une erreur générale sur le formulaire (qui sera considéré comme échoué après)
             if($captchaResponse == null || !$recaptcha->verify($captchaResponse, $request->server->get('REMOTE_ADDR'))){

                // Ajout de l'erreur au formulaire
                $form->addError(new FormError('Veuillez remplir le Captcha de sécurité'));
            }
            
            if ($form->isValid()){

                dump(new DateTime);
                // Hydratation du nouveau compte
                $user
                    ->setPassword(
                        $passwordEncoder->encodePassword(
                            $user,
                            $form->get('plainPassword')->getData()
                        )
                    )

                    // Date actuelle
                    ->setRegistrationDate(new DateTime())

                    // Compte non activé
                    ->setActivated(false)

                    // md5 aléatoire comme token d'activation
                    ->setActivationToken( md5( random_bytes(100) ) )
                    
                ;

                // Sauvegarde du nouveau compte grâce au manager général des entités
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                // generate a signed url and email it to the user
                $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                    (new TemplatedEmail())
                        ->from(new Address('noreply@ddatv.com', 'le patron'))
                        ->to($user->getEmail())
                        ->subject('Please Confirm your Email')
                        ->htmlTemplate('registration/confirmation_email.html.twig')
                );
                // do anything else you need here, like send an email

                // Message flash de succès
                $this->addFlash('success', 'Votre compte a été créé avec succès ! Un email vous a été envoyé pour activer votre compte.');

                return $this->redirectToRoute('home');
            }
        }

        // Appel de la vue en envoyant le formulaire à afficher
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Votre adresse mail a bien été vérifié.');

        return $this->redirectToRoute('app_register');
    }
}

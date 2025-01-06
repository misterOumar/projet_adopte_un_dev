<?php

namespace App\Controller;


use App\Entity\Cv;
use App\Entity\Fichier;
use App\Form\CVRegistrationType;
use App\Form\DeveloperProfileType;
use App\Form\DevelopperSettingType;
use App\Repository\DeveloperRepository;
use App\Services\FichierService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Uid\Uuid;

use App\Repository\CategorieRepository;
use App\Form\DeveloperFilterType;
use App\Entity\Developer;
use Knp\Component\Pager\PaginatorInterface;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/developer', name: 'app_developer_')]
class DeveloperController extends AbstractController
{
    public function __construct(private DeveloperRepository $developerRepository, private FichierService $fichierService) {}

    #[Route('/profile', name: 'profile')]
    public function index(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getUser();

        $developer = $this->developerRepository->findOneBy(['user' => $user]);
        if (!$developer) {
            throw $this->createNotFoundException('Aucun profil de développeur associé à cet utilisateur.');
        }
        $email = $developer->getUser()->getEmail();
        // dd($email);
        $form = $this->createForm(DeveloperProfileType::class, $developer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            $user = $developer->getUser();

            // avatar
            $avatarFile = $form->get('avatar')->getData();
            // dd($form);
            if ($avatarFile) {

                // Suppression de l'ancien avatar s'il existe
                $oldAvatarReference = $user->getAvatar()?->getReference();
                if (!empty($oldAvatarReference)) {
                    $this->fichierService->deleteUserAvatar($oldAvatarReference);
                }

                // Sauvegarde du nouveau avatar
                $fichier = $this->fichierService->saveUserAvatar($avatarFile);
                $entityManager->persist($fichier);
                $user->setAvatar($fichier);
            }

            // Mise à jour du profil
            $entityManager->flush();
            $this->addFlash('success', 'Profil mis à jour avec succès.');
        }
        
        // formulaire pour le reglage de son profile
        $formSetting = $this->createForm(DevelopperSettingType::class, $developer);
        $formSetting->handleRequest($request);

        if ($formSetting->isSubmitted() && $formSetting->isValid()) {
            // Récupérer les champs du mot de passe
            $currentPassword = $formSetting->get('currentPassword')->getData();
            $newPassword = $formSetting->get('plainPassword')->getData();


            if ($currentPassword) {
                // Vérification du mot de passe actuel
                if ($passwordHasher->isPasswordValid($user, $currentPassword)) {
                    if ($newPassword) {
                        // Hacher et mettre à jour le nouveau mot de passe
                        $hashedPassword = $passwordHasher->hashPassword($user, $newPassword);
                        $user->setPassword($hashedPassword);
                    }
                } else {
                    $this->addFlash('error', 'Le mot de passe actuel est incorrect.');
                    return $this->redirectToRoute('app_developer_profile');
                }
            }
            $entityManager->flush();
            $this->addFlash('success', 'Réglages du profil mis à jour avec succès.');
        }

        // formulaire pour ajouter un CV
        $cv = new Cv();
        $cv->setDeveloper($developer);
        $formCv = $this->createForm(CVRegistrationType::class, $cv);
        $formCv->handleRequest($request);

        if ($formCv->isSubmitted() && $formCv->isValid()) {
            // Upload du fichier
            $uploadedFile = $formCv->get('fichier')->getData();
            $uuid = Uuid::v4();
            $fileName = $uuid . '.' . $uploadedFile->guessExtension();
            $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/cvs';
            $uploadedFile->move($uploadDir, $fileName);

            // Enregistrement en base
            $fichier = new Fichier();
            $fichier->setNom($uploadedFile->getClientOriginalName());
            $fichier->setReference($fileName);

            $entityManager->persist($fichier);

            $cv->setFichier($fichier);
            $entityManager->persist($cv);
            $entityManager->flush();

            $this->addFlash('success', 'CV ajouté avec succès.');
            return $this->redirectToRoute('app_developer_profile'); // Page du profil
        }

        return $this->render('developer/profile_dev.html.twig', [
            'form' => $form->createView(),
            'formSetting' => $formSetting->createView(),
            'formCv' => $formCv->createView(),
            'developer' => $developer
        ]);
    }

    // page candidature du dev
    #[Route('/candidature', name: 'candidature')]
    public function candidature(): Response
    {
        return $this->render('developer/candidature_dev.html.twig');
    }

    //postes favoris
    #[Route('/favoris', name: 'favoris')]
    public function favoris(): Response
    {
        return $this->render('developer/poste_favoris_dev.html.twig');
    }
  
   #[Route('/devs', name: 'app_dev_list')]
    public function listFiltered(Request $request, DeveloperRepository $developerRepository, CategorieRepository $categorieRepository,  PaginatorInterface $paginator): Response
    {

        $categories = $categorieRepository->findAll();
        $categoryChoices = [];
        foreach ($categories as $category) {
            $categoryChoices[$category->getNom()] = $category->getNom();
        }

        // Créer le formulaire avec les technologies et catégories récupérées
        $form = $this->createForm(DeveloperFilterType::class, null, [
            'categories' => $categoryChoices,
        ]);

        // Traiter la soumission du formulaire
        $form->handleRequest($request);

        // Récupérer les filtres du formulaire
        $filters = $form->getData() ?? [];

        // Appliquer les filtres à la requête de recherche des développeurs
        $queryBuilder = $developerRepository->createQueryBuilder('d')
            ->leftJoin('d.cat', 'c')
            ->where('1=1');

        if (!empty($filters['valeur'])) {
            $queryBuilder->andWhere(
                'd.nom LIKE :valeur OR d.prenom LIKE :valeur OR d.experience LIKE :valeur OR d.salaire LIKE :valeur'
            )
            ->setParameter('valeur', '%'.$filters['valeur'].'%');
        }

        if (!empty($filters['categorie'])) {
            $queryBuilder->andWhere('c.nom = :categorie')
                ->setParameter('categorie', $filters['categorie']);
        }

        if (!empty($filters['experience'])) {
            $queryBuilder->andWhere('d.experience >= :experience')
                ->setParameter('experience', $filters['experience']);
        }

        if (!empty($filters['salaireMin'])) {
            $queryBuilder->andWhere('d.salaire >= :salaireMin')
                ->setParameter('salaireMin', $filters['salaireMin']);
        }

        // Appliquer la pagination
        $pagination = $paginator->paginate(
            $queryBuilder, 
            $request->query->getInt('page', 1), // Page actuelle (par défaut page 1)
            10 // Nombre d'éléments par page
        );

        
        return $this->render('developer/list.html.twig', [
            'developers' => $pagination->getItems(),
            'form' => $form->createView(),
            'pagination' => $pagination,
        ]);
    
    }


    #[Route('/developer/{uuid}', name: 'app_dev_details')]
    public function details(string $uuid, DeveloperRepository $developerRepository, EntityManagerInterface $entityManager, ): Response
    {
        if (!$this->getUser()) {
            
            return $this->redirectToRoute('app_login');
        }
        $developer = $entityManager->getRepository(Developer::class)
            ->createQueryBuilder('d')
            ->innerJoin('d.user', 'u')
            ->where('u.uuid = :uuid')
            ->setParameter('uuid', $uuid)
            ->getQuery()
            ->getOneOrNullResult();

        if (!$developer) {
            throw $this->createNotFoundException('Développeur non trouvé');
        }

        return $this->render('developer/details.html.twig', [
            'developer' => $developer,
        ]);
    }



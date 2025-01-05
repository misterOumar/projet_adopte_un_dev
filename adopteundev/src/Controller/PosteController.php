<?php

namespace App\Controller;

use App\Entity\Poste;
use App\Form\PosteFormType;
use App\Repository\CompanyRepository;
use App\Repository\PosteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PosteController extends AbstractController
{
    public function __construct(private CompanyRepository $companyRepository, private PosteRepository $posteRepository) {}

    #[IsGranted('ROLE_COMPANY')]
    #[Route('company/poste/new', name: 'app_add_poste')]
    public function addPoste(Request $request, EntityManagerInterface $entityManager, CompanyRepository $companyRepository): Response
    {
        $poste = new Poste();
        $form = $this->createForm(PosteFormType::class, $poste);
        $form->handleRequest($request);

        // Récupérer la `Company` actuellement connectée ou liée
        $user = $this->getUser();
        $company = $companyRepository->findOneBy(['user' => $user]);

        if ($form->isSubmitted() && $form->isValid()) {
            $poste->setCompany($company);

            // Enregistrer dans la base de données
            $entityManager->persist($poste);
            $entityManager->flush();

            $this->addFlash('success', 'Le poste a été créé avec succès.');

            return $this->redirectToRoute('app_company_poste_list');
        }
        return $this->render('company/add_poste.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[IsGranted('ROLE_COMPANY')]
    #[Route('company/poste/list', name: 'app_company_poste_list')]
    public function posteList()
    {
        // Récupérer la `Company` actuellement connectée ou liée
        $user = $this->getUser();
        $company = $this->companyRepository->findOneBy(['user' => $user]);
        //recuperer tous postes de la company connectée
        $postes = $company->getPostes();
        return $this->render('company/poste_list.html.twig', ['postes' => $postes, 'company' => $company]);
    }

    #[IsGranted('ROLE_COMPANY')]
    #[Route('company/poste/details/{uuid}', name: 'app_company_poste_details')]
    public function posteDetail(string $uuid)
    {
        $poste = $this->posteRepository->findOneBy(['uuid' => $uuid]);

        if (!$poste) {
            throw $this->createNotFoundException('Poste introuvable');
        }
        return $this->render('company/poste_details.html.twig', ['poste' => $poste]);
    }

    #[IsGranted('ROLE_COMPANY')]
    #[Route('company/poste/edit/{uuid}', name: 'app_company_poste_edit')]
    public function posteEdit(string $uuid, Request $request, EntityManagerInterface $entityManager): Response {
        $poste = $this->posteRepository->findOneBy(['uuid' => $uuid]);

        $user = $this->getUser();
        $company = $this->companyRepository->findOneBy(['user' => $user]);

        if ($poste->getCompany() !== $company) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à modifier ce poste.');
        }

        $form = $this->createForm(PosteFormType::class, $poste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $poste->setModifiedAt(new \DateTimeImmutable());

            $entityManager->flush();

            $this->addFlash('success', 'Le poste a été mis à jour avec succès.');

            return $this->redirectToRoute('app_company_poste_list');
        }

        return $this->render('company/edit_poste.html.twig', [
            'form' => $form->createView(),
            'poste' => $poste,
        ]);
    }
}

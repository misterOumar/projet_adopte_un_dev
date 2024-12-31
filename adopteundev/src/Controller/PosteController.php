<?php

namespace App\Controller;

use App\Entity\Poste;
use App\Form\PosteFormType;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PosteController extends AbstractController
{
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

        return $this->redirectToRoute('app_company_dashboard');
    }        
        return $this->render('company/add_poste.html.twig', [
            'form_add' => $form->createView(),
        ]);
    }
}

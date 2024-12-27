<?php

namespace App\Controller;

use App\Entity\Technologie;
use App\Form\TechnologieType;
use App\Repository\TechnologieRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[Route('/admin/technologie')]
final class TechnologieController extends AbstractController
{
    #[Route(name: 'app_technologie_index', methods: ['GET', 'POST'])]
    public function index(TechnologieRepository $technologieRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $technologie = new Technologie();
        $form = $this->createForm(TechnologieType::class, $technologie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->persist($technologie);
                $entityManager->flush();

                $this->addFlash('success', "La technologie a bien été crée");
            } catch (UniqueConstraintViolationException  $e) {
                $this->addFlash('error', 'Une technologie avec ce nom existe déjà.');
            }

            // Création d'un message flash pour informer l'utilisateur que tout s'est bien deroulé

            return $this->redirectToRoute('app_technologie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('technologie/index.html.twig', [
            'technologies' => $technologieRepository->findAll(),
            'technologie' => $technologie,
            'form' => $form,
        ]);
    }


    #[Route('/{id}/edit', name: 'app_technologie_edit', methods: ['POST'])]
    public function edit(Request $request, Technologie $technologie, EntityManagerInterface $entityManager): Response
    {
        $formData = $request->request->all();

        $technologie->setNom($formData['name']);

        $entityManager->flush();

        $this->addFlash('success', 'La technologie a été mise à jour avec succès.');

        return $this->redirectToRoute('app_technologie_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}', name: 'app_technologie_delete', methods: ['POST'])]
    public function delete(Request $request, Technologie $technologie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $technologie->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($technologie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_technologie_index', [], Response::HTTP_SEE_OTHER);
    }
}

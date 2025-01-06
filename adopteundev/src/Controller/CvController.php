<?php

namespace App\Controller;

use App\Entity\Cv;
use App\Repository\DeveloperRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CvController extends AbstractController
{

    public function __construct(private DeveloperRepository $developerRepository) {}


    #[Route('/cv/delete/{id}', name: 'cv_delete')]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $cv = $entityManager->getRepository(Cv::class)->find($id);

        $user = $this->getUser();

        $developer = $this->developerRepository->findOneBy(['user' => $user]);

        if (!$cv || $cv->getDeveloper() !== $developer) {
            $this->addFlash('error', 'CV introuvable ou accès non autorisé.');
            return $this->redirectToRoute('app_developer_profile');
        }

        $fichier = $cv->getFichier();

        // Vérification des candidatures
        // $candidatures = $entityManager->getRepository(Candidature::class)->findBy(['cv' => $cv]);

        // if (empty($candidatures)) {
        //     // Supprimer le fichier du disque
        //     $filePath = $this->getParameter('kernel.project_dir') . '/public/uploads/cvs/' . $fichier->getReference();
        //     if (file_exists($filePath)) {
        //         unlink($filePath);
        //     }
        //     $entityManager->remove($fichier);
        // }

        $entityManager->remove($cv);
        $entityManager->flush();

        $this->addFlash('success', 'CV supprimé avec succès.');
        return $this->redirectToRoute('app_developer_profile');
    }


}

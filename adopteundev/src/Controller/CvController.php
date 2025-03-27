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

// genere du texte pour la biographie d'un developeur web

$biographie = "
    Mon nom est John Doe, je suis né le 15 mai 1990 à Paris. Je suis passionné par le développement web et le marketing digital.
    Je vis actuellement en formation à l'école d'informatique à Paris. J'ai développé une expérience professionnelle en tant qu'ingénieur développeur web chez une entreprise de technologie.
    Mon objectif est de vous proposer des services de développement web professionnels et de marketing digital adaptés à vos besoins. J'ai également une expérience en tant que freelance, où j'ai aidé des entreprises à développer leurs sites web et les marques digitales.
";


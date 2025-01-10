<?php

namespace App\Controller;

use App\Entity\Candidature;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CompanyDashboardController extends AbstractController
{
    public function __construct(private CompanyRepository $companyRepository) {}
    #[IsGranted('ROLE_COMPANY')]
    #[Route('/company/dashboard', name: 'app_company_dashboard')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $company = $this->companyRepository->findOneBy(['user' => $user]);

        // Récupérer les candidatures liées aux postes de la société
        $candidatures = $entityManager->getRepository(Candidature::class)->createQueryBuilder('c')
            ->join('c.poste', 'p')
            ->andWhere('p.company = :company')
            ->setParameter('company', $company)
            ->getQuery()
            ->getResult();
        return $this->render('company/dashboard.html.twig', [
            'company' => $company,'candidatures' => $candidatures
        ]);
    }
}

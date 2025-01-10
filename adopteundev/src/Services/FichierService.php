<?php

namespace App\Services;

use App\Entity\Fichier;
use App\Entity\Fond;
use League\Flysystem\FilesystemOperator;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Uid\Uuid;

class FichierService
{
    public function __construct(
        private LoggerInterface $logger,
        private FilesystemOperator $userFilesystem,
        private FilesystemOperator $documentationFilesystem,

    ) {}




    /**
     * Permet de sauvegarder un fichier concernant une documentation
     * 
     * @param  UploadedFile  $fichier     Le fichier uploadé
     * 
     * @return Fichier      L'entité fichier
     */
    public function saveDocumentationFile(UploadedFile $fichier): Fichier
    {
        $uuid = Uuid::v4();

        $nom = $fichier->getClientOriginalName();

        $reference = sprintf('%s.%s', $uuid, $fichier->getClientOriginalExtension());

        $this->documentationFilesystem->write($reference, file_get_contents($fichier->getPathname()));

        return $this->createFileEntity($reference, $nom);
    }

    /**
     * Permet de télécharger le fichier documentation
     * 
     * @param  string $reference  La référence du fichier
     * 
     * @return string
     */
    public function telechargerDocumentationFichier(string $reference): string
    {
        return $this->documentationFilesystem->read($reference);
    }


    /**
     * Supprimer du fichier 
     * 
     * @param string $reference  La reference du fichier
     */
    public function deleteDocumentationFile(string $reference): void
    {
        try {
            $this->documentationFilesystem->delete($reference);
        } catch (\Exception $e) {
            $this->logger->critical('FichierService.deleteDocumentationFile', [
                'message' => $e->getMessage(),
                'reference' => $reference
            ]);
        }
    }

    /**
     * Permet de sauvegarder un fichier concernant une documentation
     * 
     * @param  UploadedFile  $fichier     Le fichier uploadé
     * 
     * @return Fichier      L'entité fichier
     */
    public function saveUserAvatar(UploadedFile $fichier): Fichier
    {
        $uuid = Uuid::v4();

        $nom = $fichier->getClientOriginalName();

        $reference = sprintf('%s.%s', $uuid, $fichier->getClientOriginalExtension());

        // dd($reference);

        $this->userFilesystem->write($reference, file_get_contents($fichier->getPathname()));

        return $this->createFileEntity($reference, $nom);
    }

    /**
     * Supprimer du fichier 
     * 
     * @param string $reference  La reference du fichier
     */
    public function deleteUserAvatar(string $reference): void
    {
        try {
            $this->userFilesystem->delete($reference);
        } catch (\Exception $e) {
            $this->logger->critical('FichierService.deleteUserAvatar', [
                'message' => $e->getMessage(),
                'reference' => $reference
            ]);
        }
    }

    /**
     * Permet de créer une entité fichier
     * 
     * @param string   $fileReference     La référence (le nouveau nom) du fichier
     * @param string   $fileOriginalName  Le nom original du fichier
     * 
     * @return   Fichier                  L'entité fichier
     */
    private function createFileEntity(string $fileReference, string $fileOriginalName): Fichier
    {
        $fichier = new Fichier();

        $fichier->setNom($fileOriginalName);
        $fichier->setReference($fileReference);

        return $fichier;
    }

}

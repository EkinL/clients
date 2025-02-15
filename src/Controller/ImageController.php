<?php
namespace App\Controller;

use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class ImageController extends AbstractController
{
    #[Route('/upload', name: 'image_upload')]
    public function upload(Request $request, SluggerInterface $slugger, EntityManagerInterface $entityManager): Response
    {
        if ($request->isMethod('POST')) {
            /** @var UploadedFile $uploadedFile */
            $uploadedFile = $request->files->get('image');
            if ($uploadedFile) {
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();

                try {
                    $uploadedFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );

                    // Enregistrer l'image originale en base de données
                    $image = new Image();
                    $image->setFilename($newFilename);

                    $entityManager->persist($image);
                    $entityManager->flush();

                    return $this->render('image/upload.html.twig', [
                        'filename' => $newFilename,
                        'image_id' => $image->getId(), // On passe l'ID pour identifier l'enregistrement
                    ]);
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                }
            }
        }

        return $this->render('image/upload.html.twig');
    }

    #[Route('/save-result', name: 'save_result', methods: ['POST'])]
    public function saveResult(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);
        $imageId = $data['image_id'] ?? null;
        $resultFilename = $data['result_filename'] ?? null;

        if (!$imageId || !$resultFilename) {
            return $this->json(['error' => 'Données invalides'], Response::HTTP_BAD_REQUEST);
        }

        $image = $entityManager->getRepository(Image::class)->find($imageId);
        if (!$image) {
            return $this->json(['error' => 'Image non trouvée'], Response::HTTP_NOT_FOUND);
        }

        $image->setResultFilename($resultFilename);
        $entityManager->persist($image);
        $entityManager->flush();

        return $this->json(['message' => 'Résultat enregistré avec succès']);
    }

    #[Route('/history', name: 'image_history')]
    public function history(EntityManagerInterface $entityManager): Response
    {
        $images = $entityManager->getRepository(Image::class)->findAll();

        return $this->render('image/history.html.twig', [
            'images' => $images,
        ]);
    }
}
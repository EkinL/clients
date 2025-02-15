<?php
namespace App\Controller;

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
    public function upload(Request $request, SluggerInterface $slugger): Response
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

                    // Appeler la fonction JavaScript pour traiter l'image
                    // Vous pouvez passer le nom du fichier à la vue pour utilisation dans le script JS
                    return $this->render('image/upload.html.twig', [
                        'filename' => $newFilename,
                    ]);
                } catch (FileException $e) {
                    // Gérer l'exception en cas de problème lors de l'upload
                    $this->addFlash('error', 'Erreur lors de l\'upload de l\'image.');
                }
            }
        }

        return $this->render('image/upload.html.twig');
    }
}
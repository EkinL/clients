<?php

namespace App\Controller;

use App\Entity\Clients;
use App\Form\ClientsType;
use App\Repository\ClientsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\String\Slugger\Slugger;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use App\Service\ImageGeneratorService;



#[Route('/clients')]
final class ClientsController extends AbstractController
{
    #[Route(name: 'app_clients_index', methods: ['GET'])]
    public function index(ClientsRepository $clientsRepository): Response
    {
        return $this->render('clients/index.html.twig', [
            'clients' => $clientsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_clients_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        ImageGeneratorService $imageGeneratorService
    ): Response {
        set_time_limit(0);
        $client = new Clients();
        $form = $this->createForm(ClientsType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('generateWithAI')->getData()) {
                $prompt = $form->get('prompt')->getData() ?? "Avatar généré";
                $imageFileName = $imageGeneratorService->generateImage($prompt);

                if (!$imageFileName) {
                    $this->addFlash('danger', "Erreur : l'image n'a pas pu être générée.");
                    return $this->redirectToRoute('app_clients_new');
                }

                $client->setPicture($imageFileName);
                $client->setPrompt($prompt);
            }

            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('app_clients_index');
        }

        return $this->render('clients/new.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_clients_show', methods: ['GET'])]
    public function show(Clients $client): Response
    {

        // get projects of the client
        $projects = $client->getProjects();
        
        
        return $this->render('clients/show.html.twig', [
            'client' => $client,
            'projects' => $projects,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_clients_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Clients $client,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger,
        ImageGeneratorService $imageGeneratorService
    ): Response {
        set_time_limit(0);
        $form = $this->createForm(ClientsType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $picture = $form->get('picture')->getData();
            $useAI = $form->get('generateWithAI')->getData();

            // 🔽🔽🔽 Gestion de l'IA 🔽🔽🔽
            if ($useAI) {
                $prompt = $form->get('prompt')->getData() ?? "Avatar généré";
                $imageFileName = $imageGeneratorService->generateImage($prompt);

                if (!$imageFileName) {
                    $this->addFlash('danger', "Erreur : l'image n'a pas pu être générée.");
                    return $this->redirectToRoute('app_clients_edit', ['id' => $client->getId()]);
                }

                $client->setPicture($imageFileName);
                $client->setPrompt($prompt);
            }
            if ($picture) {
                $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$picture->guessExtension();

                try {
                    $picture->move(
                        $this->getParameter('picture_directory'),
                        $newFilename
                    );
                    $client->setPicture($newFilename);
                } catch (FileException $e) {
                    $this->addFlash('danger', "Erreur lors de l'upload de l'image.");
                }
            }

            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('app_clients_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('clients/edit.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_clients_delete', methods: ['POST'])]
    public function delete(Request $request, Clients $client, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($client);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_clients_index', [], Response::HTTP_SEE_OTHER);
    }
}

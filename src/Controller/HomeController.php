<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProjectsRepository;
use App\Repository\ClientsRepository;


class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(ProjectsRepository $projectsRepository, ClientsRepository $clientsRepository): Response
    {
        $projects = $projectsRepository->findBy([], ['id' => 'DESC'], 4);

        $clients = $clientsRepository->findBy([], ['id' => 'DESC'], 4);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'projects' => $projects,
            'clients' => $clients
        ]);
    }

    #[Route('/bot', name: 'app_bot')]
    public function bot(): Response
    {
        return $this->render('chatbot/index.html.twig', [
            'controller_name' => 'chatbotController',
        ]);
    }

    #[Route('/demo', name: 'app_demo')]
    public function demo(): Response
    {
        return $this->render('demo/index.html.twig', [
            'controller_name' => 'demoController',
        ]);
    }
}

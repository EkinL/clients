<?php

namespace App\Controller;

use App\Entity\Deliverables;
use App\Form\DeliverablesType;
use App\Repository\DeliverablesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/deliverables')]
final class DeliverablesController extends AbstractController
{
    #[Route(name: 'app_deliverables_index', methods: ['GET'])]
    public function index(DeliverablesRepository $deliverablesRepository): Response
    {
        return $this->render('deliverables/index.html.twig', [
            'deliverables' => $deliverablesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_deliverables_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $deliverable = new Deliverables();
        $form = $this->createForm(DeliverablesType::class, $deliverable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($deliverable);
            $entityManager->flush();

            return $this->redirectToRoute('app_deliverables_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('deliverables/new.html.twig', [
            'deliverable' => $deliverable,
            'form' => $form,
        ]);
    }
 
    #[Route('/{id}', name: 'app_deliverables_show', methods: ['GET'])]
    public function show(Deliverables $deliverable): Response
    {
        return $this->render('deliverables/show.html.twig', [
            'deliverable' => $deliverable,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_deliverables_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Deliverables $deliverable, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DeliverablesType::class, $deliverable);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_deliverables_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('deliverables/edit.html.twig', [
            'deliverable' => $deliverable,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_deliverables_delete', methods: ['POST'])]
    public function delete(Request $request, Deliverables $deliverable, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$deliverable->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($deliverable);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_deliverables_index', [], Response::HTTP_SEE_OTHER);
    }
}

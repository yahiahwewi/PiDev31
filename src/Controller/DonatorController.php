<?php

namespace App\Controller;

use App\Entity\Donator;
use App\Form\DonatorType;
use App\Repository\DonatorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/donator')]
class DonatorController extends AbstractController
{
    #[Route('/', name: 'app_donator_index', methods: ['GET'])]
    public function index(DonatorRepository $donatorRepository): Response
    {
        return $this->render('donator/index.html.twig', [
            'donators' => $donatorRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_donator_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $donator = new Donator();
        $form = $this->createForm(DonatorType::class, $donator);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($donator);
            $entityManager->flush();

            return $this->redirectToRoute('app_donator_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('donator/new.html.twig', [
            'donator' => $donator,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_donator_show', methods: ['GET'])]
    public function show(Donator $donator): Response
    {
        return $this->render('donator/show.html.twig', [
            'donator' => $donator,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_donator_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Donator $donator, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DonatorType::class, $donator);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_donator_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('donator/edit.html.twig', [
            'donator' => $donator,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_donator_delete', methods: ['POST'])]
    public function delete(Request $request, Donator $donator, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$donator->getId(), $request->request->get('_token'))) {
            $entityManager->remove($donator);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_donator_index', [], Response::HTTP_SEE_OTHER);
    }
}

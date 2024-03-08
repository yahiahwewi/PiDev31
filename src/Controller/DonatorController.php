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
use Knp\Component\Pager\PaginatorInterface;

class DonatorController extends AbstractController
{
    #[Route('/donator', name: 'app_donator_index', methods: ['GET'])]
    public function index(Request $request, DonatorRepository $donatorRepository, PaginatorInterface $paginator): Response
    {
        $searchTerm = $request->query->get('search');
    
        if ($searchTerm) {
            $query = $donatorRepository->searchDonators($searchTerm);
        } else {
            $query = $donatorRepository->findAll();
        }
    
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );
    
        return $this->render('donator/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/donator/new', name: 'app_donator_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $donator = new Donator();
        $form = $this->createForm(DonatorType::class, $donator);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($donator);
            $entityManager->flush();

            $this->addFlash('success', 'Donator created successfully !');

            return $this->redirectToRoute('app_donator_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('donator/new.html.twig', [
            'donator' => $donator,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/donator/{id}', name: 'app_donator_show', methods: ['GET'])]
    public function show(Donator $donator): Response
    {
        return $this->render('donator/show.html.twig', [
            'donator' => $donator,
        ]);
    }

    #[Route('/donator/{id}/edit', name: 'app_donator_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Donator $donator, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DonatorType::class, $donator);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Donator modified successfully !');

            return $this->redirectToRoute('app_donator_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('donator/edit.html.twig', [
            'donator' => $donator,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/donator/{id}', name: 'app_donator_delete', methods: ['POST'])]
    public function delete(Request $request, Donator $donator, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$donator->getId(), $request->request->get('_token'))) {
            $entityManager->remove($donator);
            $entityManager->flush();

            $this->addFlash('success', 'Donator deleted successfully !');
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('app_donator_index', [], Response::HTTP_SEE_OTHER);
    }
}

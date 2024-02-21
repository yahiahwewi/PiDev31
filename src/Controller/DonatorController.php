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
use Knp\Component\Pager\PaginatorInterface; // Importez la classe PaginatorInterface

class DonatorController extends AbstractController
{
    #[Route('/donator', name: 'app_donator_index', methods: ['GET'])]
    public function index(Request $request, DonatorRepository $donatorRepository, PaginatorInterface $paginator): Response
    {
        $searchTerm = $request->query->get('search');
    
        if ($searchTerm) {
            $query = $donatorRepository->searchDonators($searchTerm);
        } else {
            $query = $donatorRepository->findAll(); // Utilisation de la méthode findAll()
        }
    
        // Utilisez le paginator pour paginer les résultats
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1), // Numéro de page par défaut
            10 // Nombre d'éléments par page
        );
    
        return $this->render('donator/index.html.twig', [
            'pagination' => $pagination,
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

            $this->addFlash('success', 'Donator created successfully !');

            return $this->redirectToRoute('app_donator_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('donator/new.html.twig', [
            'donator' => $donator,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_donator_show', methods: ['GET'])]
    public function show(int $id, DonatorRepository $donatorRepository): Response
    {
        $donator = $donatorRepository->find($id);

        if (!$donator) {
            throw $this->createNotFoundException('Donator not found');
        }

        return $this->render('donator/show.html.twig', [
            'donator' => $donator,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_donator_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id, EntityManagerInterface $entityManager, DonatorRepository $donatorRepository): Response
    {
        // Récupérer l'entité Donator en fonction de son identifiant
        $donator = $donatorRepository->find($id);

        // Vérifier si l'entité Donator existe
        if (!$donator) {
            throw $this->createNotFoundException('Donator not found');
        }

        // Créer le formulaire en utilisant l'entité Donator récupérée
        $form = $this->createForm(DonatorType::class, $donator);
        $form->handleRequest($request);

        // Traiter le formulaire soumis
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrer les modifications dans la base de données
            $entityManager->flush();

            $this->addFlash('success', 'Donator modified successfully !');

            // Rediriger vers la page d'index des donators
            return $this->redirectToRoute('app_donator_index', [], Response::HTTP_SEE_OTHER);
        }

        // Rendre le template d'édition du donateur avec le formulaire
        return $this->render('donator/edit.html.twig', [
            'donator' => $donator,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_donator_delete', methods: ['POST'])]
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

<?php

namespace App\Controller;

use App\Entity\DonationsList;
use App\Form\DonationsListType;
use App\Repository\DonationsListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/donations/list')]
class DonationsListController extends AbstractController
{
    #[Route('/', name: 'app_donations_list_index')]
    public function index(DonationsListRepository $donationsListRepository): Response
    {
        $donationsLists = $donationsListRepository->findAll();
        
        return $this->render('donations_list/index.html.twig', [
            'donations_lists' => $donationsLists,
        ]);
    }

    #[Route('/new', name: 'app_donations_list_new',  methods: ['GET','POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $donationsList = new DonationsList();
        $form = $this->createForm(DonationsListType::class, $donationsList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($donationsList);
            $entityManager->flush();

            return $this->redirectToRoute('app_donations_list_index');
        }

        return $this->render('donations_list/new.html.twig', [
            'donations_lists' => $donationsList,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_donations_list_show')]
    public function show(DonationsList $donationsList): Response
    {
        return $this->render('donations_list/show.html.twig', [
            'donations_list' => $donationsList,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_donations_list_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DonationsList $donationsList, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DonationsListType::class, $donationsList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_donations_list_index');
        }

        return $this->render('donations_list/edit.html.twig', [
            'donations_list' => $donationsList,
            'form' => $form->createView(),
        ]);
    }

#[Route('/donations/{id}', name: 'app_donations_delete')]
public function delete(DonationsList $donation): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->remove($donation);
    $entityManager->flush();

    return $this->redirectToRoute('app_donations_list_index');
}

}

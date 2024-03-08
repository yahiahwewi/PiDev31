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
use Knp\Component\Pager\PaginatorInterface;
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/Admin/Donation')]
class DonationAdminController extends AbstractController
{
    #[Route('/', name: 'admin_donations_index')]
    public function index(DonationsListRepository $donationsListRepository,PaginatorInterface  $paginator,Request $request): Response
    {
        $donationsLists = $donationsListRepository->findAll();

        $arb=$paginator->paginate(
            $donationsLists,
            $request->query->getInt('page',1),
            2


        ) ;
        
        return $this->render('donations_list_admin/index.html.twig', [
            'donations_lists' => $arb,
        ]);
    }

    #[Route('/{id}', name: 'admin_donations_list_show')]
    public function show(DonationsList $donationsList): Response
    {
        return $this->render('donations_list_admin/show.html.twig', [
            'donations_list' => $donationsList,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_donations_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DonationsList $donationsList, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DonationsListType::class, $donationsList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_donations_index');
        }

        return $this->render('donations_list_admin/edit.html.twig', [
            'donations_list' => $donationsList,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/donations/{id}', name: 'admin_donations_delete', methods: ['POST'])]
    public function delete(Request $request, DonationsList $donation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$donation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($donation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_donations_index');
    }


    #[Route('/search/donation', name: 'admin_donations_recherche', methods: ['GET'])]
        public function search(Request $request, EntityManagerInterface $entityManager)
        {
            $search = $request->query->get('search');
            $produits = $entityManager
            ->getRepository(DonationsList::class)
            ->findAll();
            if ($search) {
                $productRepository = $entityManager->getRepository(DonationsList::class);

            $searchResults = $productRepository->createQueryBuilder('p')
                ->where('p.idProduit = :query OR p.nameProd LIKE :query OR p.prodDescription LIKE :query OR p.typeProd LIKE :query OR p.priceProd = :query OR p.quantite = :query OR p.imageProd LIKE :query OR p.status LIKE :query')
                ->setParameter('query', '%'.$search.'%')
                ->getQuery()
                ->getResult();
                    }
                    else{
                        $searchResults = $entityManager
                        ->getRepository(DonationsList::class)
                        ->findAll();
                    }

                        return  $this->json($searchResults);

        }



 


   

}

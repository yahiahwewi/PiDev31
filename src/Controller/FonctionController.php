<?php

namespace App\Controller;

use App\Entity\DonationsList;
use App\Repository\DonationsListRepository;
use App\Repository\ProjectsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use GuzzleHttp\Psr7\Request;

class FonctionController extends AbstractController
{
    #[Route('/fonction', name: 'app_fonction')]
    public function index(): Response
    {
        return $this->render('fonction/index.html.twig', [
            'controller_name' => 'FonctionController',
        ]);
    }





    /**
     * @Route("/pdf", name="pdf", methods={"GET"})
     */
    public function list(DonationsListRepository $FootRepository): Response
    {
        // Configure Dompdf according to your needs
        $pdfoptions = new Options();
        $pdfoptions->set('defaultFont', 'Arial');
        $pdfoptions->set('tempDir', '.\www\DaryGym\public\uploads\images');


        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfoptions);
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('donations_list/pdf.html.twig', [
            'donations_lists' => $FootRepository->findAll(),
        ]);
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);
    }




    
 /**
     * @Route("/stats", name="stats")
     */
    public function statistiques(ProjectsRepository $DonationsListRepository){
        // On va chercher toutes les types
        $menus = $DonationsListRepository->findAll();

//Data Category
        $foot = $DonationsListRepository->createQueryBuilder('a')
            ->select('count(a.amount)')
            ->Where('a.title= :title')
            ->setParameter('title',"ecole")
            ->getQuery()
            ->getSingleScalarResult();

        $hand = $DonationsListRepository->createQueryBuilder('a')
            ->select('count(a.amount)')
            ->Where('a.title= :title')
            ->setParameter('title',"faculte")
            ->getQuery()
            ->getSingleScalarResult();
        $volley= $DonationsListRepository->createQueryBuilder('a')
            ->select('count(a.amount)')
            ->Where('a.title= :title')
            ->setParameter('title',"lycee")
            ->getQuery()
            ->getSingleScalarResult();

       

        return $this->render('Stats/stats.html.twig', [
            'nfoot' => $foot,
            'nhand' => $hand,
            'nvol' => $volley,


        ]);
    }



}

<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Form\ConferenceType;
use App\Repository\ConferenceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Association;
use App\Form\AssociationType;
use App\Repository\AssociationRepository;


class TtiConferenceController extends AbstractController
{
    #[Route('/tti/conference', name: 'app_tti_conference')]
    public function index(): Response
    {
        return $this->render('tti_conference/index.html.twig', [
            'controller_name' => 'TtiConferenceController',
        ]);
    }

     /**
     * @Route("/TrierspcASC", name="tridate",methods={"GET"})
     */

     public function trierDate(Request $request, ConferenceRepository $ConferenceRepository): Response
     {
 
         $ConferenceRepository = $this->getDoctrine()->getRepository(Conference::class);
         $arb = $ConferenceRepository->trieamirr();
 
         return $this->render('conference/index.html.twig', [
             'conferences' => $arb,
         ]);
     }




     
    /**

     * @Route("/search", name="recherchearb", methods={"GET"})

     */


     public function searchoffreajax(Request $request, ConferenceRepository $FootRepository): Response

     {
 
         $FootRepository = $this->getDoctrine()->getRepository(Conference::class);
 
         $requestString = $request->get('searchValue');
 
         $foot = $FootRepository->findlieubytype($requestString);
 
 
         return $this->render('conference/index.html.twig', [
 
             "conferences" => $foot
 
         ]);
 
     }
 



       /**

     * @Route("/searchc", name="rechercheass", methods={"GET"})

     */


     public function searchassajax(Request $request, AssociationRepository $FootRepository): Response

     {
 
         $FootRepository = $this->getDoctrine()->getRepository(Association::class);
 
         $requestString = $request->get('searchValue');
 
         $foot = $FootRepository->findassociationbynom($requestString);
 
 
         return $this->render('association/index.html.twig', [
 
             "associations" => $foot
 
         ]);
 
     }


      /**
     * @Route("/TrierASC", name="trinom",methods={"GET"})
     */

     public function triernom(Request $request, AssociationRepository $AssociationRepository): Response
     {
 
         $AssociationRepository = $this->getDoctrine()->getRepository(Association::class);
         $arb = $AssociationRepository->triass();
 
         return $this->render('association/index.html.twig', [
             'associations' => $arb,
         ]);
     }


/**
     * @Route("/TrierDESC", name="trinomdes",methods={"GET"})
     */

     public function triernomdes(Request $request, AssociationRepository $AssociationRepository): Response
     {
 
         $AssociationRepository = $this->getDoctrine()->getRepository(Association::class);
         $arb = $AssociationRepository->trides();
 
         return $this->render('association/index.html.twig', [
             'associations' => $arb,
         ]);
     }


     /**
     * @Route("/Trierfront", name="trinomdesfront",methods={"GET"})
     */

     public function triernomdesfront(Request $request, AssociationRepository $AssociationRepository): Response
     {
 
         $AssociationRepository = $this->getDoctrine()->getRepository(Association::class);
         $arb = $AssociationRepository->trides();
 
         return $this->render('association/user/indexfront.html.twig', [
             'associations' => $arb,
         ]);
     }


          /**

     * @Route("/searchassotionfront", name="rechercheassfront", methods={"GET"})

     */


     public function searchassajaxAssosiationfront(Request $request, AssociationRepository $FootRepository): Response

     {
 
         $FootRepository = $this->getDoctrine()->getRepository(Association::class);
 
         $requestString = $request->get('searchValue');
 
         $foot = $FootRepository->findassociationbynom($requestString);
 
 
         return $this->render('association/user/indexfront.html.twig', [
 
             "associations" => $foot
 
         ]);
 
     }




      /**
     * @Route("/TrierASCfront", name="trinomfront",methods={"GET"})
     */

     public function triernomfront(Request $request, AssociationRepository $AssociationRepository): Response
     {
 
         $AssociationRepository = $this->getDoctrine()->getRepository(Association::class);
         $arb = $AssociationRepository->triass();
 
         return $this->render('association/user/indexfront.html.twig', [
             'associations' => $arb,
         ]);
     }

}

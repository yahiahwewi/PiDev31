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
use Knp\Component\Pager\PaginatorInterface;


#[Route('/conference')]
class ConferenceController extends AbstractController
{
    #[Route('/', name: 'app_conference_index', methods: ['GET'])]
    public function index(PaginatorInterface  $paginator,Request $request , ConferenceRepository $ConferenceRepository): Response

    {
        $searchTerm = $request->query->get('search');
        $associations = $ConferenceRepository->findBySearchTerm($searchTerm);

        $arb=$paginator->paginate(

            $associations,

            $request->query->getInt('page',1),

            3


        ) ;



        return $this->render('conference/index.html.twig', [

            'conferences'=>$arb

        ]);

    }



    #[Route('/conferencefront', name: 'app_conferencefront_index', methods: ['GET'])]
    public function indexfornt(PaginatorInterface  $paginator,Request $request,  ConferenceRepository $ConferenceRepository): Response

    {

        $searchTerm = $request->query->get('search');
        $associations = $ConferenceRepository->findBySearchTerm($searchTerm);
        $arb=$paginator->paginate(

            $associations,

            $request->query->getInt('page',1),

            3


        ) ;



        return $this->render('conference/user/indexfront.html.twig', [

            'conferences'=>$arb

        ]);

    }



    #[Route('/new', name: 'app_conference_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $conference = new Conference();
        $form = $this->createForm(ConferenceType::class, $conference);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($conference);
            $entityManager->flush();

            $this->addFlash(

                'info',

                'Added successfully!'

            );

            return $this->redirectToRoute('app_conference_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('conference/new.html.twig', [
            'conference' => $conference,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_conference_show', methods: ['GET'])]
    public function show(Conference $conference): Response
    {
        return $this->render('conference/show.html.twig', [
            'conference' => $conference,
        ]);
    }

    #[Route('/show/{id}', name: 'app_conferencefront_show', methods: ['GET'])]
    public function showfront(Conference $conference): Response
    {
        return $this->render('conference/user/showfront.html.twig', [
            'conference' => $conference,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_conference_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Conference $conference, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ConferenceType::class, $conference);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash(

                'upd',

                'Updated successfully!'

            );

            return $this->redirectToRoute('app_conference_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('conference/edit.html.twig', [
            'conference' => $conference,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_conference_delete', methods: ['POST'])]
    public function delete(Request $request, Conference $conference, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$conference->getId(), $request->request->get('_token'))) {
            $entityManager->remove($conference);
            $entityManager->flush();

            $this->addFlash(

                'del',

                'Deleted successfully!'

            );
        }

        return $this->redirectToRoute('app_conference_index', [], Response::HTTP_SEE_OTHER);
    }









    




}

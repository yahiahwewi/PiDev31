<?php

namespace App\Controller;

use App\Entity\Association;
use App\Form\AssociationType;
use App\Repository\AssociationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\AsciiSlugger;

#[Route('/association')]
class AssociationController extends AbstractController
{
    #[Route('/', name: 'app_association_index', methods: ['GET'])]
    public function index(AssociationRepository $associationRepository, Request $request): Response
    {
        $searchTerm = $request->query->get('search');
        $associations = $associationRepository->findBySearchTerm($searchTerm);

        return $this->render('association/index.html.twig', [
            'associations' => $associations,
        ]);
    }


    #[Route('/ass', name: 'app_associationfront_index', methods: ['GET'])]
    public function indexfront(AssociationRepository $associationRepository, Request $request): Response
    {
        $searchTerm = $request->query->get('search');
        $associations = $associationRepository->findBySearchTerm($searchTerm);

        return $this->render('association/user/indexfront.html.twig', [
            'associations' => $associations,
        ]);
    }


    #[Route('/new', name: 'app_association_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $association = new Association();
    $form = $this->createForm(AssociationType::class, $association);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Traitement de l'importation de l'image
        $brochureFile = $form->get('logo')->getData();

        if ($brochureFile instanceof UploadedFile) {
            $slugger = new AsciiSlugger();
            $br = 'uploads/img';

            $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

            try {
                $brochureFile->move(
                    $br,
                    $newFilename
                );
            } catch (FileException $e) {
                // Gérer l'exception si quelque chose se passe mal lors du téléchargement du fichier
            }

            // Mettre à jour le nom du fichier dans l'entité Association
            $association->setLogo($newFilename);
        }

        $entityManager->persist($association);
        $entityManager->flush();

        $this->addFlash(

            'info',

            'Added successfully!'

        );

        $this->addFlash('success', 'Association created successfully.');

        return $this->redirectToRoute('app_association_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('association/new.html.twig', [
        'association' => $association,
        'form' => $form,
    ]);
}



#[Route('/newassociation', name: 'app_associationfront_new', methods: ['GET', 'POST'])]
public function newfront(Request $request, EntityManagerInterface $entityManager): Response
{
    $association = new Association();
    $form = $this->createForm(AssociationType::class, $association);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $brochureFile = $form->get('logo')->getData();

        if ($brochureFile instanceof UploadedFile) {
            $slugger = new AsciiSlugger();
            $br = 'uploads/img';

            $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

            try {
                $brochureFile->move(
                    $br,
                    $newFilename
                );
            } catch (FileException $e) {
                // Gérer l'exception si quelque chose se passe mal lors du téléchargement du fichier
            }

            // Mettre à jour le nom du fichier dans l'entité Association
            $association->setLogo($newFilename);
        }

        $entityManager->persist($association);
        $entityManager->flush();

        $this->addFlash(

            'info',

            'Added successfully!'

        );

        $this->addFlash('success', 'Association created successfully.');

        return $this->redirectToRoute('app_associationfront_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('association/responsable/newfront.html.twig', [
        'association' => $association,
        'form' => $form,
    ]);
}







    #[Route('/{id}', name: 'app_association_show', methods: ['GET'])]
    public function show(Association $association): Response
    {
        return $this->render('association/show.html.twig', [
            'association' => $association,
        ]);
    }

    #[Route('/show/{id}', name: 'app_associationfront_show', methods: ['GET'])]
    public function showfront(Association $association): Response
    {
        return $this->render('association/user/showfront.html.twig', [
            'association' => $association,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_association_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Association $association, EntityManagerInterface $entityManager): Response
{
    $form = $this->createForm(AssociationType::class, $association);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $logoFile = $form->get('logo')->getData();

        if ($logoFile instanceof UploadedFile) {
            $slugger = new AsciiSlugger();
            $br = 'uploads/img';

            $originalFilename = pathinfo($logoFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$logoFile->guessExtension();

            try {
                $logoFile->move(
                    $br,
                    $newFilename
                );
            } catch (FileException $e) {
                // Gérer l'exception si quelque chose se passe mal lors du téléchargement du fichier
            }

            // Mettre à jour le nom du fichier dans l'entité Association
            $association->setLogo($newFilename);
        }

        $entityManager->flush();

        $this->addFlash(

            'upd',

            'Updated successfully!'

        );

        $this->addFlash('success', 'Association updated successfully.');

        return $this->redirectToRoute('app_association_index', [], Response::HTTP_SEE_OTHER);
    }

    return $this->renderForm('association/edit.html.twig', [
        'association' => $association,
        'form' => $form,
    ]);
}

    
    #[Route('/{id}', name: 'app_association_delete', methods: ['POST'])]
    public function delete(Request $request, Association $association, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$association->getId(), $request->request->get('_token'))) {
            $entityManager->remove($association);
            $entityManager->flush();

            $this->addFlash(

                'del',

                'Deleted successfully!'

            );
        

            $this->addFlash('success', 'Association deleted successfully.');
        }

        return $this->redirectToRoute('app_association_index', [], Response::HTTP_SEE_OTHER);
    }


    
}

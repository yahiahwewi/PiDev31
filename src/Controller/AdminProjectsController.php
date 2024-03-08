<?php

namespace App\Controller;

use App\Entity\Projects;
use App\Form\ProjectsType;
use App\Repository\ProjectsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\HttpFoundation\Response;


#[Route('/admin/projects')]
class AdminProjectsController extends AbstractController
{
    #[Route('/', name: 'admin_projects_index', methods: ['GET'])]
    public function index(ProjectsRepository $projectsRepository): Response
    {
        return $this->render('projects_admin/index.html.twig', [
            'projects' => $projectsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'admin_projects_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $project = new Projects();
        $form = $this->createForm(ProjectsType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($project);
            $entityManager->flush();

            $this->addFlash(
                'info',
                'Added successfully!'
            );

            return $this->redirectToRoute('admin_projects_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('projects_admin/new.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }


    #[Route('/{id}', name: 'admin_projects_show', methods: ['GET'])]
    public function show(Projects $project): Response
    {
        return $this->render('projects_admin/show.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_projects_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Projects $project, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProjectsType::class, $project);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash(
                'upd',
                'updated successfully!'
            );

            return $this->redirectToRoute('admin_projects_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('projects_admin/edit.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_projects_delete', methods: ['POST'])]
    public function delete(Request $request, Projects $project, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$project->getId(), $request->request->get('_token'))) {
            $entityManager->remove($project);
            $entityManager->flush();

            $this->addFlash(
                'del',
                'deleted successfully!'
            );
        }

        return $this->redirectToRoute('admin_projects_index', [], Response::HTTP_SEE_OTHER);
    }



    
  
}

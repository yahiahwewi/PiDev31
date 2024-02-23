<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\BlogPostRepository;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/comment')]
class CommentController extends AbstractController
{
        #[Route('/', name: 'app_comment_index', methods: ['GET'])]
        public function index(CommentRepository $commentRepository,BlogPostRepository $blogPostRepository,UserRepository $userRepository): Response
        {



            return $this->render('comment/index.html.twig', [
                'comments' => $commentRepository->findAll(),
                'users' => $userRepository->findAll(),
                'blogs' => $blogPostRepository->findAll(),
            ]);
        }



    #[Route('/new', name: 'app_comment_new', methods: ['GET', 'POST'])]
    public function new($id,Request $request, EntityManagerInterface $entityManager,BlogPostRepository $blogPostRepository, UserRepository $userRepository): Response
    {
        $comment = new Comment();
        // $blog=$blogPostRepository->find($id);
        // $user=$userRepository->find(2);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            

            
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_comment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }



    #[Route('/new/{id}', name: 'app_commentbloc_new', methods: ['GET', 'POST'])]
    public function newcommforblog($id,Request $request, EntityManagerInterface $entityManager,BlogPostRepository $blogPostRepository, UserRepository $userRepository): Response
    {
        $comment = new Comment();
        // $blog=$blogPostRepository->find($id);
        // $user=$userRepository->find(2);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $createdAt = new \DateTimeImmutable();

            $comment->setCreatedAt($createdAt);
            

            $comment->setUserId(2);
            $comment->setBlogPostId($id);
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_comment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_comment_show', methods: ['GET'])]
    public function show(Comment $comment,BlogPostRepository $blogPostRepository,UserRepository $userRepository): Response
    {


        $username=($userRepository->find($comment->getUserId()))->getUsername();

        $blog=($blogPostRepository->find($comment->getBlogPostId()))->getTitle();

        return $this->render('comment/show.html.twig', [
            'comment' => $comment,
            #'userID'  => $user,
            'username' => $username,
            'blogtitle'=> $blog,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_comment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_comment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_comment_delete', methods: ['POST'])]
    public function delete(Request $request, Comment $comment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$comment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_comment_index', [], Response::HTTP_SEE_OTHER);
    }
}

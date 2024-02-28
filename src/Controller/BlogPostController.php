<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Entity\Comment;
use App\Form\BlogPostType;
use App\Form\CommentType;
use App\Repository\BlogPostRepository;
use App\Repository\CommentRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/blog/post')]
class BlogPostController extends AbstractController
{
    #[Route('/', name: 'app_blog_post_index', methods: ['GET'])]
    public function index(PaginatorInterface $paginator, BlogPostRepository $blogPostRepository, Request $request, CommentRepository $commentRepository): Response
    {
        // Get all blog posts
        $blogPosts = $blogPostRepository->findAll();
        $comment= $commentRepository -> findAll();

    
        // Create an array to store comment forms for each blog post
        $commentForms = [];
    
        // Create comment forms for each blog post
        foreach ($blogPosts as $blogPost) {
            // Create a new comment instance for each blog post
            $comment = new Comment();
            $comment->setBlogPostId($blogPost->getId()); // Associate the comment with the current blog post
    
            // Create the form for creating a new comment
            $commentForm = $this->createForm(CommentType::class, $comment, [
                'action' => $this->generateUrl('app_commentbloc_new', ['id' => $blogPost->getId()]), // Adjust the route name accordingly
                'method' => 'POST',
            ]);
    
            // Add the comment form to the array
            $commentForms[$blogPost->getId()] = $commentForm->createView();
        }
    
        // Paginate the list of blog posts
        $pagination = $paginator->paginate(
            $blogPosts,
            $request->query->getInt('page', 1), // Current page
            3 // Posts per page
        );
    
        // Render the Twig template with the paginated blog posts and comment forms
        return $this->render('blog_post/index.html.twig', [
            'blog_posts' => $pagination,
            'commentForms' => $commentForms, // Pass the comment forms to the template
            'Comments' => $comment,

        ]);
    }

    #[Route('/new', name: 'app_blog_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,UserRepository $user,SluggerInterface $slugger): Response
    {

        
        $u=$user->find(2); // static use coz i signed up to add a user to work with  
        $blogPost = new BlogPost();
        $form = $this->createForm(BlogPostType::class, $blogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $createdAt = new \DateTimeImmutable();

            $blogPost->setCreatedAt($createdAt);

             $imageFile = $form->get('image')->getData();

             if ($imageFile) {
                 $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                 $safeFilename = $slugger->slug($originalFilename);
                 $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                 // Move the file to the directory where your images are stored
                 try {
                     $imageFile->move(
                         $this->getParameter('img_directory'), // where the image will be stored
                         $newFilename
                     );
                 } catch (FileException $e) {
                     // Handle the exception if something happens during the file upload.
                 }

                 // Update the 'image' property to store the file name instead of its contents.
                 $blogPost->setImage($newFilename);
             }

            $blogPost->setUser($u);

            $entityManager->persist($blogPost);
            $entityManager->flush();

            return $this->redirectToRoute('app_blog_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('blog_post/new.html.twig', [
            'blog_post' => $blogPost,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'app_blog_post_show', methods: ['GET'])]
    public function show(BlogPost $blogPost, CommentRepository $commentRepository): Response
    {
        $comment= $commentRepository -> findAll();

        return $this->render('blog_post/show.html.twig', [
            'blog_post' => $blogPost,
            'Comments' => $comment,
        ]);
    }

    #[Route('/edit/{id}', name: 'app_blog_post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, BlogPost $blogPost, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(BlogPostType::class, $blogPost);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_blog_post_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('blog_post/edit.html.twig', [
            'blog_post' => $blogPost,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_blog_post_delete', methods: ['POST'])]
    public function delete(Request $request, BlogPost $blogPost, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blogPost->getId(), $request->request->get('_token'))) {
            $entityManager->remove($blogPost);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_blog_post_index', [], Response::HTTP_SEE_OTHER);
    }
}

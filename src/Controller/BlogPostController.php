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
use App\Form\BlogPostFilterType;
use Dompdf\Dompdf;

#[Route('/blog/post')]
class BlogPostController extends AbstractController
{
    #[Route('/', name: 'app_blog_post_index', methods: ['GET'])]
    public function index(PaginatorInterface $paginator, BlogPostRepository $blogPostRepository, Request $request, CommentRepository $commentRepository): Response
    {
        // Create the form for filtering
        $filterForm = $this->createForm(BlogPostFilterType::class);
        $filterForm->handleRequest($request);
    
        // Initialize the query builder to retrieve all blog posts
        $queryBuilder = $blogPostRepository->createQueryBuilder('bp');
    
        // If the form is submitted and valid, get the form data
        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $data = $filterForm->getData();
            $filterBy = $data['filterBy'];
    
            // Filtering logic based on $filterBy
            if ($filterBy === 'createdAt') {
                $queryBuilder->orderBy('bp.createdAt', 'DESC');
            } elseif ($filterBy === 'user') {
                // Logic to filter by user
            }
        }
    
        // Get all blog posts
        $blogPosts = $queryBuilder->getQuery()->getResult();
    
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
    
        // Get all comments
        $comments = $commentRepository->findAll();
    
        // Render the Twig template with the paginated blog posts, comment forms, and filter form
        return $this->render('blog_post/index.html.twig', [
            'blog_posts' => $pagination,
            'commentForms' => $commentForms, // Pass the comment forms to the template
            'comments' => $comments,
            'filterForm' => $filterForm->createView(), // Pass the filter form to the template
        ]);
    }
    

    #[Route('/new', name: 'app_blog_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,UserRepository $user,SluggerInterface $slugger): Response
    {

        
        $u=$user->find(1); // static use coz i signed up to add a user to work with  
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
    public function show(BlogPost $blogPost, UserRepository $userRepository,CommentRepository $commentRepository): Response
    {
        $user= $userRepository->findAll();
        $comment= $commentRepository -> findAll();

        return $this->render('blog_post/show.html.twig', [
            'blog_post' => $blogPost,
            'Comments' => $comment,
            'users' => $user,
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
    public function delete($id,Request $request,CommentRepository $commentRepository,BlogPostRepository $blogPostRepository, BlogPost $blogPost, EntityManagerInterface $entityManager): Response
    {

        $comments=$commentRepository->findAll();
        if ($this->isCsrfTokenValid('delete'.$blogPost->getId(), $request->request->get('_token'))) {
            foreach($comments as $comment)
            {
                if($comment->getBlogPostId()==$id)
                {
                    $comment->setBlogPostId(0);
                }

            }
            $entityManager->remove($blogPost);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_blog_post_index', [], Response::HTTP_SEE_OTHER);
    }
    //partie back office



    #[Route('/pdf', name: 'pdfbohmid', methods: ['GET'])]
    public function index_pdf(BlogPostRepository $blogPostRepository, Request $request): Response
    {
        // Création d'une nouvelle instance de la classe Dompdf
        $dompdf = new Dompdf();

        // Récupération des top 3 commandes par total price
        $blogposts = $blogPostRepository->findAll();
        $imagePath = $this->getParameter('kernel.project_dir') . '/public/img/1.jpg';
        // Encode the image to base64
        $imageData = base64_encode(file_get_contents($imagePath));
        $imageSrc = 'data:image/jpeg;base64,' . $imageData;
        // Génération du HTML à partir du template Twig 'commande/pdf_file.html.twig' en passant les top 3 commandes
        $html = $this->renderView('blog_post/pdf_file.html.twig', [
            'blogposts' => $blogposts,
            'imagePath' => $imageSrc,
        ]);

        // Récupération des options de Dompdf et activation du chargement des ressources à distance
        $options = $dompdf->getOptions();
        $options->set([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,  // Enable PHP rendering
        ]);

        $dompdf->setOptions($options);

        // Chargement du HTML généré dans Dompdf
        $dompdf->loadHtml($html);

        // Configuration du format de la page en A4 en mode portrait
        $dompdf->setPaper('A4', 'portrait');

        // Génération du PDF
        $dompdf->render();

        // Récupération du contenu du PDF généré
        $output = $dompdf->output();

        // Set headers for PDF download
        $response = new Response($output, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="archive.pdf"',
        ]);

        return $response;
    }


    #[Route('/back', name: 'app_blog_post_indexback', methods: ['GET'])]
    public function indexback(PaginatorInterface $paginator, BlogPostRepository $blogPostRepository, Request $request, CommentRepository $commentRepository): Response
    {
        // Create the form for filtering
        
        $blogPosts=$blogPostRepository->findAll();
        // Paginate the list of blog posts
        $pagination = $paginator->paginate(
            $blogPosts,
            $request->query->getInt('page', 1), // Current page
            3 // Posts per page
        );
    
        // Get all comments
        $comments = $commentRepository->findAll();
    
        // Render the Twig template with the paginated blog posts, comment forms, and filter form
        return $this->render('blog_post/indexback.html.twig', [
            'blog_posts' => $pagination,
            'comments' => $comments,
        ]);
    }




    #[Route('/show/back/{id}', name: 'app_blog_post_showback', methods: ['GET'])]
    public function showback(BlogPost $blogPost, UserRepository $userRepository,CommentRepository $commentRepository): Response
    {
        $user= $userRepository->findAll();
        $comment= $commentRepository -> findAll();

        return $this->render('blog_post/showback.html.twig', [
            'blog_post' => $blogPost,
            'Comments' => $comment,
            'users' => $user,
        ]);
    }




    #[Route('/delete/back/{id}', name: 'app_blog_post_deleteback', methods: ['POST'])]
    public function deleteback($id,Request $request,CommentRepository $commentRepository,BlogPostRepository $blogPostRepository, BlogPost $blogPost, EntityManagerInterface $entityManager): Response
    {

        $comments=$commentRepository->findAll();
        if ($this->isCsrfTokenValid('delete'.$blogPost->getId(), $request->request->get('_token'))) {
            foreach($comments as $comment)
            {
                if($comment->getBlogPostId()==$id)
                {
                    $comment->setBlogPostId(0);
                }

            }
            $entityManager->remove($blogPost);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_blog_post_indexback', [], Response::HTTP_SEE_OTHER);
    }

    //partie back office
}

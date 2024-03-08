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
use phpDocumentor\Reflection\Types\Null_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

use App\Form\BlogPostFilterType;
use Dompdf\Dompdf;
use Twilio\Rest\Client;
use UltraMsg\WhatsAppApi;

#[Route('/blog/post')]
class BlogPostController extends AbstractController
{
    #[Route('/li/{id}/like', name: 'like')]
    public function like(BlogPost $blogPost): Response
    {
        $blogPost->incrementLikes();
        // Enregistrez les modifications dans la base de données
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('app_blog_post_index');
    }
    #[Route('/likezazeazd/{id}/dislike', name: 'dislike')] public function dislike(BlogPost $blogPost): Response
    {

        if ($blogPost->checkAndDeleteIfRequired()) {
            $user = $blogPost->getUser()->getUsername(); 
            $title = $blogPost->getTitle(); 
            
            //$this->sendSMS($title, $user);
            
            $blogPost->incrementDislikes();


            return $this->redirectToRoute('app_blog_post_index');
        } else {
            $blogPost->incrementDislikes();

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('app_blog_post_index');
        }
    }
    #[Route('/', name: 'app_blog_post_index', methods: ['GET'])]
    public function index(PaginatorInterface $paginator, BlogPostRepository $blogPostRepository, Request $request, CommentRepository $commentRepository): Response
    {
        // Create the form for filtering
        $filterForm = $this->createForm(BlogPostFilterType::class);
        $filterForm->handleRequest($request);

        // Retrieve all blog posts
        $queryBuilder = $blogPostRepository->createQueryBuilder('bp');

        // The form is submitted and valid, get the form data
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

        // store comment for each blogpost
        $commentForms = [];

        // Create comment forms for each blog post
        foreach ($blogPosts as $blogPost) {

            // Create a new comment instance for each blog post
            $comment = new Comment();
            $comment->setBlogPostId($blogPost); // Associate the comment with the current blog post

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
            $request->query->getInt('page', 1), //  page courant
            2 // Posts in 1 page
        );

        // Get all comments
        
        $comments = $commentRepository->findAll();

        return $this->render('blog_post/index.html.twig', [
            'blog_posts' => $pagination,
            'commentForms' => $commentForms, // Pass the comment 
            'comments' => $comments,
            'filterForm' => $filterForm->createView(), 
        ]);
    }

    
//composer require ultramsg/whatsapp-php-sdk

    #[Route('/what', name: 'whatsapp')]
    public function envoyerMessageWhatsApp($user, $contenent, $blog, $date): Response
    {
        require_once __DIR__ . '/../../vendor/autoload.php';
        $ultramsg_token = "14sxaf9y87se6mpl"; // Ultramsg token
        $instance_id = "instance80613"; //  Ultramsg instance ID

        $client = new WhatsAppApi($ultramsg_token, $instance_id);

        $to = "+21621137130";
        $body = "Bonjour Monsieur Hedi,\n\n Nous vous informons qu'un nouveau post a passé nombre de vue superieur a 100. Voici les détails :\n\nUtilisateur : $user\n title : $contenent\n  Contenu : $blog\nDate de publication : $date\n\nVeuillez prendre les mesures nécessaires pour suivi.\n\nCordialement.";

        // Send a text message or image message
        $api = $client->sendChatMessage($to, $body);

       
        $image = "https://thumbs.dreamstime.com/b/information-12597316.jpg?w=768";
        $caption = "Image Caption";
        $priority = 10;
        $referenceId = "SDK";
        $nocache = false;
        $imageApi = $client->sendImageMessage($to, $image, $caption, $priority, $referenceId, $nocache);

        print_r($api); // Handle the response 
        print_r($imageApi); // Handle the response for the image message

  
        return new Response('WhatsApp messages sent successfully!');
    }
    #[Route('/new', name: 'app_blog_post_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Security $security, EntityManagerInterface $entityManager, UserRepository $user, SluggerInterface $slugger): Response
    {


        $u = $security->getUser();
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
        $this->addFlash('success', 'Le post a été ajouté avec succès.');

        return $this->renderForm('blog_post/new.html.twig', [
            'blog_post' => $blogPost,
            'form' => $form,
        ]);
        
    }

    #[Route('/show/{id}', name: 'app_blog_post_show', methods: ['GET'])]
    public function show(BlogPost $blogPost, EntityManagerInterface $entityManager, UserRepository $userRepository, CommentRepository $commentRepository): Response
    {
        $user = $blogPost->getUser()->getUsername(); 
        $title = $blogPost->getTitle(); 
        $contenent = $blogPost->getContent();
        $date = $blogPost->getCreatedAt()->format('Y-m-d'); 

        $currentViews = $blogPost->getNbVue();
        $blogPost->setNbVue($currentViews + 1);

        $entityManager->persist($blogPost);
        $entityManager->flush();
        if ($currentViews + 1 == 2) {


            // Appel la fct pour envoyer msg Whats

            $this->envoyerMessageWhatsApp($user, $title, $contenent, $date);
            $this->addFlash('success', 'Le Message whatsapp a été envoyé avec succès.');

        }
        $user = $userRepository->findAll();
        $comment = $commentRepository->findBy(['BlogPost_id' => $blogPost]);


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

        $this->addFlash('success', 'Le post a été modifié avec succès.');

        return $this->renderForm('blog_post/edit.html.twig', [
            'blog_post' => $blogPost,
            'form' => $form,
        ]);

    }

    #[Route('/delete/{id}', name: 'app_blog_post_delete', methods: ['POST'])]
    public function delete($id, Request $request, CommentRepository $commentRepository, BlogPostRepository $blogPostRepository, BlogPost $blogPost, EntityManagerInterface $entityManager): Response
    {

        $comments = $commentRepository->findBy(['BlogPost_id' => $blogPost]);
        if ($this->isCsrfTokenValid('delete' . $blogPost->getId(), $request->request->get('_token'))) {
            foreach ($comments as $comment) {
                $comment->setblognull();
            }
            $entityManager->remove($blogPost);
            $entityManager->flush();
        }

        $this->addFlash('success', 'Le post a été supprimé avec succès.');

        return $this->redirectToRoute('app_blog_post_index', [], Response::HTTP_SEE_OTHER);
    }


    public function sendSMS($title, $user): Response
    {
        $accountSid = 'AC5342fefb7b2076d295c362b3b6d04ef6'; //  Twilio Acc SID
        $authToken = '4be02e0afa9d7b80930ff5ad121eeacf'; // Twilio Auth Token
        $twilioPhoneNumber = '+12408521971'; // Twilio phone number

        $to = '+21621137130'; // 
        $message = "Le  post de le utilisateur '$user' de titre '$title' a un nombre de dislikes supérieur au nombre de likes!";

        $client = new Client($accountSid, $authToken);

        $client->messages->create(
            $to,
            [
                'from' => $twilioPhoneNumber,
                'body' => $message
            ]
        );

        return new Response('SMS sent successfully!');
    }
    //partie back office


    #[Route('/calendrier', name: 'calendrier', methods: ['GET'])]
    public function calendrier(BlogPostRepository $blogPostRepository)
    {
        // Récupération de tous les blogs enregistrés 
        $blogs = $blogPostRepository->findAll();

        // Création d'un tableau de rendez-vous vide
        $rdvs = [];

        // Parcours de tous l blogs pour les ajouter au tableau de blogs
        foreach ($blogs as $blog) {
            // Création d'un tableau pour chaque blog
            $rdv = [];

            // Ajout de l'identifiant de blog au tableau de blogs
            $rdv['id'] = $blog->getId();

            // Ajout du titre de blog au tableau de blogs
            $rdv['title'] = $blog->getTitle();

            // Ajout de la description de blog au tableau de blogs
            $rdv['description'] = $blog->getContent();

            // Ajout de la date de début de blog au tableau de blogs
            $rdv['start'] = $blog->getCreatedAt()->format('Y-m-d');

            // Ajout de la date de fin de blog au tableau de blogs
            // $rdv['end'] = $blog->getDateFin()->format('Y-m-d');

            // Ajout de la couleur de fond de blog au tableau de blogs
            $rdv['backgroundColor'] = '#FF7474';

            // Ajout de la couleur de bordure de blog au tableau de blog
            $rdv['borderColor'] = 'blue';

            // Ajout de la couleur de texte de blog au tableau de blog
            $rdv['textColor'] = '#000000';
            $rdv['color'] = '#ffffff';
            // Désactivation de la modification de blog dans le calendrier
            $rdv['editable'] = false;
            $rdv['url'] = $this->generateUrl('app_blog_post_showback', ['id' => $blog->getId()]);


            // Ajout du tableau de rendez-vous de blog au tableau de rendez-vous global
            $rdvs[] = $rdv;
        }

        // tableau de rdvs global au JSON
        $data = json_encode($rdvs);

        // page du calendrier avec les données encodées
        return $this->render('blog_post/calendrier.html.twig', compact('data'));
    }



    #[Route('/pdf', name: 'pdfbohmid', methods: ['GET'])]
    public function index_pdf(BlogPostRepository $blogPostRepository, Request $request): Response
    {
        // nouvelle iinstnceof Dompdf
        $dompdf = new Dompdf();

        
        $blogposts = $blogPostRepository->findAll();
        $imagePath = $this->getParameter('kernel.project_dir') . '/public/img/pdfBackground.png';
       
        $imageData = base64_encode(file_get_contents($imagePath));
        $imageSrc = 'data:image/jpeg;base64,' . $imageData;
        // Génération du HTML à partir du template Twig 'blog_post/pdf_file.html.twig' 
        $html = $this->renderView('blog_post/pdf_file.html.twig', [
            'blogposts' => $blogposts,
            'imagePath' => $imageSrc,
        ]);

        // Récupé options de Dompdf 
        $options = $dompdf->getOptions();
        $options->set([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,  // Enable PHP rendering
        ]);

        $dompdf->setOptions($options);

        // Chargement du HTML généré 
        $dompdf->loadHtml($html);

        // C format de la page en A4 en md portrait
        $dompdf->setPaper('A4', 'portrait');

        // Génére du PDF
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

        $blogPosts = $blogPostRepository->findAll();
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




    #[Route('/back/show/{id}', name: 'app_blog_post_showback', methods: ['GET'])]
    public function showback(BlogPost $blogPost, UserRepository $userRepository, CommentRepository $commentRepository): Response
    {
        $user = $userRepository->findAll();
        $comment = $commentRepository->findBy(['BlogPost_id' => $blogPost]);

        return $this->render('blog_post/showback.html.twig', [
            'blog_post' => $blogPost,
            'Comments' => $comment,
            'users' => $user,
        ]);
    }




    #[Route('/back/delete/{id}', name: 'app_blog_post_deleteback', methods: ['POST'])]
    public function deleteback($id, Request $request, CommentRepository $commentRepository, BlogPostRepository $blogPostRepository, BlogPost $blogPost, EntityManagerInterface $entityManager): Response
    {

        $comments = $commentRepository->findBy(['BlogPost_id' => $blogPost]);
        if ($this->isCsrfTokenValid('delete' . $blogPost->getId(), $request->request->get('_token'))) {
            foreach ($comments as $comment) {
                $comment->setblognull();
            }
            $entityManager->remove($blogPost);
            $entityManager->flush();
        }
        $this->addFlash('success', 'Le post a été supprimé avec succès.');

        return $this->redirectToRoute('app_blog_post_indexback', [], Response::HTTP_SEE_OTHER);
    }




    #[Route('/back/top-blogs', name: 'top_blogs')]
    public function topblog(BlogPostRepository $blogPostRepository): Response
    {
        // top blogs with the number of comments for each blog
        $topBlogs = $blogPostRepository->findTopBlogs();

        return $this->render('blog_post/topblog.html.twig', [
            'topBlogs' => $topBlogs,
        ]);
    }

    //partie back office





}

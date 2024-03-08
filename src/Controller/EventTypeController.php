<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/event/type')]
class EventTypeController extends AbstractController
{
    #[Route('/', name: 'app_event_type_index', methods: ['GET'])]
    public function index(EventRepository $eventRepository): Response
    {
        return $this->render('event_type/index.html.twig', [
            'events' => $eventRepository->findAll(),
        ]);
    }

    #[Route('/events', name: 'app_list', methods: ['GET'])]
    public function list(): Response
    {
        $events = $this->getDoctrine()->getRepository(Event::class)->findAll();

        return $this->render('event_type/list.html.twig', [
            'events' => $events,
        ]);
    }

    #[Route('/front', name: 'app_evenement_indexFront', methods: ['GET'])]
    //public function indexFront(EventRepository $eventRepository,EntityManagerInterface $entityManager,Request $request,PaginatorInterface $paginator): Response
   public function indexFront(EventRepository $eventRepository, EntityManagerInterface $entityManager, Request $request, PaginatorInterface $paginator): Response
    {
        // Retrieve all eve
        $events = $entityManager
            ->getRepository(Event::class)
            ->findAll();

        // Paginate the events
        $pagination = $paginator->paginate(
            $events, /* query NOT result */
            $request->query->getInt('page', 1),4 // Number of items per page
        );

        // Render the template with the paginated events
        return $this->render('event_type/list.html.twig', [
            'events' => $pagination, // Pass the paginated data with the correct variable name
        ]);
    }

    #[Route('/searchevent', name: 'searchevent')]
    public function searchMateriel(Request $request, EventRepository $eventRepository): Response
    {
     $query = $request->query->get('query');
     $events = [];

        if ($query) {
         $events = $eventRepository->findByLibelle($query);
     } else {
         $events = $eventRepository->findAll();
     }

     return $this->render('event_type/search.html.twig', [
         'events' => $events,
         'query' => $query,
     ]);
    }
    #[Route('/new', name: 'app_event_type_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('app_event_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event_type/new.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }
    #[Route('/calendar', name: 'app_event_calendar')]
    public function volCalendar(EventRepository $eventRepository): Response
    {
        // Fetch vols from the repository
        $events = $eventRepository->findAll();

        // Pass vols to the Twig template
        return $this->render('event_type/agenda.html.twig', [
            'events' => $events,
        ]);
    }
 


    #[Route('/{id}', name: 'app_event_type_show', methods: ['GET'])]
    public function show(Event $event): Response
    {
        
        return $this->render('event_type/show.html.twig', [
            'event' => $event,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_event_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_event_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('event_type/edit.html.twig', [
            'event' => $event,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_event_type_delete', methods: ['POST'])]
    public function delete(Request $request, Event $event, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_event_type_index', [], Response::HTTP_SEE_OTHER);
    }
}

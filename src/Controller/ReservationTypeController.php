<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Entity\Event;

use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;

#[Route('/reservation/type')]
class ReservationTypeController extends AbstractController
{
    #[Route('/', name: 'app_reservation_type_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation_type/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_reservation_type_new', methods: ['GET', 'POST'])]
    public function new($id, Request $request, EntityManagerInterface $entityManager): Response
    {


        $event = $this->getDoctrine()->getRepository(Event::class)->find($id);
        if (!$event) {
            throw $this->createNotFoundException('Event not found');
        }
        $reservation = new Reservation();
       $reservation->setRelation($event);

        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservation);
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_indexFront', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation_type/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }
    #[Route('/exportpdf', name: 'exportpdf')]
    public function exportToPdf(ReservationRepository $reservationRepository): Response
{
    // Récupérer les données de matériel depuis votre base de données
    $reservation = $reservationRepository->findAll();

    // Créer le tableau de données pour le PDF
    $tableData = [];
    foreach ($reservation as $reservation) {
        $tableData[] = [
            'Nom' => $reservation->getNom(),
            'prenom' => $reservation->getPrenom(),
            'email' => $reservation->getEmail(),
            'age' => $reservation->getAge(),
            'motorise' => $reservation->isMotorise(),

        ];
    }

    // Créer le PDF avec Dompdf
    $dompdf=new Dompdf();
    $html = $this->renderView('reservation_type/export-pdf.html.twig', [
        'tableData' => $tableData,
    ]);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();

    // Envoyer le PDF au navigateur
    $response = new Response($dompdf->output(), 200, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="materiels.pdf"',
    ]);
    return $response;
}

    
    #[Route('/stat', name: 'reservation_stat', methods: ['GET'])]
    public function reservationStatistics(ReservationRepository $reservationRepository): Response
    {
        // Get all reservations from the repository
        $reservations = $reservationRepository->findAll();
    
        // Initialize counts for motorized and non-motorized reservations
        $motorizedCount = 0;
        $nonMotorizedCount = 0;
    
        // Calculate statistics
        foreach ($reservations as $reservation) {
            // Check if the reservation is motorized or not
            if ($reservation->isMotorise()) {
                $motorizedCount++;
            } else {
                $nonMotorizedCount++;
            }
        }
    
        // Prepare statistics data
        $statistics = [
            'Motorized' => $motorizedCount,
            'Non-Motorized' => $nonMotorizedCount,
        ];
    
        // Render the template with statistics
        return $this->render('reservation_type/statistics.html.twig', [
            'statistics' => $statistics,
        ]);
    }
    
    #[Route('/{id}', name: 'app_reservation_type_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation_type/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation_type/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_type_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_type_index', [], Response::HTTP_SEE_OTHER);
    }
}

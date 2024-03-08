<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'search')]
    public function search(Request $request): JsonResponse
    {
        $query = $request->query->get('q'); // Get the search query from the request

        // Perform the search based on the query and retrieve the results
        $results = $this->searchLogic($query);

        // Return the results as JSON
        return new JsonResponse($results);
    }

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    private function searchLogic(string $query): array
    {
        $userRepository = $this->entityManager->getRepository(User::class);

        // Perform the search query to retrieve matching user names
        $results = $userRepository->createQueryBuilder('u')
            ->where('u.username LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->getQuery()
            ->getResult();

        // Transform the results into the desired format for the response
        $formattedResults = [];
        foreach ($results as $user) {
            $formattedResults[] = [
                'title' => $user->getUsername(),
                'description' => 'User ID: ' . $user->getId(),
            ];
        }

        return $formattedResults;
    }}

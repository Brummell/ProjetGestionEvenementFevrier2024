<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Evenement;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $category = $entityManager->getRepository(Category::class)->findAll();
        foreach($category as $cat){
            $cat->setPicture(base64_encode(stream_get_contents($cat->getPicture())));
        }
        return $this->render('home/index.html.twig', [
            'categories'=>$category
        ]);
    }
    #[Route('/calendar', name: 'calendar')]
    public function calendar(EntityManagerInterface $entityManager): Response
    {
        $category = $entityManager->getRepository(Category::class)->findAll();
        $lieu = $entityManager->getRepository(Evenement::class)->findAll();
        foreach($category as $cat){
            $cat->setPicture(base64_encode(stream_get_contents($cat->getPicture())));
        }
        return $this->render('calendar.html.twig',[
            'categories'=>$category,
            'lieu'=>$lieu
        ]);
    }

    #[Route('/toutes_les_evenements', name: 'toutes_les_evenements')]
    public function toutes_les_evenements(EntityManagerInterface $entityManager): Response
    {
        $events = $entityManager->getRepository(Evenement::class)->findAll();
        foreach($events as $ev){
            $ev->setPicture(base64_encode(stream_get_contents($ev->getPicture())));
        }
        return $this->render('mes_pages/toutes_les_evenements.html.twig',[
            'evenements' => $events
        ]);
    }
    #[Route('/jeParticipe/{event}', name: 'app_jeParticipe')]
    public function jeParticipe(EntityManagerInterface $entityManager,Request $request,#[CurrentUser()] ?User $user,int $event): Response
    {
        if(!empty($user)){
            $evenement = $entityManager->getRepository(Evenement::class)->find($event);
            $evenement->addUserEvent($user);
            $user->addEventUser($evenement);
            $entityManager->flush();
            $this->addFlash('success', 'Vous participez à l\'évenement');
            return $this->redirectToRoute('toutes_les_evenements'); 
        }else{
            $this->addFlash('error', 'Une erreur s\'est produite ');
            return $this->redirectToRoute('toutes_les_evenements'); 
        }
        
    }

    #[Route('/mes_participations',name:'app_mesParticipations')]
    public function mes_participations(EntityManagerInterface $entityManager,#[CurrentUser()] ?User $user){
        $evenementRepository = $entityManager->getRepository(Evenement::class);
        $queryBuilder = $evenementRepository->getUserEventsQueryBuilder($user);

        $results = $queryBuilder->getQuery()->getResult();
        foreach($results as $result){
            $result->setPicture(base64_encode(stream_get_contents($result->getPicture())));
        }
        return $this->render('mes_pages/mes_participations.html.twig',[
            'evenements' => $results
        ]);
    }
    #[Route('/calendar/events',name:'calendarEvents',methods: ['GET'])]
    public function calendarEvents(EntityManagerInterface $entityManager,#[CurrentUser()] ?User $user): JsonResponse
    {
        // Récupérez vos événements depuis la base de données ou d'autres sources
        $evenementRepository = $entityManager->getRepository(Evenement::class);
        $queryBuilder = $evenementRepository->getUserEventsQueryBuilder($user);

        $events = $queryBuilder->getQuery()->getResult();

        // Formattez les données pour FullCalendar
        $formattedEvents = [];
        foreach ($events as $event) {
            $formattedEvents[] = [
                'title' => $event->getName(),
                'start' => $event->getDateDeb()->format('Y-m-d H:i:s'),
                'category'=>$event->getCategory(),
                'lieu'=>$event->getLieu()
                // Ajoutez d'autres propriétés si nécessaire, comme 'end'
            ];
        }

        // Retournez les données en tant que réponse JSON
        return new JsonResponse($formattedEvents);
    }
}

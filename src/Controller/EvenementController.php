<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EvenementType;
use App\Repository\EvenementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/evenement')]
class EvenementController extends AbstractController
{
    #[Route('/', name: 'app_evenement_index', methods: ['GET'])]
    public function index(EvenementRepository $evenementRepository,EntityManagerInterface $entityManager): Response
    {
        $events = $entityManager->getRepository(Evenement::class)->findAll();
        foreach($events as $ev){
            $ev->setPicture(base64_encode(stream_get_contents($ev->getPicture())));
        }
        return $this->render('evenement/index.html.twig', [
            'evenements' => $events,
        ]);
    }

    #[Route('/new', name: 'app_evenement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evenement_show', methods: ['GET'])]
    public function show(Evenement $evenement,EntityManagerInterface $entityManager): Response
    {
        $events = $entityManager->getRepository(Evenement::class)->find($evenement);
    
        $events->setPicture(base64_encode(stream_get_contents($events->getPicture())));

        return $this->render('evenement/show.html.twig', [
            'evenement' => $events,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_evenement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        $oldPictureContent = $evenement->getPicture();

        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);
        
        //dd(base64_encode(stream_get_contents($oldPictureContent->getPicture())));
        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $evenement->getPicture();
            if ($uploadedFile instanceof UploadedFile) {
                // Lire le contenu du nouveau fichier
                $fileContents = file_get_contents($uploadedFile->getPathname());
                $evenement->setPicture($fileContents);
            } elseif (empty($uploadedFile)) {
                
                $evenement->setPicture($oldPictureContent);
            }
            
            $entityManager->flush();
            return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('evenement/edit.html.twig', [
            'evenement' => $evenement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_evenement_delete', methods: ['POST'])]
    public function delete(Request $request, Evenement $evenement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_evenement_index', [], Response::HTTP_SEE_OTHER);
    }
}

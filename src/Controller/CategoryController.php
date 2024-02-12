<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'app_category_index', methods: ['GET'])]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $category = $entityManager->getRepository(Category::class)->findAll();
        foreach($category as $cat){
            $cat->setPicture(base64_encode(stream_get_contents($cat->getPicture())));
        }
        return $this->render('category/index.html.twig', [
            'categories' => $category,
        ]);
    }

    #[Route('/new', name: 'app_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/new.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_category_show', methods: ['GET'])]
    public function show(Category $category,EntityManagerInterface $entityManager): Response
    {
        $categorie = $entityManager->getRepository(Category::class)->find($category);
        $categorie->setPicture(base64_encode(stream_get_contents($categorie->getPicture())));
        return $this->render('category/show.html.twig', [
            'category' => $categorie,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    { 
        $oldPictureContent = $category->getPicture();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $category->getPicture();

            // Assurez-vous que le fichier a été téléchargé
            if ($uploadedFile instanceof UploadedFile) {
                // Lisez le contenu du fichier
                $fileContents = file_get_contents($uploadedFile->getPathname());

                // Faites ce que vous devez faire avec le contenu du fichier

                // Par exemple, enregistrez le contenu dans une colonne BLOB ou autre traitement
                $category->setPicture($fileContents);
            }elseif (empty($uploadedFile)) {
                // Si le champ 'picture' est vide ou n'a pas de fichier téléchargé,
                // rétablissez l'ancienne valeur du champ 'picture'
                //dd($oldPictureContent);
                $category->setPicture($oldPictureContent);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_category_delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_category_index', [], Response::HTTP_SEE_OTHER);
    }
}

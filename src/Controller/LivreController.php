<?php

namespace App\Controller;
//src/controller/LivreController.php
use App\Entity\Livre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LivreController extends AbstractController
{
    //afficher tous les livres
    #[Route('/livre', name: 'app_livre')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $livres=$entityManager->getRepository(Livre::class)->findAll();
        return $this->render('livre/index.html.twig',['livres'=>$livres]);
    }
    #[Route('/livre/ajout',name:'app_livre_ajouter',methods:['Post','GET'])]
    public function ajouter(Request $request, EntityManagerInterface $entityManager):Response
    {
        if ($request->isMethod('Post')){
            $livre=new Livre();
            $livre->setTitre($request->request->get('titre'));
            $livre->setAuteur($request->request->get('auteur'));
            $livre->setDateDePublication(new \DateTime($request->request->get('datePublication')));
            $livre->setDescription($request->request->get('description'));
            $entityManager->persist($livre);
            $entityManager->flush();
            return $this->redirectToRoute('app_livre');
        }
        return $this->render('livre/ajouter.html.twig');
    }
    #[Route('/livre/{id}/supprimer', name: 'app_livre_supprimer', methods: ['POST'])]
    public function supprimer(int $id, EntityManagerInterface $entityManager): Response
    {
        $livre = $entityManager->getRepository(Livre::class)->find($id);
        if ($livre) {
            $entityManager->remove($livre);
            $entityManager->flush();
        }
        
        return $this->redirectToRoute('app_livre');
    }
    #[Route('/livres/{id}/description', name: 'app_livre_description')]
    public function desc(EntityManagerInterface $entityManager,int $id): Response
    {   $livres= $entityManager ->getRepository(Livre::class)->find($id);
        return $this->render('livre/description.html.twig', [
            'livre' => $livres,
        ]);
    }

    }


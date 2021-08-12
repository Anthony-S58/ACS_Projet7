<?php

namespace App\Controller;

use App\Entity\Fichiers;
use App\Entity\Produits;
use App\Entity\Images;
use App\Form\ProduitsType;
use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/produits")
 */
class ProduitsController extends AbstractController
{
    /**
     * @Route("/", name="produits_index", methods={"GET"})
     */
    public function index(ProduitsRepository $produitsRepository): Response
    {
        return $this->render('produits/index.html.twig', [
            'produits' => $produitsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="produits_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $produit = new Produits();
        $form = $this->createForm(ProduitsType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //on récupère les images transmises
            $images = $form->get('images')->getData();

            //on boucle sur les images
            foreach ($images as $image) {
                //on génère un nouveau nom de fichier
                $imgnom = md5(uniqid()) . '.' . $image->guessExtension();

                //on copie le fichier dans le dossier upload
                $image->move(
                    $this->getParameter('images_directory'),
                    $imgnom
                );

                //on stocke l'image dans la base de données (son nom)
                $img = new Images();
                $img->setName($imgnom);
                $produit->addImage($img);
            }

            //on récupère les fichiers transmis
            $fichiers = $form->get('fichiers')->getData();

            //on boucle sur les images
            foreach ($fichiers as $fichier) {
                //on génère un nouveau nom de fichier
                $fichiername = md5(uniqid()) . '.' . $fichier->guessExtension();

                //on copie le fichier dans le dossier upload
                $fichier->move(
                    $this->getParameter('fichiers_directory'),
                    $fichiername
                );

                //on stocke le fichier dans la base de données (son nom)
                $fich = new Fichiers();
                $fich->setName($fichiername);
                $produit->addFichier($fich);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('produits_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produits/new.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="produits_show", methods={"GET"})
     */
    public function show(Produits $produit): Response
    {
        return $this->render('produits/show.html.twig', [
            'produit' => $produit,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="produits_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Produits $produit): Response
    {
        $form = $this->createForm(ProduitsType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //on récupère les images transmises
            $images = $form->get('images')->getData();

            //on boucle sur les images
            foreach ($images as $image) {
                //on génère un nouveau nom de fichier
                $imgnom = md5(uniqid()) . '.' . $image->guessExtension();

                //on copie le fichier dans le dossier upload
                $image->move(
                    $this->getParameter('images_directory'),
                    $imgnom
                );

                //on stocke l'image dans la base de données (son nom)
                $img = new Images();
                $img->setName($imgnom);
                $produit->addImage($img);
            }

            //on récupère les fichiers transmis
            $fichiers = $form->get('fichiers')->getData();

            //on boucle sur les fichiers
            foreach ($fichiers as $fichier) {
                //on génère un nouveau nom de fichier
                $fichiername = md5(uniqid()) . '.' . $fichier->guessExtension();

                //on copie le fichier dans le dossier upload
                $fichier->move(
                    $this->getParameter('fichiers_directory'),
                    $fichiername
                );

                //on stocke le fichier dans la base de données (son nom)
                $fich = new Fichiers();
                $fich->setName($fichiername);
                $produit->addFichier($fich);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('produits_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('produits/edit.html.twig', [
            'produit' => $produit,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="produits_delete", methods={"POST"})
     */
    public function delete(Request $request, Produits $produit): Response
    {
        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('produits_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/supprime/image/{id}", name="produits_delete_image", methods={"DELETE"})
     */
    public function deleteImage(Images $image, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        //on verifie si le token est valide
        if ($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])) {
            // on récupère le nom ce l'image
            $nom = $image->getName();
            //on supprime le fichier
            unlink($this->getParameter('images_directory') . '/' . $nom);

            //on supprime de la base de données
            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();

            //on répond en json
            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }

    /**
     * @Route("/supprime/fichier/{id}", name="produits_delete_fichier", methods={"DELETE"})
     */
    public function deleteFichier(Fichiers $fichier, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        //on verifie si le token est valide
        if ($this->isCsrfTokenValid('delete' . $fichier->getId(), $data['_token'])) {
            // on récupère le nom du fichiere
            $name = $fichier->getName();
            //on supprime le fichier
            unlink($this->getParameter('fichiers_directory') . '/' . $name);

            //on supprime de la base de données
            $en = $this->getDoctrine()->getManager();
            $en->remove($fichier);
            $en->flush();

            //on répond en json
            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }
}

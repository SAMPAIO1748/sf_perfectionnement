<?php

namespace App\Controller\Admin;

use App\Entity\Image;
use App\Form\ImageType;
use App\Repository\ImageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

class AdminImageController extends AbstractController
{

    public function createImage(
        Request $request,
        SluggerInterface $sluggerInterface,
        EntityManagerInterface $entityManagerInterface
    ) {
        $image = new Image();

        $imageForm = $this->createForm(ImageType::class, $image);

        $imageForm->handleRequest($request);

        if ($imageForm->isSubmitted() && $imageForm->isValid()) {

            $imageFile = $imageForm->get('src')->getData();

            if ($imageFile) {


                $originalFileName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFileName = $sluggerInterface->slug($originalFileName);

                $newFileName = $safeFileName . '-' . uniqid() . '.' . $imageFile->guessExtension();

                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFileName
                );

                $image->setSrc($newFileName);
            }

            $entityManagerInterface->persist($image);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('admin_article_list');
        }

        return $this->render("admin/imageform.html.twig", ['imageForm' => $imageForm->createView()]);
    }

    public function updateImage(
        $id,
        Request $request,
        SluggerInterface $sluggerInterface,
        EntityManagerInterface $entityManagerInterface,
        ImageRepository $imageRepository
    ) {

        $image = $imageRepository->find($id);

        $imageForm = $this->createForm(ImageType::class, $image);

        $imageForm->handleRequest($request);

        if ($imageForm->isSubmitted() && $imageForm->isValid()) {

            $imageFile = $imageForm->get('src')->getData();

            if ($imageFile) {


                $originalFileName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFileName = $sluggerInterface->slug($originalFileName);

                $newFileName = $safeFileName . '-' . uniqid() . '.' . $imageFile->guessExtension();

                $imageFile->move(
                    $this->getParameter('images_directory'),
                    $newFileName
                );

                $image->setSrc($newFileName);
            }

            $entityManagerInterface->persist($image);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('admin_article_list');
        }

        return $this->render("admin/imageform.html.twig", ['imageForm' => $imageForm->createView()]);
    }

    public function deleteImage(
        $id,
        ImageRepository $imageRepository,
        EntityManagerInterface $entityManagerInterface
    ) {
        $image = $imageRepository->find($id);

        $entityManagerInterface->remove($image);

        $entityManagerInterface->flush();

        return $this->redirectToRoute('admin_article_list');
    }

    public function imageList(ImageRepository $imageRepository)
    {

        $images = $imageRepository->findAll();

        return $this->render("admin/images.html.twig", ['images' => $images]);
    }

    public function image_show($id, ImageRepository $imageRepository)
    {
        $image = $imageRepository->find($id);

        return $this->render("admin/image.html.twig", ['image' => $image]);
    }
}

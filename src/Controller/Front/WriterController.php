<?php

namespace App\Controller\Front;

use App\Repository\WriterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class WriterController extends AbstractController
{

    /**
     * @Route("writers", name="writer_list")
     */
    public function writerList(WriterRepository $writerRepository)
    {
        $writers = $writerRepository->findAll();

        return $this->render("front/writers.html.twig", ['writers' => $writers]);
    }

    /**
     * @Route("writer/{id}", name="writer_show")
     */
    public function writerShow($id, WriterRepository $writerRepository)
    {
        $writer = $writerRepository->find($id);

        return $this->render("front/writer.html.twig", ['writer' => $writer]);
    }
}

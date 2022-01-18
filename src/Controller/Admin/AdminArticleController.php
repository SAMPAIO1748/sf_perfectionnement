<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminArticleController extends AbstractController
{

    public function articleList(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findAll();

        return $this->render("admin/articles.html.twig", ['articles' => $articles]);
    }

    public function articleShow($id, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->find($id);

        return $this->render("admin/article.html.twig", ['article' => $article]);
    }

    public function articleUpdate(
        $id,
        ArticleRepository $articleRepository,
        Request $request,
        EntityManagerInterface $entityManagerInterface
    ) {
        $article = $articleRepository->find($id);

        $articleForm = $this->createForm(ArticleType::class, $article);

        $articleForm->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            $entityManagerInterface->persist($article);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('admin_article_list');
        }

        return $this->render("admin/articleform.html.twig", ['articleForm' => $articleForm->createView()]);
    }

    public function createArticle(
        EntityManagerInterface $entityManagerInterface,
        Request $request
    ) {

        $article = new Article();

        $articleForm = $this->createForm(ArticleType::class, $article);

        $articleForm->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid()) {

            $article->setDate(new \DateTime("NOW"));
            $entityManagerInterface->persist($article);
            $entityManagerInterface->flush();

            return $this->redirectToRoute('admin_article_list');
        }

        return $this->render("admin/articleform.html.twig", ['articleForm' => $articleForm->createView()]);
    }

    public function deletetArticle(
        $id,
        EntityManagerInterface $entityManagerInterface,
        ArticleRepository $articleRepository
    ) {
        $article = $articleRepository->find($id);

        $entityManagerInterface->remove($article);

        $entityManagerInterface->flush();

        return $this->redirectToRoute("admin_article_list");
    }
}

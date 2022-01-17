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

    /**
     * @Route("admin/articles", name="admin_article_list")
     */
    public function articleList(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findAll();

        return $this->render("admin/articles.html.twig", ['articles' => $articles]);
    }

    /**
     * @Route("admin/article/{id}", name="admin_article_show")
     */
    public function articleShow($id, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->find($id);

        return $this->render("admin/article.html.twig", ['article' => $article]);
    }

    /**
     * @Route("/admin/update/article/{id}", name="update_article")
     */
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

    /**
     * @Route("/admin/create/article", name="create_article")
     */
    public function creatArticle(
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

    /**
     * @Route("admin/delete/article/{id}", name="delete_article")
     */
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

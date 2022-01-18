<?php

namespace App\Controller\Front;

use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{

    /**
     * @Route("articles", name="article_list")
     */
    public function articleList(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findAll();

        return $this->render("front/articles.html.twig", ['articles' => $articles]);
    }

    /**
     * @Route("article/{id}", name="article_show")
     */
    public function articleShow($id, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->find($id);

        return $this->render("front/article.html.twig", ['article' => $article]);
    }

    /**
     * @Route("search", name="front_search")
     */
    public function frontSearch()
    {
    }
}

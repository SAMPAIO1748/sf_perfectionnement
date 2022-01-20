<?php

namespace App\Controller\Front;

use App\Entity\Dislike;
use App\Entity\Like;
use App\Repository\ArticleRepository;
use App\Repository\DislikeRepository;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{

    public function articleList(ArticleRepository $articleRepository)
    {
        $articles = $articleRepository->findAll();

        return $this->render("front/articles.html.twig", ['articles' => $articles]);
    }

    public function articleShow($id, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->find($id);

        return $this->render("front/article.html.twig", ['article' => $article]);
    }

    public function frontSearch(Request $request, ArticleRepository $articleRepository)
    {

        // Récupérer les données rentrées dans le formulaire
        $term = $request->query->get('term');
        // query correspond à l'outil qui permet de récupérer les données d'un formulaire en get
        // pour un formulaire en post on utilise request

        $articles = $articleRepository->searchByTerm($term);

        return $this->render('front/search.html.twig', ['articles' => $articles, 'term' => $term]);
    }

    /**
     * @Route("like/article/{id}", name="article_like")
     */
    public function likeArticle(
        $id,
        ArticleRepository $articleRepository,
        LikeRepository $likeRepository,
        EntityManagerInterface $entityManagerInterface,
        DislikeRepository $dislikeRepository
    ) {

        $article = $articleRepository->find($id);
        $user = $this->getUser();

        if (!$user) {
            return $this->json(
                [
                    'code' => 403,
                    'message' => "Vous devez vous connecter"
                ],
                403
            );
        }

        if ($article->isLikeByUser($user)) {
            $like = $likeRepository->findOneBy(
                [
                    'article' => $article,
                    'user' => $user
                ]
            );

            $entityManagerInterface->remove($like);
            $entityManagerInterface->flush();

            return $this->json([
                'code' => 200,
                'message' => "Like supprimé",
                'likes' => $likeRepository->count(['article' => $article])
            ], 200);
        }

        if ($article->isDislikeByUser($user)) {
            $dislike = $dislikeRepository->findOneBy([
                'article' => $article,
                'user' => $user
            ]);

            $entityManagerInterface->remove($dislike);

            $like = new Like();

            $like->setArticle($article);
            $like->setUser($user);

            $entityManagerInterface->persist($like);
            $entityManagerInterface->flush();

            return $this->json([
                'code' => 200,
                'message' => "Like ajouté et dislike supprimé",
                'likes' => $likeRepository->count(['article' => $article]),
                'dislikes' => $dislikeRepository->count(['article' => $article])
            ], 200);
        }


        $like = new Like();

        $like->setArticle($article);
        $like->setUser($user);

        $entityManagerInterface->persist($like);
        $entityManagerInterface->flush();

        return $this->json([
            'code' => 200,
            'message' => "Like ajouté",
            'likes' => $likeRepository->count(['article' => $article])
        ], 200);
    }

    /**
     * @Route("dislike/article/{id}", name="article_dislike")
     */
    public function dislikeArticle(
        $id,
        ArticleRepository $articleRepository,
        EntityManagerInterface $entityManagerInterface,
        DislikeRepository $dislikeRepository,
        LikeRepository $likeRepository
    ) {

        $article = $articleRepository->find($id);
        $user = $this->getUser();

        if (!$user) {
            return $this->json(
                [
                    'code' => 403,
                    'message' => "Vous devez vous connecter"
                ],
                403
            );
        }

        if ($article->isDislikeByUser($user)) {
            $dislike = $dislikeRepository->findOneBy([
                'article' => $article,
                'user' => $user
            ]);

            $entityManagerInterface->remove($dislike);
            $entityManagerInterface->flush();

            return $this->json([
                'code' => 200,
                'message' => "Le dislike a été supprimé",
                'dislikes' => $dislikeRepository->count(['article' => $article])
            ], 200);
        }

        if ($article->isLikeByUser($user)) {
            $like = $likeRepository->findOneBy([
                'article' => $article,
                'user' => $user
            ]);

            $entityManagerInterface->remove($like);

            $dislike = new Dislike();
            $dislike->setArticle($article);
            $dislike->setUser($user);

            $entityManagerInterface->persist($dislike);
            $entityManagerInterface->flush();

            return $this->json([
                'code' => 200,
                'message' => "like supprimé et dislike ajouté",
                'dislikes' => $dislikeRepository->count(['article' => $article]),
                'likes' => $likeRepository->count(['article' => $article])
            ], 200);
        }


        $dislike = new Dislike();

        $dislike->setArticle($article);
        $dislike->setUser($user);

        $entityManagerInterface->persist($dislike);
        $entityManagerInterface->flush();

        return $this->json([
            'code' => 200,
            'message' => "Dislike ajouté",
            'dislikes' => $dislikeRepository->count(['article' => $article])
        ], 200);
    }
}

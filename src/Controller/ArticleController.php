<?php 

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as AbstractController;

use App\Entity\Article;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="index", methods="GET")
     */
    public function index(EntityManagerInterface $entityManager)
    {
        $articles = $entityManager->getRepository(Article::class)->findAll();
        // echo '<pre>';
        // var_dump($articles); die();

        // return new Response('<html><body>Hello</body></html>');
        return $this->render('articles/index.html.twig', ['articles' => $articles]);
    }

    /** 
     * @Route("/article/{id}", name="article_show")
     */
    public function showId(EntityManagerInterface $entityManager, int $id) 
    {
        $article = $entityManager->getRepository(Article::class)->find($id);

        return $this->render('articles/show.html.twig', ['article' => $article]);
    }

    /**
     * @Route("/article/save", name="save")
     */
    public function save(EntityManagerInterface $entityManager): Response
    {
        // $entityManager = $this->getDoctrine()->getManager();

        $article = new Article();
        $article->setTitle('Article two');
        $article->setBody('This is the body of the article');

        $entityManager->persist($article);

        $entityManager->flush();

        return new Response('Saved an article with id of '. $article->getId());
    }
}
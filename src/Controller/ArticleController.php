<?php 

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as ControllerAbstractController;

class ArticleController extends ControllerAbstractController
{
    /**
     * @Route("/", name="index", methods="GET")
     */
    public function index()
    {
        $articles = [
            'Article 1',
            'Article 2'
        ];

        // return new Response('<html><body>Hello</body></html>');
        return $this->render('articles/index.html.twig', ['articles' => $articles]);
    }
}

<?php 

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
// use Symfony\Component\Config\Builder\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as AbstractController;

// use Doctrine\DBAL\Types\TextType;
// use Doctrine\DBAL\Types\TextArea;
// use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Entity\Article;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;

// use Doctrine\DBAL\Types\TextType;

class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="article_list", methods="GET")
     */
    public function index(EntityManagerInterface $entityManager)
    {
        $articles = $entityManager->getRepository(Article::class)->findAll();

        // return new Response('<html><body>Hello</body></html>');
        return $this->render('articles/index.html.twig', ['articles' => $articles]);
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

    /**
     * @Route("/article/new", name="new_article")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article();

        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('body', TextareaType::class, array(
                'required' => false,
                'attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array(
                'label' => 'Create',
                'attr' => array('class' => 'btn btn-primary mp-3')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();

            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('article_list');
        }

        return $this->render('articles/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/article/edit/{id}", name="edit_article")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $article = $entityManager->getRepository(Article::class)->find($id);

        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class, array('attr' => array('class' => 'form-control')))
            ->add('body', TextareaType::class, array(
                'required' => false,
                'attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array(
                'label' => 'Update',
                'attr' => array('class' => 'btn btn-primary mp-3')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('article_list');
        }

        return $this->render('articles/edit.html.twig', ['form' => $form->createView()]);
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
     * @Route("/article/delete/{id}", name="article_delete")
     * @Method({"DELETE"})
     */
    public function delete(EntityManagerInterface $entityManager, int $id) 
    {
        $article = $entityManager->getRepository(Article::class)->find($id);

        $entityManager->remove($article);
        $entityManager->flush();

        $response = new Response();
        $response->send();
    }
}
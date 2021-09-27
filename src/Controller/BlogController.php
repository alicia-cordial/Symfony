<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index(PostRepository $ripo)
    {
        $posts = $ripo->findAll();

        return $this->render('blog/index.html.twig', [
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/posts/{id}", name="show_post")
     */
    public function show(Post $post, Request $request, EntityManagerInterface $em)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);


        $comment->setCreatedAt(new \DateTime());
        $comment->setPost($post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($comment);
            $em->flush();
        }


        return $this->render('blog/post.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }
}

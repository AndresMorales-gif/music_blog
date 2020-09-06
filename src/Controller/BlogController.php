<?php

namespace App\Controller;

use App\Entity\BlogPosts;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index()
    {
    	$repository = $this->getDoctrine()->getRepository(BlogPosts::class);
    	$blogs = $repository->findAll();

        return $this->render('blog/index.html.twig', [
            'blogs' => $blogs, 'blogSelect' => ''
        ]);
    }

    /**
     * @Route("/blog/{id}", name="id_blog")
     */
    public function blog_one($id)
    {
    	$repository = $this->getDoctrine()->getRepository(BlogPosts::class);
    	$blogs = $repository->findAll();
        $blogSelect = $repository->find($id);
        return $this->render('blog/index.html.twig', [
            'blogs' => $blogs, 'blogSelect' => $blogSelect
        ]);
    }
}

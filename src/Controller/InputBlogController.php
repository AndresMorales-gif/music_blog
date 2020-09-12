<?php

namespace App\Controller;

use App\Entity\BlogPosts;
use App\Form\BlogType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class InputBlogController extends AbstractController
{
    
    public function index()
    {
    	$user = $this->getUser();
    	$repository = $this->getDoctrine()->getRepository(BlogPosts::class);
    	$blogs = $repository->findBy(
		    ['idUser' => $user->getIdUser()]
		);
        return $this->render('input_blog/index.html.twig', [
            'blogs' => $blogs,
        ]);
    }

    public function newBlog(Request $request, SluggerInterface $slugger)
    {
    	$user = $this->getUser();
        $blog = new BlogPosts();
        
        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    
                }
                
                $blog->setImage($newFilename);
            }

            $blog->setIdUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($blog);
            $entityManager->flush();
            
            return $this->redirectToRoute('input_blog');
        }

        return $this->render('input_blog/new_blog.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    
    public function editBlog($id, Request $request, SluggerInterface $slugger)
    {
    	$entityManager = $this->getDoctrine()->getManager();
	    $blog = $entityManager->getRepository(BlogPosts::class)->find($id);

	    if (!$blog) {
	        throw $this->createNotFoundException(
	            'No product found for id '.$id
	        );
	    }

	    $form = $this->createForm(BlogType::class, $blog);
        $form->get('title')->setData($blog->getTitle());
        $form->get('body')->setData($blog->getBody());
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('images'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    
                }
                
                $blog->setImage($newFilename);
            }

            $entityManager->flush();
            
            return $this->redirectToRoute('input_blog');
        }

        return $this->render('input_blog/edit_blog.html.twig', [
            'form' => $form->createView(),
        ]);	    
    }

    
    public function deleteBlog($id)
    {
    	$user = $this->getUser();
    	$entityManager = $this->getDoctrine()->getManager();
	    $blog = $entityManager->getRepository(BlogPosts::class)->find($id);
	    if ($blog->getIdUser()==$user)
	    {
	    	$entityManager->remove($blog);
			$entityManager->flush();
	    }
        return $this->redirectToRoute('input_blog');
    }

}

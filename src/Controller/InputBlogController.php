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
    /**
     * @Route("/entrada", name="input_blog")
     */
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

    /**
     * @Route("/entrada/nuevo", name="new_blog")
     */
    public function newBlog(Request $request, SluggerInterface $slugger)
    {
    	$user = $this->getUser();
        $blog = new BlogPosts();
        // ...

        $form = $this->createForm(BlogType::class, $blog);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();


            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('images'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
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

    /**
     * @Route("/entrada/editar/{id}", name="edit_blog")
     */
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
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();


            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('images'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $blog->setImage($newFilename);
            }
            $entityManager->flush();
            return $this->redirectToRoute('input_blog');
        }

        return $this->render('input_blog/edit_blog.html.twig', [
            'form' => $form->createView(),
        ]);	    
    }

    /**
     * @Route("/entrada/eliminar/{id}", name="delete_blog")
     */
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

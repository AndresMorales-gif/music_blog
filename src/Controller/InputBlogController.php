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
        return $this->render('input_blog/index.html.twig', [
            'controller_name' => 'InputBlogController',
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
}

<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Form\MessagesType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactoController extends AbstractController
{
    
    public function index(Request $request)
    {
    	$message = new Messages();
	    $form = $this->createForm(MessagesType::class, $message);
	    $form->handleRequest($request);

	    if ($form->isSubmitted() && $form->isValid()) {
	        
	        $message = $form->getData();
	        $entityManager = $this->getDoctrine()->getManager();
	        $entityManager->persist($message);
	        $entityManager->flush();

	        return $this->redirectToRoute('home');
	    }

	    return $this->render('contacto/index.html.twig', [
	        'form' => $form->createView(),
	    ]);
    }
}

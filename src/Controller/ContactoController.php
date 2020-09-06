<?php

namespace App\Controller;

use App\Entity\Messages;
use App\Form\MessagesType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ContactoController extends AbstractController
{
    /**
     * @Route("/contacto", name="contacto")
     */
    public function index(Request $request)
    {
    	$message = new Messages();

	    $form = $this->createForm(MessagesType::class, $message);

	    $form->handleRequest($request);
	    if ($form->isSubmitted() && $form->isValid()) {
	        // $form->getData() holds the submitted values
	        // but, the original `$task` variable has also been updated
	        $message = $form->getData();

	        // ... perform some action, such as saving the task to the database
	        // for example, if Task is a Doctrine entity, save it!
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

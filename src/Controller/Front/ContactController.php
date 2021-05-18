<?php

namespace App\Controller\Front;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/front/contact", name="front_contact", methods={"GET","POST"})
     */
    public function index(Request $request, \Swift_Mailer $mailer): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactFormData = $form->getData();
            // dd($contactFormData);

            $message = (new \Swift_Message('Hello Email'))
                ->setFrom($contactFormData->getEmail())
                ->setTo('pedr1ferre@gmail.com')
                ->setBody(
                    $contactFormData->getMessage(),
                    'text/plain'
                )
            ;

            
    
            $mailer->send($message);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();


            return $this->redirectToRoute('front_contact');
        }

        return $this->render('front/contact/add.html.twig', [
            'contact' => $contact,
            'form' => $form->createView(),
        ]);
    }
}

<?php

namespace App\Controller\Front;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    
    // public function index(Request $request, \Swift_Mailer $mailer): Response
    // {
    //     $contact = new Contact();
    //     $form = $this->createForm(ContactType::class, $contact);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
            

    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($contact);
    //         $entityManager->flush();

    //         $contactFormData = $form->getData();

    //         $message = (new \Swift_Message('Hello Email'))
    //             ->setFrom($contactFormData->getEmail())
    //             ->setTo('pedr1ferre@gmail.com')
    //             ->setBody(
    //                 $contactFormData->getMessage(),
    //                 'text/plain'
    //             )
    //             // ->setBody(
    //             //     $this->renderView(
    //             //         'front/contact/add.html.twig',
    //             //         [
    //             //             'name'    => $contactFormData->getName(),
    //             //             'email'   => $contactFormData->getEmail(),
    //             //             'message' => $contactFormData->getMessage(),
    //             //         ]
    //             //     ),
    //             //     'text/html'
    //             // )
    //         ;
    //         //     ->setBody(
    //         //         $contactFormData->getMessage(),
    //         //         'text/plain'
    //         //     )
    //         // ;

    //         $this->addFlash(
    //             'secondary',
    //             'Votre message a bien été envoyé.'
    //         );
    
    //         $mailer->send($message);
    //         // $this->get('mailer')->send($message);


    //         return $this->redirectToRoute('front_contact');
    //     }

    //     return $this->render('front/contact/add.html.twig', [
    //         'contact' => $contact,
    //         'form' => $form->createView(),
    //     ]);
    // }

    /**
     * @Route("/front/contact", name="front_contact", methods={"GET","POST"})
     * @param Request $request
     * @param MailerInterface $mailer
     * @return Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();

            $contactFormData = $form->getData();


             //$this->get('mailer')->send($message);
            $email = (new Email())
                ->from($contactFormData->getEmail())
                ->to('pedr1ferre@gmail.com')
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('Time for Symfony Mailer!')
                ->text($contactFormData->getMessage())
                ->html($contactFormData->getMessage());

            $this->addFlash(
                'secondary',
                'Votre message a bien été envoyé.'
            );

            $mailer->send($email);
            return $this->redirectToRoute('front_contact');
        }

        return $this->render('front/contact/add.html.twig', [
            'contact' => $contact,
            'form' => $form->createView(),
        ]);
    }

    // /**
    //  * @Route("/front/contact", name="front_contact", methods={"GET","POST"})
    //  */
    // public function index(Request $request, \Swift_Mailer $mailer): Response
    // {
    //     $contact = new Contact();
    //     $form = $this->createForm(ContactType::class, $contact);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
            

    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($contact);
    //         $entityManager->flush();

    //         $contactFormData = $form->getData();

    //         $message = (new \Swift_Message('Hello Email'))
    //             ->setFrom($contactFormData->getEmail())
    //             ->setTo('pedr1ferre@gmail.com')
    //             ->setBody(
    //                 $contactFormData->getMessage(),
    //                 'text/plain'
    //             )
    //         ;

            // $this->addFlash(
            //     'secondary',
            //     'Votre message a bien été envoyé.'
            // );
    
    //         $mailer->send($message);


    //         return $this->redirectToRoute('front_contact');
    //     }

    //     return $this->render('front/contact/add.html.twig', [
    //         'contact' => $contact,
    //         'form' => $form->createView(),
    //     ]);
    // }
}

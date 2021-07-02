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
    /**
     * @Route("/contact", name="front_contact", methods={"GET","POST"})
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @return Response
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function index(Request $request, \Swift_Mailer $mailer): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();

            $contactFormData = $form->getData();

            $email = (new \Swift_Message('Contact depuis romaingodard.fr'))
                ->setFrom($contactFormData->getEmail())
                ->setTo('priam33@gmail.com')
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                // ->subject('Time for Symfony Mailer!')
                ->setBody(
                    $this->renderView(
                        'front/contact/contact-mail.txt.twig',
                        [
                            'name' => $contactFormData->getName(),
                            'message' => $contactFormData->getMessage()
                        ]
                    ),
                    'text/plain'
                );
            $mailer->send($email);

            $this->addFlash(
                'secondary',
                'Votre message a bien été envoyé.'
            );


            return $this->redirectToRoute('front_contact');
        }

        return $this->render('front/contact/add.html.twig', [
            'contact' => $contact,
            'form' => $form->createView(),
        ]);
    }

}

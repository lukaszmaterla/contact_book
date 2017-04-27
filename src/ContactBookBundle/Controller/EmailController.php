<?php

namespace ContactBookBundle\Controller;

use ContactBookBundle\Entity\Email;
use ContactBookBundle\Form\EmailType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class EmailController extends Controller
{
    /**
     * @Route("/{id}/email/add")
     * @Template(":Email:new.html.twig")
     */

    public function addEmailAction(Request $request, $id)
    {
        $contact = $this->getDoctrine()->getRepository('ContactBookBundle:Contact')->find($id);
        if (!$contact) {
            throw $this->createNotFoundException("Contact not Found");
        }

        $email = new Email();

        $formEmail = $this->createForm(EmailType::class, $email, ['action' => $this->generateUrl('contactbook_email_addemail', ['id' => $contact->getId()])]);

        $formEmail->handleRequest($request);

        if ($formEmail->isSubmitted() && $formEmail->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $email->setContact($contact);
            $contact->addEmail($email);
            $em->persist($email);
            $em->flush();

            return $this->redirectToRoute('contactbook_contact_show', ['id' => $contact->getId()]);
        }


        return ['formEmail' => $formEmail->createView(), 'contact' => $contact];
    }

    /**
     * @Route("/{id}/email/{n}/delete")
     */

    public function deleteAction($id, $n)
    {
        $email = $this->getDoctrine()->getRepository('ContactBookBundle:Email')->find($n);
        if (!$email) {
            throw $this->createNotFoundException("Email not Found");
        }

        $contact = $this->getDoctrine()->getRepository('ContactBookBundle:Contact')->find($id);

        if (!$contact) {
            throw $this->createNotFoundException("Contact not Found");
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($email);
        $em->flush();

        return $this->redirectToRoute('contactbook_contact_show', ['id' => $contact->getId()]);
    }

    /**
     * @Route("/{id}/email/{n}")
     * @Template(":Email:new.html.twig")
     */

    public function modifyAction(Request $request, $id, $n)
    {
        $contact = $this->getDoctrine()->getRepository('ContactBookBundle:Contact')->find($id);
        if (!$contact) {
            throw $this->createNotFoundException("Contact not Found");
        }
        $email = $this->getDoctrine()->getRepository('ContactBookBundle:Email')->find($n);
        if (!$email) {
            throw $this->createNotFoundException("Address not Found");
        }

        $formEmail = $this->createForm(EmailType::class, $email, ['action' => $this->generateUrl('contactbook_email_modify', ['id' => $contact->getId(), 'n' => $email->getId()])]);
        $formEmail->handleRequest($request);

        if ($formEmail->isValid() && $formEmail->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('contactbook_contact_show', ['id' => $contact->getId()]);
        }

        return ['formEmail' => $formEmail->createView(), 'contact' => $contact];
    }
}

<?php

namespace ContactBookBundle\Controller;

use ContactBookBundle\Entity\Contact;
use ContactBookBundle\Form\ContactType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends Controller
{
    /**
     * @Route("/new")
     * @Template(":Contact:new.html.twig")
     */
    public function newCreateAction(Request $request)
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact, ['action' => $this->generateUrl('contactbook_contact_newcreate')]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();

            return $this->redirectToRoute('contactbook_contact_show', ['id' => $contact->getId()]);
        }

        return ['form' => $form->createView()];


    }

    /**
     * @Route("/{id}")
     * @Template(":Contact:show.html.twig")
     */
    public function showAction($id)
    {
        $contact = $this->getDoctrine()->getRepository('ContactBookBundle:Contact')->find($id);

        $addresses = $this->getDoctrine()->getRepository('ContactBookBundle:Address')->findBy(['contact' => $contact->getId()]);

        $phones = $this->getDoctrine()->getRepository('ContactBookBundle:Phone')->findBy(['contact' => $contact->getId()]);

        $emails = $this->getDoctrine()->getRepository('ContactBookBundle:Email')->findBy(['contact'=>$contact->getId()]);
        if (!$contact) {

            throw $this->createNotFoundException("Contact not Found");
        }
        return [
            'contact' => $contact,
            'addresses' => $addresses,
            'phones' => $phones,
            'emails'=> $emails
        ];
    }

    /**
     * @Route("/")
     * @Template(":Contact:show_all.html.twig")
     */
    public function showAllAction()
    {
        $contacts = $this->getDoctrine()->getRepository('ContactBookBundle:Contact')->findBy([], ['name' => 'ASC']);

        return ['contacts' => $contacts];
    }

    /**
     * @Route("/{id}/delete")
     */

    public function deleteAction($id)
    {
        $contact = $this->getDoctrine()->getRepository('ContactBookBundle:Contact')->find($id);

        if (!$contact) {
            throw $this->createNotFoundException("Contact not Found");
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($contact);
        $em->flush();

        return $this->redirectToRoute('contactbook_contact_showall');
    }

    /**
     * @Route("/{id}/modify")
     * @Template(":Contact:modify.html.twig")
     */

    public function modifyAction(Request $request, $id)
    {
        $contact = $this->getDoctrine()->getRepository('ContactBookBundle:Contact')->find($id);
        if (!$contact) {
            throw $this->createNotFoundException("Contact not Found");
        }

        $form = $this->createForm(ContactType::class, $contact, ['action' => $this->generateUrl('contactbook_contact_modify', ['id' => $contact->getId()])]);
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('contactbook_contact_show', ['id' => $contact->getId()]);
        }

        return ['form' => $form->createView()];
    }
}

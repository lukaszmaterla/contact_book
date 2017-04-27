<?php

namespace ContactBookBundle\Controller;

use ContactBookBundle\Entity\Phone;
use ContactBookBundle\Form\PhoneType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class PhoneController extends Controller
{
    /**
     * @Route("/{id}/phone/add")
     * @Template(":Phone:new.html.twig")
     */

    public function addPhoneAction(Request $request, $id)
    {
        $contact = $this->getDoctrine()->getRepository('ContactBookBundle:Contact')->find($id);
        if (!$contact) {
            throw $this->createNotFoundException("Contact not Found");
        }

        $phone = new Phone();

        $formPhone = $this->createForm(PhoneType::class, $phone, ['action' => $this->generateUrl('contactbook_phone_addphone', ['id' => $contact->getId()])]);

        $formPhone->handleRequest($request);

        if ($formPhone->isSubmitted() && $formPhone->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $phone->setContact($contact);
            $contact->addPhone($phone);
            $em->persist($phone);
            $em->flush();

            return $this->redirectToRoute('contactbook_contact_show', ['id' => $contact->getId()]);
        }


        return ['formPhone' => $formPhone->createView(), 'contact' => $contact];
    }


    /**
     * @Route("/{id}/phone/{n}/delete")
     */

    public function deleteAction($id, $n)
    {
        $phone = $this->getDoctrine()->getRepository('ContactBookBundle:Phone')->find($n);
        if (!$phone) {
            throw $this->createNotFoundException("Phone not Found");
        }

        $contact = $this->getDoctrine()->getRepository('ContactBookBundle:Contact')->find($id);

        if (!$contact) {
            throw $this->createNotFoundException("Contact not Found");
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($phone);
        $em->flush();

        return $this->redirectToRoute('contactbook_contact_show', ['id' => $contact->getId()]);
    }

    /**
     * @Route("/{id}/phone/{n}")
     * @Template(":Phone:new.html.twig")
     */

    public function modifyAction(Request $request, $id, $n)
    {
        $contact = $this->getDoctrine()->getRepository('ContactBookBundle:Contact')->find($id);
        if (!$contact) {
            throw $this->createNotFoundException("Contact not Found");
        }
        $phone = $this->getDoctrine()->getRepository('ContactBookBundle:Phone')->find($n);
        if (!$phone) {
            throw $this->createNotFoundException("Address not Found");
        }

        $formPhone = $this->createForm(PhoneType::class, $phone, ['action' => $this->generateUrl('contactbook_phone_modify', ['id' => $contact->getId(), 'n' => $phone->getId()])]);
        $formPhone->handleRequest($request);

        if ($formPhone->isValid() && $formPhone->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('contactbook_contact_show', ['id' => $contact->getId()]);
        }

        return ['formPhone' => $formPhone->createView(), 'contact' => $contact];
    }
}

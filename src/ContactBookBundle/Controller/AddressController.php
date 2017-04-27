<?php

namespace ContactBookBundle\Controller;

use ContactBookBundle\Entity\Address;
use ContactBookBundle\Form\AddressType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AddressController extends Controller
{
    /**
     * @Route("/{id}/address/add")
     * @Template(":Address:new.html.twig")
     */
    public function AddAdressesAction(Request $request, $id)
    {
        $contact = $this->getDoctrine()->getRepository('ContactBookBundle:Contact')->find($id);

        if (!$contact) {
            throw $this->createNotFoundException("Contact not Found");
        }

        $address = new Address();

        $formAddress = $this->createForm(AddressType::class, $address, ['action' => $this->generateUrl('contactbook_address_addadresses', ['id' => $contact->getId()])]);
        $formAddress->handleRequest($request);

        if ($formAddress->isSubmitted() && $formAddress->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $address->setContact($contact);
            $contact->addAddress($address);
            $em->persist($address);
            $em->flush();

            return $this->redirectToRoute('contactbook_contact_show', ['id' => $contact->getId()]);
        }

        return ['formAddress' => $formAddress->createView(), 'contact' => $contact];

    }

    /**
     * @Route("/{id}/address/{n}")
     * @Template(":Address:new.html.twig")
     */

    public function modifyAction(Request $request, $id, $n)
    {
        $contact = $this->getDoctrine()->getRepository('ContactBookBundle:Contact')->find($id);
        if (!$contact) {
            throw $this->createNotFoundException("Contact not Found");
        }
        $address = $this->getDoctrine()->getRepository('ContactBookBundle:Address')->find($n);
        if (!$address) {
            throw $this->createNotFoundException("Address not Found");
        }

        $formAddress = $this->createForm(AddressType::class, $address, ['action' => $this->generateUrl('contactbook_address_modify', ['id' => $contact->getId(), 'n' => $address->getId()])]);
        $formAddress->handleRequest($request);

        if ($formAddress->isValid() && $formAddress->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('contactbook_contact_show', ['id' => $contact->getId()]);
        }

        return ['formAddress' => $formAddress->createView(), 'contact' => $contact];
    }

    /**
     * @Route("/{id}/address/{n}/delete")
     */

    public function deleteAction($id, $n)
    {
        $address = $this->getDoctrine()->getRepository('ContactBookBundle:Address')->find($n);
        if (!$address) {
            throw $this->createNotFoundException("Contact not Found");
        }

        $contact = $this->getDoctrine()->getRepository('ContactBookBundle:Contact')->find($id);

        if (!$contact) {
            throw $this->createNotFoundException("Contact not Found");
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($address);
        $em->flush();

        return $this->redirectToRoute('contactbook_contact_show', ['id' => $contact->getId()]);
    }
}

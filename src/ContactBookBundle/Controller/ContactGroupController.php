<?php

namespace ContactBookBundle\Controller;

use ContactBookBundle\Entity\ContactGroup;
use ContactBookBundle\Form\ContactGroupType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/group")
 */

class ContactGroupController extends Controller
{
    /**
     * @Route("/new")
     * @Template("ContactGroup/new.html.twig")
     */
    public function newGroupAction (Request $request)
    {

        $group = new ContactGroup();

        $formGroup = $this->createForm(ContactGroupType::class,$group, ['action'=> $this->generateUrl('contactbook_contactgroup_newgroup')]);

        $formGroup->handleRequest($request);

        if ($formGroup->isValid() && $formGroup->isValid()){
            $em = $this->getDoctrine()->getManager();

            $em->persist($group);
            $em->flush();

            return $this->redirectToRoute('contactbook_contactgroup_showgroups');
        }
        return ['formGroup' => $formGroup->createView()];
    }

    /**
     * @Route("/")
     * @Template(":ContactGroup:show_groups.html.twig")
     */
    public function showGroupsAction()
    {
        $groups = $this->getDoctrine()->getRepository('ContactBookBundle:ContactGroup')->findAll();

        return ['groups'=> $groups];
    }
    /**
     * @Route("/{id}")
     * @Template(":ContactGroup:show_one_group.html.twig")
     */
    public function showOneGroupAction($id)
    {
        $group = $this->getDoctrine()->getRepository('ContactBookBundle:ContactGroup')->find($id);
        if (!$group) {
            throw $this->createNotFoundException("Group not Found");
        }

        $contacts = $this->getDoctrine()->getRepository('ContactBookBundle:Contact')->createQueryBuilder('c')
            ->innerJoin('c.groups', 'g')
            ->where('g.id = :group_id')
            ->setParameter('group_id', $group->getId())
            ->getQuery()->getResult();

        return ['group'=> $group,
            'contacts'=> $contacts];

    }

    /**
     * @Route("/{id}/delete")
     */

    public function deleteAction($id)
    {
        $group = $this->getDoctrine()->getRepository('ContactBookBundle:ContactGroup')->find($id);
        if (!$group) {
            throw $this->createNotFoundException("Group not Found");
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($group);
        $em->flush();

        return $this->redirectToRoute('contactbook_contactgroup_showgroups');
    }

    /**
     * @Route("/{id}/modify")
     * @Template(":ContactGroup:new.html.twig")
     */
    public function modifyGroupAction(Request $request, $id)

    {
        $group = $this->getDoctrine()->getRepository('ContactBookBundle:ContactGroup')->find($id);
        $formGroup = $this->createForm(ContactGroupType::class,$group, ['action'=> $this->generateUrl('contactbook_contactgroup_modifygroup', ['id'=> $group->getId()])]);

        $formGroup->handleRequest($request);

        if ($formGroup->isValid() && $formGroup->isValid()){
            $em = $this->getDoctrine()->getManager();

            $em->persist($group);
            $em->flush();

            return $this->redirectToRoute('contactbook_contactgroup_showgroups');
        }
        return ['formGroup' => $formGroup->createView()];
    }
}

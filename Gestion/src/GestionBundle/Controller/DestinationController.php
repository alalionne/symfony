<?php
namespace GestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use GestionBundle\Pagination\Paginator;
use GestionBundle\Form\DestinationType;
use GestionBundle\Entity\Destination;

class DestinationController extends Controller
{   
	/**
     * @Security("has_role('ROLE_USER')")
     * @Route("/destination", name="destination_index")
     */
	public function indexAction(){ 
		$em = $this->getDoctrine()->getManager();
    	$entities = $em->getRepository("GestionBundle:Destination")->findAll();
        
    	if(null === $entities){
            throw new NotFoundHttpException("Aucune destination trouvée.");
        }
        
        return $this->render("GestionBundle:Destination:index.html.twig", array(
            'entities'=>$entities));
	}

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/destination/ajax", name="destination_ajax")
     */
    public function ajaxListAction(Request $request)
    {   
        $em = $this->getDoctrine()->getManager();
        $searchParam = $request->get('searchParam');
        $entities = $em->getRepository('GestionBundle:Destination')->search($searchParam);
        $pagination = (new Paginator())->setItems(count($entities), $searchParam['perPage'])->setPage($searchParam['page'])->toArray();
        
        return $this->render('GestionBundle:Destination:ajax_list.html.twig', array(
                    'entities' => $entities,
                    'pagination' => $pagination,
                    ));
    }
    
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/destination/add", name="destination_add")
     */
	public function addAction(Request $request){  
		$entity = new Destination();
        
		$form = $this->get('form.factory')->create(DestinationType::class,$entity);
       
		if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){ 
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();	
			$request->getSession()->getFlashBag()->add('info', "La destination a été ajouter avec succès.");
			return $this->redirect($this->generateUrl('destination_view', array('id' => $entity->getId())));
		} 
		return $this->render("GestionBundle:Destination:add.html.twig", array('form'=>$form->createView()));
	}
    
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/destination/view/{id}", name="destination_view")
     */
	public function viewAction($id){
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('GestionBundle:Destination')->find($id);

		if(null === $entity){
			throw new NotFoundHttpException("La destination d'id ".$id." n'existe pas.");
		}

    	return $this->render("GestionBundle:Destination:view.html.twig", array('entity'=>$entity));
	}
    
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/destination/edit/{id}", name="destination_edit")
     */
	public function editAction(Request $request, $id){
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('GestionBundle:Destination')->find($id);

		if (null === $entity) {
          throw new NotFoundHttpException("La destination d'id ".$id." n'existe pas.");
        }

		$form = $this->get('form.factory')->create(DestinationType::class, $entity);

		if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
			$em->persist($entity);
			$em->flush();
			$request->getSession()->getFlashBag()->add('success', "La destination a bien été mise à jour.");
			return $this->redirect($this->generateUrl('destination_view', array('id' => $entity->getId())));
		}
    	return $this->render("GestionBundle:Destination:edit.html.twig", array('form'=>$form->createView(), 'entity'=>$entity));
	}
    
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/destination/delete/{id}", name="destination_delete")
     */
	public function deleteAction(Request $request, $id)
    {  
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('GestionBundle:Destination')->find($id);

        if (null === $entity) {
          throw new NotFoundHttpException("La destination d'id ".$id." n'existe pas.");
        }
        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->remove($entity);
            $em->flush();
            $request->getSession()->getFlashBag()->add('danger', "La destination a bien été supprimé.");

            return $this->redirectToRoute('destination_index');
        }
        
        return $this->render("GestionBundle:Destination:delete.html.twig", array(
          'entity' => $entity,
          'form'   => $form->createView(),
        ));
    }

}
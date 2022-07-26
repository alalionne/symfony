<?php
namespace GestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use GestionBundle\Pagination\Paginator;
use GestionBundle\Form\UtilisationType;
use GestionBundle\Entity\Utilisation;

class UtilisationController extends Controller
{   
	/**
     * @Security("has_role('ROLE_USER')")
     * @Route("/utilisation", name="utilisation_index")
     */
	public function indexAction(){
		$em = $this->getDoctrine()->getManager();
    	$entities = $em->getRepository("GestionBundle:Utilisation")->findAll();
        
    	if(null === $entities){
            throw new NotFoundHttpException("Aucune utilisation trouvée.");
        }

        return $this->render("GestionBundle:Utilisation:index.html.twig", array(
            'entities'=>$entities));
	}

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/utilisation/ajax", name="utilisation_ajax")
     */
    public function ajaxListAction(Request $request) 
    {   
        $em = $this->getDoctrine()->getManager();
        $searchParam = $request->get('searchParam');
        $entities = $em->getRepository('GestionBundle:Utilisation')->search($searchParam);
        $pagination = (new Paginator())->setItems(count($entities), $searchParam['perPage'])->setPage($searchParam['page'])->toArray();
        
        return $this->render('GestionBundle:Utilisation:ajax_list.html.twig', array(
                    'entities' => $entities,
                    'pagination' => $pagination,
                    ));
    }
    
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/utilisation/add", name="utilisation_add")
     */
	public function addAction(Request $request){  
		$entity = new Utilisation();
        
		$form = $this->get('form.factory')->create(UtilisationType::class,$entity);
       
		if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){ 
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();	
			$request->getSession()->getFlashBag()->add('info', "L'utilisation a été ajouter avec succès.");
			return $this->redirect($this->generateUrl('utilisation_view', array('id' => $entity->getId())));
		} 
		return $this->render("GestionBundle:Utilisation:add.html.twig", array('form'=>$form->createView()));
	}
    
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/utilisation/view/{id}", name="utilisation_view")
     */
	public function viewAction($id){
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('GestionBundle:Utilisation')->find($id);

		if(null === $entity){
			throw new NotFoundHttpException("L'utilisation d'id ".$id." n'existe pas.");
		}

    	return $this->render("GestionBundle:Utilisation:view.html.twig", array('entity'=>$entity));
	}
    
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/utilisation/edit/{id}", name="utilisation_edit")
     */
	public function editAction(Request $request, $id){
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('GestionBundle:Utilisation')->find($id);

		if (null === $entity) {
          throw new NotFoundHttpException("L'utilisation d'id ".$id." n'existe pas.");
        }

		$form = $this->get('form.factory')->create(UtilisationType::class, $entity);

		if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
			$em->persist($entity);
			$em->flush();
			$request->getSession()->getFlashBag()->add('success', "Utilisation a bien été mise à jour.");
			return $this->redirect($this->generateUrl('utilisation_view', array('id' => $entity->getId())));
		}
    	return $this->render("GestionBundle:Utilisation:edit.html.twig", array('form'=>$form->createView(), 'entity'=>$entity));
	}
    
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/utilisation/delete/{id}", name="utilisation_delete")
     */
	public function deleteAction(Request $request, $id)
    {  
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('GestionBundle:Utilisation')->find($id);

        if (null === $entity) {
          throw new NotFoundHttpException("La utilisation d'id ".$id." n'existe pas.");
        }
        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->remove($entity);
            $em->flush();
            $request->getSession()->getFlashBag()->add('danger', "L'utilisation a bien été supprimé.");

            return $this->redirectToRoute('utilisation_index');
        }
        
        return $this->render("GestionBundle:Utilisation:delete.html.twig", array(
          'entity' => $entity,
          'form'   => $form->createView(),
        ));
    }

}
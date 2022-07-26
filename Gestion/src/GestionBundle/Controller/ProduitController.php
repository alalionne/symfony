<?php
namespace GestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use GestionBundle\Pagination\Paginator;
use GestionBundle\Form\ProduitType;
use GestionBundle\Entity\Produit;

class ProduitController extends Controller
{   
	/**
     * @Security("has_role('ROLE_USER')")
     * @Route("/produit", name="produit_index")
     */
	public function indexAction(){
		$em = $this->getDoctrine()->getManager();
    	$entities = $em->getRepository("GestionBundle:Produit")->findAll();
        
    	if(null === $entities){
            throw new NotFoundHttpException("Aucun produit trouvé.");
        }

        return $this->render("GestionBundle:Produit:index.html.twig", array(
            'entities'=>$entities));
	}

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/produit/ajax", name="produit_ajax")
     */
    public function ajaxListAction(Request $request)
    {   
        $em = $this->getDoctrine()->getManager();
        $searchParam = $request->get('searchParam'); 
        $entities = $em->getRepository('GestionBundle:Produit')->search($searchParam);
        $pagination = (new Paginator())->setItems(count($entities), $searchParam['perPage'])->setPage($searchParam['page'])->toArray();
        
        return $this->render('GestionBundle:Produit:ajax_list.html.twig', array(
                    'entities' => $entities,
                    'pagination' => $pagination,
                    ));
    }
    
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/produit/add", name="produit_add")
     */
	public function addAction(Request $request){ 
		$entity = new Produit();
        
		$form = $this->get('form.factory')->create(ProduitType::class,$entity);
       
		if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();	
			$request->getSession()->getFlashBag()->add('info', "Le produit a été ajouter avec succès.");
			return $this->redirect($this->generateUrl('produit_view', array('id' => $entity->getId())));
		} 
		return $this->render("GestionBundle:Produit:add.html.twig", array('form'=>$form->createView()));
	}
    
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/produit/view/{id}", name="produit_view")
     */
	public function viewAction($id){
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('GestionBundle:Produit')->find($id);

		if(null === $entity){
			throw new NotFoundHttpException("Le produit d'id ".$id." n'existe pas.");
		}

    	return $this->render("GestionBundle:Produit:view.html.twig", array('entity'=>$entity));
	}
    
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/produit/edit/{id}", name="produit_edit")
     */
	public function editAction(Request $request, $id){
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('GestionBundle:Produit')->find($id);

		if (null === $entity) {
          throw new NotFoundHttpException("Le produit d'id ".$id." n'existe pas.");
        }

		$form = $this->get('form.factory')->create(ProduitType::class, $entity);

		if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
			$em->persist($entity);
			$em->flush();
			$request->getSession()->getFlashBag()->add('success', "Le produit a bien été mise à jour.");
			return $this->redirect($this->generateUrl('produit_view', array('id' => $entity->getId())));
		}
    	return $this->render("GestionBundle:Produit:edit.html.twig", array('form'=>$form->createView(), 'entity'=>$entity));
	}
    
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/produit/delete/{id}", name="produit_delete")
     */
	public function deleteAction(Request $request, $id)
    {  
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('GestionBundle:Produit')->find($id);

        if (null === $entity) {
          throw new NotFoundHttpException("Le produit d'id ".$id." n'existe pas.");
        }
        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->remove($entity);
            $em->flush();
            $request->getSession()->getFlashBag()->add('danger', "le produit a bien été supprimé.");

            return $this->redirectToRoute('produit_index');
        }
        
        return $this->render("GestionBundle:Produit:delete.html.twig", array(
          'entity' => $entity,
          'form'   => $form->createView(),
        ));
    }

}
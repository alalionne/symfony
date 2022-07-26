<?php
namespace GestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use GestionBundle\Pagination\Paginator;
use GestionBundle\Form\CategorieType;
use GestionBundle\Entity\Categorie;

class CategorieController extends Controller
{   
	/**
     * @Security("has_role('ROLE_USER')")
     * @Route("/categorie", name="categorie_index")
     */
	public function indexAction(){
		$em = $this->getDoctrine()->getManager();
    	$entities = $em->getRepository("GestionBundle:Categorie")->findAll();
        
    	if(null === $entities){
            throw new NotFoundHttpException("Aucune catégorie trouvé.");
        }

        return $this->render("GestionBundle:Categorie:index.html.twig", array(
            'entities'=>$entities));
	}

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/categorie/ajax", name="categorie_ajax")
     */
    public function ajaxListAction(Request $request)
    {   
        $em = $this->getDoctrine()->getManager();
        $searchParam = $request->get('searchParam');
        $entities = $em->getRepository('GestionBundle:Categorie')->search($searchParam);
        $pagination = (new Paginator())->setItems(count($entities), $searchParam['perPage'])->setPage($searchParam['page'])->toArray();
        
        return $this->render('GestionBundle:Categorie:ajax_list.html.twig', array(
                    'entities' => $entities,
                    'pagination' => $pagination,
                    ));
    }
    
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/categorie/add", name="categorie_add")
     */
	public function addAction(Request $request){  
		$entity = new Categorie();
        
		$form = $this->get('form.factory')->create(CategorieType::class,$entity);
       
		if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){ 
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();	
			$request->getSession()->getFlashBag()->add('info', "La catégorie a été ajouter avec succès.");
			return $this->redirect($this->generateUrl('categorie_view', array('id' => $entity->getId())));
		} 
		return $this->render("GestionBundle:Categorie:add.html.twig", array('form'=>$form->createView()));
	}
    
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/categorie/view/{id}", name="categorie_view")
     */
	public function viewAction($id){
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('GestionBundle:Categorie')->find($id);

		if(null === $entity){
			throw new NotFoundHttpException("La catégorie d'id ".$id." n'existe pas.");
		}

    	return $this->render("GestionBundle:Categorie:view.html.twig", array('entity'=>$entity));
	}
    
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/categorie/edit/{id}", name="categorie_edit")
     */
	public function editAction(Request $request, $id){
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('GestionBundle:Categorie')->find($id);

		if (null === $entity) {
          throw new NotFoundHttpException("La catégorie d'id ".$id." n'existe pas.");
        }

		$form = $this->get('form.factory')->create(CategorieType::class, $entity);

		if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
			$em->persist($entity);
			$em->flush();
			$request->getSession()->getFlashBag()->add('success', "Categorie a bien été mise à jour.");
			return $this->redirect($this->generateUrl('categorie_view', array('id' => $entity->getId())));
		}
    	return $this->render("GestionBundle:Categorie:edit.html.twig", array('form'=>$form->createView(), 'entity'=>$entity));
	}
    
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/categorie/delete/{id}", name="categorie_delete")
     */
	public function deleteAction(Request $request, $id)
    {  
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('GestionBundle:Categorie')->find($id);

        if (null === $entity) {
          throw new NotFoundHttpException("La categorie d'id ".$id." n'existe pas.");
        }
        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->remove($entity);
            $em->flush();
            $request->getSession()->getFlashBag()->add('danger', "La catégorie a bien été supprimé.");

            return $this->redirectToRoute('categorie_index');
        }
        
        return $this->render("GestionBundle:Categorie:delete.html.twig", array(
          'entity' => $entity,
          'form'   => $form->createView(),
        ));
    }

}
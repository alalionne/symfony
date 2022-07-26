<?php
namespace GestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use GestionBundle\Pagination\Paginator;
use GestionBundle\Form\EntreeType;
use GestionBundle\Entity\Entree;
use GestionBundle\Entity\Stock;

class EntreeController extends Controller
{   
	/**
    * @Security("has_role('ROLE_USER')")
    * @Route("/entree", name="entree_index")
    */
	public function indexAction(){
		$em = $this->getDoctrine()->getManager();
    	$entities = $em->getRepository("GestionBundle:Entree")->findAll();
        
    	if(null === $entities){
            throw new NotFoundHttpException("Aucune entree trouvé.");
        }

        return $this->render("GestionBundle:Entree:index.html.twig", array(
            'entities'=>$entities));
	}

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/entree/ajax", name="entree_ajax")
     */
    public function ajaxListAction(Request $request)
    {   
        $em = $this->getDoctrine()->getManager();
        $searchParam = $request->get('searchParam');
        $entities = $em->getRepository('GestionBundle:Entree')->search($searchParam);
        $pagination = (new Paginator())->setItems(count($entities), $searchParam['perPage'])->setPage($searchParam['page'])->toArray();
        
        return $this->render('GestionBundle:Entree:ajax_list.html.twig', array(
                    'entities' => $entities,
                    'pagination' => $pagination,
                    ));
    }
    
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/entree/add", name="entree_add")
     */
	public function addAction(Request $request){   
		$entity = new Entree();
        $stock = new Stock();
        
        $form = $this->get('form.factory')->create(EntreeType::class,$entity);
       
        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){ 
            $value = round($entity->getQuantite() * $entity->getPrix(),2); 
            $em = $this->getDoctrine()->getManager();
            $entity->setValeur($value);
            $em->persist($entity); 

            $stock->setPrix($entity->getPrix());
            $stock->setValeur($value);  dump($value);
            $stock->setCode($form['code']->getData()); 
            $stock->setQuantite($form['quantite']->getData()); 
            $stock->setDci($form['produit']->getData()->getDci()); 
            $stock->setDosage($form['dosage']->getData());
            $stock->setConditionnement($form['conditionnement']->getData());
            $stock->setUtilisation($form['utilisation']->getData()->getNom());
            $stock->setDatePeremption($form['datePeremption']->getData());
            $em->persist($stock);  
            $em->flush();   
            $request->getSession()->getFlashBag()->add('info', "L'entree a été ajouter avec succès.");
            return $this->redirect($this->generateUrl('entree_view', array('id' => $entity->getId())));
        } 
        return $this->render("GestionBundle:Entree:add.html.twig", array('form'=>$form->createView()));
	}
    
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/entree/view/{id}", name="entree_view")
     */
	public function viewAction($id){
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('GestionBundle:Entree')->find($id);

		if(null === $entity){
			throw new NotFoundHttpException("L'entree d'id ".$id." n'existe pas.");
		}

    	return $this->render("GestionBundle:Entree:view.html.twig", array('entity'=>$entity));
	}
    
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/entree/edit/{id}", name="entree_edit")
     */
	public function editAction(Request $request, $id){
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('GestionBundle:Entree')->find($id);

		if (null === $entity) {
          throw new NotFoundHttpException("L'entree d'id ".$id." n'existe pas.");
        }

		$form = $this->get('form.factory')->create(EntreeType::class, $entity);

		if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
      $dci = $form['produit']->getData()->getDci();
      $conditionnement = $entity->getConditionnement();
      $datePeremption = $entity->getDatePeremption();
      $utilisation = $entity->getUtilisation()->getNom();
      $qteEntrant = $form['quantite']->getData();
			
      $em = $this->getDoctrine()->getManager();
      $stock = $em->getRepository('GestionBundle:Stock')->findOneBy(array('dci'=>$dci,'conditionnement'=>$conditionnement,'utilisation'=>$utilisation));
      if(null === $stock){
          $request->getSession()->getFlashBag()->add('Danger', "Le stock  n'existe pas.");
          return $this->redirect($this->generateUrl('entree_view', array('id' => $entity->getId())));
      }
      if($qteEntrant <  $stock->getQteSortie())
          $request->getSession()->getFlashBag()->add('Danger', "Vous avez déjà affectuer des retraits supérieur à la quantité entrée.");
      else{ 
          $qteRestant = round($qteEntrant - $stock->getQteSortie());

          $value = round($qteEntrant * $entity->getPrix(),2); 
          $valueStock = round($qteRestant * $entity->getPrix(),2);
          
          $entity->setValeur($value); 
          $stock->setPrix($entity->getPrix());
          $stock->setValeur($valueStock); 
          $stock->setCode($form['code']->getData()); 
          $stock->setQuantite($qteRestant);
          $stock->setDci($form['produit']->getData()->getDci()); 
          $stock->setDosage($form['dosage']->getData());
          $stock->setConditionnement($form['conditionnement']->getData());
          $stock->setUtilisation($form['utilisation']->getData()->getNom());
          $stock->setDatePeremption($form['datePeremption']->getData());

          if($qteRestant == 0){
              $request->getSession()->getFlashBag()->add('success', "votre Entrée a bien été mise à jour, votre quantité actuelle égale à 0.");
          }else{
              $request->getSession()->getFlashBag()->add('success', "votre Entrée a bien été mise à jour.");
          }
            
            
      }
      
      $em->persist($entity);
      $em->persist($stock);
		  $em->flush();
			
		  return $this->redirect($this->generateUrl('entree_view', array('id' => $entity->getId())));
		}
    return $this->render("GestionBundle:Entree:edit.html.twig", array('form'=>$form->createView(), 'entity'=>$entity));
	}
    
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/entree/delete/{id}", name="entree_delete")
     */
	public function deleteAction(Request $request, $id)
    {  
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('GestionBundle:Entree')->find($id);

        if (null === $entity) {
          throw new NotFoundHttpException("L'entree d'id ".$id." n'existe pas.");
        }
        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->remove($entity);
            $em->flush();
            $request->getSession()->getFlashBag()->add('info', "L'entree a bien été supprimé.");

            return $this->redirectToRoute('entree_index');
        }
        
        return $this->render("GestionBundle:Entree:delete.html.twig", array(
          'entity' => $entity,
          'form'   => $form->createView(),
        ));
    }

}
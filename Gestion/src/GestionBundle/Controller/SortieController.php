<?php

namespace GestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use GestionBundle\Pagination\Paginator;
use GestionBundle\Form\SortieType;
use GestionBundle\Entity\Sortie;

class SortieController extends Controller
{   
	/**
     * @Security("has_role('ROLE_USER')")
     * @Route("/sortie", name="sortie_index")
     */
	public function indexAction(){
		$em = $this->getDoctrine()->getManager();
    	$entities = $em->getRepository("GestionBundle:Sortie")->findAll();
        
    	if(null === $entities){
            throw new NotFoundHttpException("Aucune sortie trouvé.");
        }

        return $this->render("GestionBundle:Sortie:index.html.twig", array(
            'entities'=>$entities));
	}

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/sortie/ajax", name="sortie_ajax")
     */
    public function ajaxListAction(Request $request)
    {   
        $em = $this->getDoctrine()->getManager();
        $searchParam = $request->get('searchParam');
        $entities = $em->getRepository('GestionBundle:Sortie')->search($searchParam);
        $pagination = (new Paginator())->setItems(count($entities), $searchParam['perPage'])->setPage($searchParam['page'])->toArray();
        
        return $this->render('GestionBundle:Sortie:ajax_list.html.twig', array(
                    'entities' => $entities,
                    'pagination' => $pagination,
                    ));
    }
    
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/sortie/add", name="sortie_add")
     */
	public function addAction(Request $request){  
		$entity = new Sortie();
        
		$form = $this->get('form.factory')->create(SortieType::class,$entity);
       
		if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){ 
            $dci = $form['produit']->getData()->getDci();
            $lastqteSortie = $form['quantite']->getData();
            $dosage = $entity->getDosage(); 
            $conditionnement = $entity->getConditionnement();
            $datePeremption = $entity->getDatePeremption();
                     
			$em = $this->getDoctrine()->getManager();
            $stock = $em->getRepository('GestionBundle:Stock')->findOneBy(array('dci'=>$dci,'conditionnement'=>$conditionnement,'datePeremption'=>$datePeremption,'dosage'=>$dosage));
            if(null === $stock){
				$request->getSession()->getFlashBag()->add('danger', "Le stock  n'existe pas.");
            }else{                 
				if($lastqteSortie >  $stock->getQuantite())
					$request->getSession()->getFlashBag()->add('danger', "quantité insuffisante.");
				else{ 
					$qteRestant = round($stock->getQuantite() - $lastqteSortie);
					$qteSortie = round($stock->getQteSortie() + $lastqteSortie);

					$value = round($qteSortie * $entity->getPrix(),2); 
                    $valueStock = round($qteRestant * $entity->getPrix(),2);
					
					if($qteRestant == 0){ 
						$em->remove($stock);
						$em->flush();
						$request->getSession()->getFlashBag()->add('success', "La sortie a été ajouter avec succès.");
						return $this->redirect($this->generateUrl('sortie_view', array('id' => $entity->getId())));
					}
					$entity->setValeur($value); 
                    $stock->setPrix($entity->getPrix());
                    $stock->setValeur($valueStock);
					$stock->setQuantite($qteRestant); 
					$stock->setQteSortie($qteSortie);
					$stock->setLastqteSortie($lastqteSortie); 
					$em->persist($entity);
					$em->flush();	
					
					$request->getSession()->getFlashBag()->add('info', "La sortie a été ajouter avec succès.");
					return $this->redirect($this->generateUrl('sortie_view', array('id' => $entity->getId())));
				}
			}
            
			
		} 
		return $this->render("GestionBundle:Sortie:add.html.twig", array('form'=>$form->createView()));
	}
    
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/sortie/view/{id}", name="sortie_view")
     */
	public function viewAction($id){
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('GestionBundle:Sortie')->find($id);

		if(null === $entity){
			throw new NotFoundHttpException("La sortie d'id ".$id." n'existe pas.");
		}

    	return $this->render("GestionBundle:Sortie:view.html.twig", array('entity'=>$entity));
	}
    
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/sortie/edit/{id}", name="sortie_edit")
     */
	public function editAction(Request $request, $id){
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('GestionBundle:Sortie')->find($id);

		if (null === $entity) {
          throw new NotFoundHttpException("La sortie d'id ".$id." n'existe pas.");
        }

		$form = $this->get('form.factory')->create(SortieType::class, $entity);

		if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){ 
            $dci = $form['produit']->getData()->getDci();
            $lastqteSortie = $form['quantite']->getData();
            $conditionnement = $entity->getConditionnement();
            $datePeremption = $entity->getDatePeremption();

            $em = $this->getDoctrine()->getManager();
            $stock = $em->getRepository('GestionBundle:Stock')->findOneBy(array('dci'=>$dci,'conditionnement'=>$conditionnement,'datePeremption'=>$datePeremption));
            if(null === $stock){
				$request->getSession()->getFlashBag()->add('danger', "Le stock  n'existe pas");
            }else{
				if($lastqteSortie >  round($stock->getQuantite() + $stock->getLastqteSortie()))
					$request->getSession()->getFlashBag()->add('danger', "quantité insuffisante.");
				else{ 
					$qteRestantE = round($stock->getQuantite() + $stock->getLastqteSortie());
					$qteRestant = round($qteRestantE - $lastqteSortie);
					$qteSorties = round($stock->getQteSortie() - $stock->getLastqteSortie());
					$qteSortie = round($qteSorties + $lastqteSortie);

					$value = round($qteSortie * $entity->getPrix(),2); 
                    $valueStock = round($qteRestant * $entity->getPrix(),2);
					
					if($qteRestant == 0){
						$em->remove($stock);
						$em->flush();
						
						$request->getSession()->getFlashBag()->add('success', "La sortie a bien été mise à jour.");
						return $this->redirect($this->generateUrl('sortie_view', array('id' => $entity->getId())));
					}
					$entity->setValeur($value); 
                    $stock->setPrix($entity->getPrix());
                    $stock->setValeur($valueStock);
					$stock->setQuantite($qteRestant); 
					$stock->setQteSortie($qteSortie);
					$stock->setLastqteSortie($lastqteSortie); 
					$em->persist($entity);
					$em->flush();
					$request->getSession()->getFlashBag()->add('success', "sortie a bien été mise à jour.");
					return $this->redirect($this->generateUrl('sortie_view', array('id' => $entity->getId())));
				}
			}
	
		}
    	return $this->render("GestionBundle:Sortie:edit.html.twig", array('form'=>$form->createView(), 'entity'=>$entity));
	}
    
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/sortie/delete/{id}", name="sortie_delete")
     */
	public function deleteAction(Request $request, $id)
    {   
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('GestionBundle:Sortie')->find($id);

        if (null === $entity) {
          throw new NotFoundHttpException("La sortie d'id ".$id." n'existe pas.");
        }
        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->remove($entity);
            $em->flush();
            $request->getSession()->getFlashBag()->add('danger', "La sortie a bien été supprimé.");

            return $this->redirectToRoute('sortie_index');
        }
        
        return $this->render("GestionBundle:Sortie:delete.html.twig", array(
          'entity' => $entity,
          'form'   => $form->createView(),
        ));
    }

}
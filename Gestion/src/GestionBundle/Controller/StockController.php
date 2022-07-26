<?php
namespace GestionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use GestionBundle\Pagination\Paginator;
use GestionBundle\Entity\Stock;

class StockController extends Controller
{   
	/**
     * @Security("has_role('ROLE_USER')")
     * @Route("/stock", name="stock_index")
     */
	public function indexAction(){
		$em = $this->getDoctrine()->getManager();
    	$entities = $em->getRepository("GestionBundle:Stock")->findAll();
        
    	if(null === $entities){
            throw new NotFoundHttpException("Aucun stock trouvé.");
        }

        return $this->render("GestionBundle:Stock:index.html.twig", array(
            'entities'=>$entities));
	}

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/stock/ajax", name="stock_ajax")
     */
    public function ajaxListAction(Request $request)
    {   
        $em = $this->getDoctrine()->getManager();
        $searchParam = $request->get('searchParam');
        $entities = $em->getRepository('GestionBundle:Stock')->search($searchParam);
        $pagination = (new Paginator())->setItems(count($entities), $searchParam['perPage'])->setPage($searchParam['page'])->toArray();
        
        return $this->render('GestionBundle:Stock:ajax_list.html.twig', array(
                    'entities' => $entities,
                    'pagination' => $pagination,
                    ));
    }
    
    
    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/stock/view/{id}", name="stock_view")
     */
	public function viewAction($id){
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('GestionBundle:Stock')->find($id);

		if(null === $entity){
			throw new NotFoundHttpException("Le stock d'id ".$id." n'existe pas.");
		}

    	return $this->render("GestionBundle:Stock:view.html.twig", array('entity'=>$entity));
	}

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/peremption", name="peremption_index")
     */
    public function peremptionAction()
    {       
        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository("GestionBundle:Stock")->stockperime();
        
        if(null === $entities){
            throw new NotFoundHttpException("Aucun stock trouvé.");
        }

        return $this->render('GestionBundle:Stock:peremption.html.twig',$entities);
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/peremption/stock", name="peremption_stock")
     */
    public function statsAction(Request $request)
    {   
        $daterange = $request->get('daterange');
        $statsHandler = $this->get('ben.stats_handler')->setDateRange($daterange);
        $key='stock';
        $searchParam = $request->get('searchParam'); 
        $query = $statsHandler->setDataColumn($key)->processData();

        $em = $this->getDoctrine()->getManager();
        $entities = $em->getRepository("GestionBundle:Stock")->peremption($query,$searchParam);
     
        return $this->render('GestionBundle:Stock:ajaxPeremption.html.twig', array(
            'entities' => $entities));
       
    }

}
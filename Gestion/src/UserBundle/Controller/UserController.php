<?php
namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Request;
use GestionBundle\Pagination\Paginator;
use UserBundle\Form\UserType;
use UserBundle\Form\UserEditType;
use UserBundle\Entity\User;

class UserController extends Controller
{   
	/**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/user", name="user_index")
     */
	public function indexAction(){
		$em = $this->getDoctrine()->getManager();
    	$entities = $em->getRepository("UserBundle:User")->findAll();
        
    	if(null === $entities){
            throw new NotFoundHttpException("Aucune utilisateur trouvé.");
        } 

        return $this->render("UserBundle:User:index.html.twig", array(
            'entities'=>$entities));
	}

    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/user/ajax", name="user_ajax")
     */
    public function ajaxListAction(Request $request)
    {  
        $em = $this->getDoctrine()->getManager();
        $searchParam = $request->get('searchParam');
        $entities = $em->getRepository('UserBundle:User')->search($searchParam); 
        $pagination = (new Paginator())->setItems(count($entities), $searchParam['perPage'])->setPage($searchParam['page'])->toArray(); 
   
        return $this->render('UserBundle:User:ajax_list.html.twig', array(
                    'entities' => $entities,
                    'pagination' => $pagination,
                    ));
    }
    
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/user/add", name="user_add")
     */
	public function addAction(Request $request){ 
		$entity = new User();
        
		$form = $this->get('form.factory')->create(UserType::class,$entity);
       
		if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
			$em = $this->getDoctrine()->getManager();
			$em->persist($entity);
			$em->flush();	
			$request->getSession()->getFlashBag()->add('info', "L'utilisateur a été ajouter avec succès.");
			return $this->redirect($this->generateUrl('user_view', array('id' => $entity->getId())));
		} 
		return $this->render("UserBundle:User:add.html.twig", array('form'=>$form->createView(),'entity'=>$entity));
	}
    
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/user/view/{id}", name="user_view")
     */
	public function viewAction($id){
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('UserBundle:User')->find($id);

		if(null === $entity){
			throw new NotFoundHttpException("L'utilisateur d'id ".$id." n'existe pas.");
		}

    	return $this->render("UserBundle:User:view.html.twig", array('entity'=>$entity));
	}
    
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/user/edit/{id}", name="user_edit")
     */
	public function editAction(Request $request, $id){
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('UserBundle:User')->find($id);

		if (null === $entity) {
          throw new NotFoundHttpException("L'utilisateur d'id ".$id." n'existe pas.");
        }

		$form = $this->get('form.factory')->create(UserType::class, $entity);

		if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
			$em->persist($entity);
			$em->flush();
			$request->getSession()->getFlashBag()->add('success', "L'utilisateur a bien été mise à jour.");
			return $this->redirect($this->generateUrl('user_view', array('id' => $entity->getId())));
		}
    	return $this->render("UserBundle:User:edit.html.twig", array('form'=>$form->createView(), 'entity'=>$entity));
	}
    
    /**
     * @Security("has_role('ROLE_ADMIN')")
     * @Route("/user/delete/{id}", name="user_delete")
     */
	public function deleteAction(Request $request, $id)
    {  
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('UserBundle:User')->find($id);

        if (null === $entity) {
          throw new NotFoundHttpException("L'utilisateur d'id ".$id." n'existe pas.");
        }
        $form = $this->get('form.factory')->create();

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->remove($entity);
            $em->flush();
            $request->getSession()->getFlashBag()->add('info', "L'utilisateur a bien été supprimé.");

            return $this->redirectToRoute('user_index');
        }
        
        return $this->render("UserBundle:User:delete.html.twig", array(
          'entity' => $entity,
          'form'   => $form->createView(),
        ));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/profile/", name="user_profile_edit")
     */
    public function editMeAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $id = $this->getUser()->getId();
        $entity = $em->getRepository('UserBundle:User')->find($id);

        if (null === $entity) {
          throw new NotFoundHttpException("L'utilisateur d'id ".$id." n'existe pas.");
        }

        $form = $this->get('form.factory')->create(UserType::class, $entity);

        return $this->render('UserBundle:myProfile:profile.html.twig', array('form'=>$form->createView(), 'entity'=>$entity));
    }

    /**
     * @Security("has_role('ROLE_USER')")
     * @Route("/profile/update", name="user_profile_update")
     */
    public function updateAction(Request $request) { 
        $em = $this->getDoctrine()->getManager();  
        $id = $this->getUser()->getId();
        $entity = $em->getRepository('UserBundle:User')->find($id);
        
        $form = $this->get('form.factory')->create(UserEditType::class, $entity);

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
            
            $em->persist($entity);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', "Vos modifications ont été enregistrées.");
            return $this->redirect($this->generateUrl('user_profile_edit', array('id' => $entity->getId())));
        }
        return $this->render("UserBundle:myProfile:edit.html.twig", array('form'=>$form->createView(), 'entity'=>$entity));
    }

}
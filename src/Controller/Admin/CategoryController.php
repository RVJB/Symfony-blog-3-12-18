<?php
/**
 * Created by PhpStorm.
 * User: PetitRV
 * Date: 30/11/2018
 * Time: 14:54
 */

namespace App\Controller\Admin;


use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class CategoryController
 * @package App\Controller\Admin
 *
 * @Route("/categorie")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Category::class);
        //$category = repository->findAll();
        // findAll() avec un tri sur name:
        $categories = $repository->findBy([], ['name'=>'asc']);

        return $this->render(
            'admin/category/index.html.twig',
            [
                'categories'=>$categories
            ]
        );
    }

    /**
     * @Route("/edition/{id}", defaults={"id": null}, requirements={"id": "\d+"})
     */
    public function edit(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();


       if(is_null($id)){ // création
        $category = new Category();
    }else {//modification
           $category = $em->find(Category::class, $id);
           // 404 si l'id reçue dans l'url n'est pas en bdd
           if(is_null($category)){
               throw new NotFoundHttpException();
           }
       }

        // création du formulaire lié à la catégorie
        $form = $this->createForm(CategoryType::class, $category);
        // le formulaire analyse la requête HTTP
        // et traite le formulaire s'il a été soumis
        $form-> handleRequest($request); // étape préalable obligatoire

       // si le formulaire a été envoyé
        if($form->isSubmitted()){
            dump($category);
            // si les validations à partir des annotations dans
            // l'entité annotations dans l'entity Category sont ok
            if ($form->isValid()){
                $em->persist($category);
                $em->flush();

                $this->addFlash('success', 'La catégorie est enregistrée');
                return $this ->redirectToRoute('app_admin_category_index');
            }else{
                $this->addFlash('error', 'Le formulaire contient des erreurs');
            }

        }

        return $this->render(
            'admin/category/edit.html.twig',
            [
                // passage du formulaire au template
                'form'=> $form->createView()

            ]
        );
    }

    /**
     * @Route("/suppression/{id}")
     */
    public function delete(Category $category)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        $this->addFlash(
            'success',
            'La catégorie a bien été supprimée'
        );

        return $this->redirectToRoute('app_admin_category_index');
    }


}
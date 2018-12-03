<?php
/**
 * Created by PhpStorm.
 * User: PetitRV
 * Date: 03/12/2018
 * Time: 17:02
 */

namespace App\Controller\Admin;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Class ArticleController
 * @package App\Controller\Admin
 * @Route("/article")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index()
    {
        return $this->render(
            'admin/category/index.html.twig'
        );
    }
}
<?php

namespace App\Controller;

use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class PropertyController extends AbstractController
{

  private $repository;

  private $em;

  public function __construct(PropertyRepository $repository, EntityManagerInterface $em)
  {
    $this->repository = $repository;
    $this->em = $em;
  }

  public function index(PaginatorInterface $paginator, Request $request): Response
  {

    $search = new PropertySearch();
    $form = $this->createForm(PropertySearchType::class, $search);
    $form->handleRequest($request);

    $properties = $paginator->paginate(
      $this->repository->findAllVisibleQuery($search),
      $request->query->getInt('page',1),
      12
    );
    return $this->render('property/index.html.twig' ,[
      'properties' =>  $properties,
      'current_menu' => 'properties',
      'form'=> $form->createView()
    ]);
  }

  public function show(Property $property, $slug, $id): Response
  {
    if($property->getSlug() !== $slug){
      return $this->redirectToRoute('property.show',[
        'id'=> $property->getId(),
        'slug'=>$property->getSlug()
      ], 301);
    }
    $property = $this->repository->find($id);
    return $this->render('property/show.html.twig' ,[
      'property' => $property,
      'current_menu' => 'properties'
    ]);
  }
}
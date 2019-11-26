<?php

namespace App\Controller;

use App\Entity\Property;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class PropertyController extends AbstractController
{

  private $repository;

  private $em;

  public function __construct(PropertyRepository $repository, EntityManagerInterface $em)
  {
    $this->repository = $repository;
    $this->em = $em;
  }

  public function index(): Response
  {
    return $this->render('property/index.html.twig' ,[
      'current_menu' => 'properties'
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
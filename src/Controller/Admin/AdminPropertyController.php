<?php

namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminPropertyController extends AbstractController
{

private $repository;

  public function __construct(PropertyRepository $repository, EntityManagerInterface $em)
  {
    $this->repository =$repository;
    $this->em = $em;
  }

  public function index():Response
  {
    $properties = $this->repository->findAll();
    return $this->render('admin/property/index.html.twig',compact('properties'));
  }

  public function new(Request $request):Response
  {
    $property = new Property();
    $form = $this->createForm(PropertyType::class, $property);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
      $this->em->persist($property);
      $this->em->flush();
      $this->addFlash('success','Bien crée avec succés');
      return $this->redirectToRoute('admin.property.index');
  }

    return $this->render('admin/property/new.html.twig',[
      'property' => $property,
      'form' => $form->createView()
    ]);
  }

  public function edit(Property $property, Request $request):Response
  {
    $form = $this->createForm(PropertyType::class, $property);
    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid()){
      $this->em->flush();
      $this->addFlash('success','Bien modifié avec succés');
      return $this->redirectToRoute('admin.property.index');
    }

    return $this->render('admin/property/edit.html.twig', [
      'property' => $property,
      'form' => $form->createView()
    ]);
  }

  public function delete(Property $property, Request $request)
  {
    if($this->isCsrfTokenValid('delete' . $property->getId(), $request->get('_token'))){
      $this->em->remove($property);
      $this->em->flush();
      $this->addFlash('success','Bien supprimé avec succés');
    }
    return $this->redirectToRoute('admin.property.index');
  }

  public function logout()
  {
    throw new \Exception('Don\'t forget to activate logout in security.yaml');
  }
}
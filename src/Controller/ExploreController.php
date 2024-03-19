<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Repository\CurrencyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ExploreController extends AbstractController
{
    #[Route('/explore', name: 'app_explore')]
    public function index(Request $request, CurrencyRepository $repository): Response
    {
        $defaultData = ['search' => ''];
        $form = $this->createFormBuilder($defaultData,['method' => 'GET'])
            ->add('search', TextType::class,[
                'label' => false,
                'attr' => [
                    'placeholder' => 'Search for a currency',
                    'class' => 'form-control'
                ]
            ])

            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $search = $form->getData()["search"];
            $currencies = $repository->searchByName($search);
        }


        return $this->render('explore/index.html.twig', [
            'controller_name' => 'ExploreController',
            "form" => $form,
            "currencies" => $currencies ?? []
            //"search" => $search,
        ]);
    }
}

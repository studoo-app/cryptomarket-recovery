<?php

namespace App\Controller\Admin;

use App\Entity\Currency;
use App\Form\CurrencyType;
use App\Repository\CurrencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/currency')]
class CurrencyController extends AbstractController
{
    #[Route('/', name: 'app_admin_currency_index', methods: ['GET'])]
    public function index(CurrencyRepository $currencyRepository): Response
    {
        return $this->render('admin/currency/index.html.twig', [
            'currencies' => $currencyRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_currency_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $currency = new Currency();
        $form = $this->createForm(CurrencyType::class, $currency);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($currency);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_currency_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/currency/new.html.twig', [
            'currency' => $currency,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_currency_show', methods: ['GET'])]
    public function show(Currency $currency): Response
    {
        return $this->render('admin/currency/show.html.twig', [
            'currency' => $currency,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_currency_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Currency $currency, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CurrencyType::class, $currency);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_currency_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/currency/edit.html.twig', [
            'currency' => $currency,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_currency_delete', methods: ['POST'])]
    public function delete(Request $request, Currency $currency, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$currency->getId(), $request->request->get('_token'))) {
            $entityManager->remove($currency);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_currency_index', [], Response::HTTP_SEE_OTHER);
    }
}

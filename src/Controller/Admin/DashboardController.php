<?php

namespace App\Controller\Admin;

use App\Entity\Asset;
use App\Entity\Currency;
use App\Entity\User;
use App\Entity\Wallet;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function index(EntityManagerInterface $em): Response
    {

        $stats = [
            'nbUsers' => ["title"=>"Active Users","value"=>$em->getRepository(User::class)->count()],
            'nbTrackedCurrencies' => ["title"=>"Tracked currencies","value"=>$em->getRepository(Currency::class)->count()],
            'nbManagedAssets' => ["title"=>"Tracked Assets","value"=>$em->getRepository(Asset::class)->count()],
            'averageAssertsByWallet' => ["title"=>"Average assets by wallet","value"=>intval($em->getRepository(Wallet::class)->getAverageDetainedAssets()[0]['avg_assets_nb'])],
            'averageWalletCapital' => ["title"=>"Average wallet capital","value"=>intval($em->getRepository(Wallet::class)->getAverageCapital()[0]['avg_capital'])]
        ];

        return $this->render('admin/dashboard/index.html.twig', [
            'stats' => $stats,
        ]);
    }
}

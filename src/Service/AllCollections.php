<?php

namespace App\Service;

use App\Repository\PortfolioRepository;
use Symfony\Component\HttpKernel\KernelInterface;

class AllCollections
{
  public function getCollections(PortfolioRepository $portfolioRepository)
  {
    $portfolios = $portfolioRepository->findAll();

    return $portfolios;
  }
}
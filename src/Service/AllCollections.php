<?php

namespace App\Service;

use App\Repository\PortfolioRepository;
use Symfony\Component\HttpKernel\KernelInterface;

class AllCollections
{
  public function __construct(PortfolioRepository $portfolioRepository)
    {
        $this->portfolioRepository = $portfolioRepository;
    }

  public function getCollections()
  {
    $portfolios = $this->portfolioRepository->findAll();

    return $portfolios;
  }
}
<?php

namespace App\Command;

use App\Application\Actions\Product\Elasticsearch\FetchElasticsearchProductsAction;
use App\Application\Actions\Product\FilterProduct\DTO\FilterProductRequest;
use App\Application\Actions\Product\FilterProduct\FilterProductAction;
use App\Repository\ProductGroupItemRepository;
use App\Repository\ProductGroupRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TestCommand extends Command
{
    protected static $defaultName = 'app:test';
    protected static $defaultDescription = 'Test command';

    private ProductGroupItemRepository $productGroupItemRepository;

    private ProductGroupRepository $productGroupRepository;

    public function __construct(
        string $name = null,
        ProductGroupItemRepository $productGroupItemRepository,
        ProductGroupRepository $productGroupRepository
    ) {
        parent::__construct($name);
        $this->productGroupItemRepository = $productGroupItemRepository;
        $this->productGroupRepository = $productGroupRepository;
    }

    protected function configure(): void
    {}

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $groupIdList = $this->productGroupItemRepository->filterGroupsByProducts([285]);
            $productGroups = $this->productGroupRepository->fetchModelsById(
                $groupIdList
            );

            dd($productGroups);

        } catch (\Throwable $e) {
            $io->error(implode(PHP_EOL, [$e->getMessage(), $e->getTraceAsString()]));
            return Command::FAILURE;
        }

        $io->success('Ok.');
        return Command::SUCCESS;
    }
}

<?php

namespace App\Command;

use App\Application\Actions\Product\DTO\ImportProductsRequest;
use App\Application\Actions\Product\Elasticsearch\FetchElasticsearchProductsAction;
use App\Application\Actions\Product\FilterProduct\DTO\FilterProductRequest;
use App\Application\Actions\Product\FilterProduct\FilterProductAction;
use App\Application\Actions\Product\ImportProductsAction;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TestCommand extends Command
{
    protected static $defaultName = 'app:test';
    protected static $defaultDescription = 'Test command';

    private FilterProductAction $filterProductAction;

    private FetchElasticsearchProductsAction $fetchElasticsearchProductsAction;

    private ImportProductsAction $importProductsAction;

    public function __construct(
        string $name = null,
        FilterProductAction $filterProductAction,
        FetchElasticsearchProductsAction $fetchElasticsearchProductsAction,
        ImportProductsAction $importProductsAction
    ) {
        parent::__construct($name);
        $this->filterProductAction = $filterProductAction;
        $this->fetchElasticsearchProductsAction = $fetchElasticsearchProductsAction;
        $this->importProductsAction = $importProductsAction;
    }

    protected function configure(): void
    {}

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $this->importProductsAction->execute(
                (new ImportProductsRequest())
                    ->setDataSet([
                        [
                            'vendor_code' => '00007497',
                            'vendor' => 'ВОМЗ',
                            'name' => 'Коллиматорный прицел ВОМЗ Пилад Р1х20',
                        ]
                    ])
            );

        } catch (\Throwable $e) {
            $io->error($e->getMessage());
        }

        $io->success('Ok.');
        return Command::SUCCESS;
    }
}

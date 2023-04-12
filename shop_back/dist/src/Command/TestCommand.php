<?php

namespace App\Command;

use App\Application\Actions\Product\FilterByFullNameAction;
use App\Message\ParseProductPriceMessage;
use App\Repository\ProductGroupCategoryItemRepository;
use App\Repository\ProductGroupItemRepository;
use App\Repository\ProductGroupRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

class TestCommand extends Command
{
    protected static $defaultName = 'app:test';
    protected static $defaultDescription = 'Test command';

    private ProductGroupItemRepository $productGroupItemRepository;

    private ProductGroupRepository $productGroupRepository;

    private ProductGroupCategoryItemRepository $productGroupCategoryItemRepository;

    private MessageBusInterface $messageBus;

    private FilterByFullNameAction $filterByFullNameAction;

    public function __construct(
        string $name = null,
        ProductGroupItemRepository $productGroupItemRepository,
        ProductGroupRepository $productGroupRepository,
        ProductGroupCategoryItemRepository $productGroupCategoryItemRepository,
        MessageBusInterface $messageBus,
        FilterByFullNameAction $filterByFullNameAction
    ) {
        parent::__construct($name);
        $this->productGroupItemRepository = $productGroupItemRepository;
        $this->productGroupRepository = $productGroupRepository;
        $this->productGroupCategoryItemRepository = $productGroupCategoryItemRepository;
        $this->messageBus = $messageBus;
        $this->filterByFullNameAction = $filterByFullNameAction;
    }

    protected function configure(): void
    {}

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
//            $htmlContent = file_get_contents('https://worldguns.store/optika/voennaya-optika/kollimatornye-pricely/kollimatornyy-pricel-aimpoint-9000sc-nv/');
//            $this->messageBus->dispatch(new ParseProductPriceMessage(265));

        } catch (\Throwable $e) {
            $io->error(implode(PHP_EOL, [$e->getMessage(), $e->getTraceAsString()]));
            return Command::FAILURE;
        }

        $io->success('Ok.');
        return Command::SUCCESS;
    }
}

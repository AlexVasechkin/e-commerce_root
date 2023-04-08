<?php

namespace App\MessageHandler;

use App\Application\Actions\Product\IndexProductAction;
use App\Message\EsIndexProductMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class EsIndexProductMessageHandler
    implements MessageHandlerInterface
{
    private LoggerInterface $logger;

    private IndexProductAction $indexProductAction;

    public function __construct(
        LoggerInterface $logger,
        IndexProductAction $indexProductAction
    ) {
        $this->logger = $logger;
        $this->indexProductAction = $indexProductAction;
    }

    public function __invoke(EsIndexProductMessage $message)
    {
        try {
            $this->indexProductAction->execute($message->getProductId());
        } catch (\Throwable $e) {
            $this->logger->error(implode(PHP_EOL, [
                $e->getMessage(),
                $e->getTraceAsString()
            ]));
        }
    }
}

<?php

namespace App\MessageHandler;

use App\Message\IndexUserMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class IndexUserMessageHandler
    implements MessageHandlerInterface
{


    public function __construct()
    {
    }

    public function __invoke(IndexUserMessage $message)
    {
        try {

        } catch (\Throwable $e) {

        }
    }
}

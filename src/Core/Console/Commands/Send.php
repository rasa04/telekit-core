<?php

namespace Core\Console\Commands;

use Core\API\Methods\Message\SendMessage;
use Core\Database\Database;
use Core\Exceptions\InvalidRequiredParameterException;
use Database\models\Chat;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Send extends Command
{
    protected function configure(): void
    {
        $this->setName('send')
            ->setDescription('Send message')
            ->addArgument('all', InputArgument::OPTIONAL)
            ->addOption('to', 't', InputOption::VALUE_OPTIONAL)
            ->addOption('message', 'm', InputOption::VALUE_REQUIRED);
    }

    /**
     * @throws InvalidRequiredParameterException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $options = $input->getOptions();

        if (isset($options['to'])) {
            if (!isset($options['message'])) {
                $output->getFormatter()->setStyle("yellow-bg", new OutputFormatterStyle('black', "yellow"));
                $output->writeln("<yellow-bg> WARING: You didn't specify the message </yellow-bg>");
            }
            $chatId = $options["to"];
            $message = $options["message"] ?? "Hi! It's test message from " . getenv("APP_NAME");
            (new SendMessage)
                ->chatId($chatId)
                ->text($message)
                ->handle();
        }
        elseif ($input->getArgument('all')) {
            new Database();
            $chats = Chat::all()->pluck('chat_id')->toArray();
            $message = $options["message"] ?? "Hi! It's test message from " . getenv("APP_NAME");

            foreach ($chats as $chat) {
                try {
                    (new SendMessage)
                        ->chatId($chat)
                        ->text($message)
                        ->handle();
                    $output->getFormatter()->setStyle("green-bg", new OutputFormatterStyle('black', "green"));
                    $output->writeln("<green-bg> Shipped: $chat </green-bg>");
                }
                catch (Exception $e) {
                    if (strpos($e->getMessage(), "chat not found")) {
                        $output->getFormatter()->setStyle("yellow-bg", new OutputFormatterStyle('black', "yellow"));
                        $output->writeln("<yellow-bg> No chat: $chat </yellow-bg>");
                    }
                }
            }

        }
        else {
            $output->getFormatter()->setStyle("yellow-bg", new OutputFormatterStyle('black', "yellow"));
            $output->writeln("<yellow-bg> Missed parameter to </yellow-bg>");
        }
        return Command::SUCCESS;
    }
}
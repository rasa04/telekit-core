<?php

declare(strict_types=1);

namespace Core\Console\Commands;

use Core\API\Methods\GetUpdates;
use Core\API\Types\Update;
use Core\Database\Database;
use Core\Env;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Serve extends Command
{
    use Env;

    public static int $lastUpdate = 0;
    public static bool $handled;
    private GetUpdates $updatesClient;

    protected function configure(): void
    {
        new Database;
        $this->setName('serve')->setDescription('Run polling');
        $this->updatesClient = new GetUpdates();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->getFormatter()
            ->setStyle(
            name: "green-bg",
            style: new OutputFormatterStyle('black', 'green')
        );

        /** @var Update[] $response */
        $response = $this->updatesClient->pull(self::$lastUpdate);
        if (!empty($response['result'])) {
            self::$lastUpdate = $response[0]->updateId();
        } elseif($response['error']) {
            $output->writeln("<red-bg> ERROR OCCURED WHILE DOING REQUEST </red-bg>");
        }

        while (true) {
            /** @var Update[] $response */
            $response = $this->updatesClient->pull(self::$lastUpdate);

            if ($response['error']) {
                continue;
            }
            if (isset($GLOBALS['request']) && $response[0]->updateId() === $GLOBALS['request']['update_id']) {
                self::$lastUpdate += 1;
                continue;
            }

            $GLOBALS['request'] = $response['result'][0];
            require 'index.php';
            $io = new SymfonyStyle($input, $output);
            var_dump($GLOBALS['request']);
            self::$lastUpdate+=1;
            $output->writeln("<green-bg> SUCCESSFUL HANDLED </green-bg>");
            sleep(1);
        }
    }
}
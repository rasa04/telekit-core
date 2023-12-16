<?php

namespace Core\Console\Commands;

use Core\Console\Commands\Database\{DatabaseInfo, Driver, Migration, Params, Seeder, Table, Tables, Version};
use Exception;
use Core\Console\Commands\Make\{MakeInteraction, MakeMigration, MakeModel, MakeTrigger};
use Symfony\Component\Console\Application;

class Command
{
    /**
     * @throws Exception
     */
    public static function run(): void
    {
        $application = new Application();

        $application->addCommands([
            new Tables, new Params, new Driver, new Version, new Table, new Table, new Migration, new Seeder, new DatabaseInfo,
            new MakeInteraction, new MakeTrigger, new MakeModel, new MakeMigration,
            new Serve, new GetUpdates, new Send, new Responses
        ]);

        $application->run();
    }
}
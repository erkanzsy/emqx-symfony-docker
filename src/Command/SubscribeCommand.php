<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:subscribe',
    description: 'Add a short description for your command',
)]
class SubscribeCommand extends Command
{

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $server   = 'emqx1';
        $port     = 1883;
        $clientId = 'test-subscriber';

        $mqtt = new \PhpMqtt\Client\MqttClient($server, $port, $clientId);
        $mqtt->connect();
        $mqtt->subscribe('php-mqtt/client/test', function ($topic, $message) {
            echo sprintf("Received message on topic [%s]: %s\n %s\n", $topic, $message, date('Y-m-d H:i:s.u'));
        }, 0);
        $mqtt->loop(true);
        $mqtt->disconnect();


        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}

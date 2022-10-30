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
    name: 'app:publish',
    description: 'Add a short description for your command',
)]
class PublishCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $server   = 'emqx1';
        $port     = 1883;
        $clientId = 'test-publisher';

        $mqtt = new \PhpMqtt\Client\MqttClient($server, $port, $clientId);
        $mqtt->connect();

        for ($i = 0; $i< 10; $i++) {
            $payload = array(
                'protocol' => 'tcp',
                'date' => date('Y-m-d H:i:s.u'),
                'url' => 'https://github.com/emqx/MQTT-Client-Examples'
            );
            $mqtt->publish(
            // topic
                'php-mqtt/client/test',
                // payload
                json_encode($payload),
                // qos
                0,
                // retain
                true
            );
            printf("msg $i send\n");
            sleep(1);
        }

$mqtt->loop(true);

        $mqtt->publish('php-mqtt/client/test', 'Hello World!', 0);
        $mqtt->disconnect();

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}

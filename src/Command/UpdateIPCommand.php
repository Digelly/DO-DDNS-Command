<?php

declare(strict_types=1);

namespace Console\Command;

use Console\IP\IPUpdater;
use Console\IP\IPVersions;
use Console\Record\RecordInfo;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\Input;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateIPCommand extends Command
{
    public const COMMAND_NAME = 'ip:update';

    /**
     * @var Input
     */
    private $input;

    /**
     * @var Output
     */
    private $output;

    /**
     * @var IPUpdater
     */
    private $updater;

    public function __construct(IPUpdater $updater)
    {
        parent::__construct(null);

        $this->updater = $updater;
    }

    protected function configure(): void
    {
        $this->setName(self::COMMAND_NAME);
        $this->setDescription('Discovers the current external IP and updates the Digital ocean record');

        $this->addArgument('domain', InputArgument::REQUIRED, 'The domain for which a record should be updated');
        $this->addArgument('recordName', InputArgument::REQUIRED, 'Name of the record to be updated');

        $this->addOption('all', null, InputOption::VALUE_OPTIONAL, 'Update both versions', false);
        $this->addOption('ipv', null, InputOption::VALUE_OPTIONAL, 'Ip version to update (v4 or v6)', IPVersions::IP_V4);

        $this->setHelp('');
    }

    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->input = $input;
        $this->output = $output;

        $ipVersion = $input->getOption('ipv');
        $updateAll = $input->getOption('all') !== false;

        switch (true) {
            case $updateAll:
                $this->updateIp(IPVersions::IP_V4);
                $this->updateIp(IPVersions::IP_V6);
                break;
            case $ipVersion === IPVersions::IP_V4:
                $this->updateIp(IPVersions::IP_V4);
                break;
            case $ipVersion === IPVersions::IP_V6:
                $this->updateIp(IPVersions::IP_V6);
                break;
        }

        $output->writeln('<info>finished</info>');
    }

    private function updateIp(string $ipv): void
    {
        $recordInfo = $this->createRecordInfo($ipv);

        try {
            $this->output->writeln(['', sprintf('<comment>Updating the ipv %s address</comment>', $ipv)]);

            $this->updater->updateRecord($recordInfo);

            $this->output->writeln(sprintf('<comment>Ipv %s address updated successfully</comment>', $ipv));
        } catch (\Exception $exception) {
            $this->output->writeln([
                '',
                sprintf('<error>Failed to update ipv %s see error:', $ipv),
                sprintf('Error: %s</error>', $exception->getMessage())
            ]);
        }
    }

    private function createRecordInfo(string $ipVersion): RecordInfo
    {
        return new RecordInfo(
            $ipVersion,
            $this->input->getArgument('domain'),
            $this->input->getArgument('recordName')
        );
    }
}

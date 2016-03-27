<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use AppBundle\Fetcher\MangaNewsFetcher;

class EditorFetcherCommand extends ContainerAwareCommand {

    protected function configure() {

        $this
            ->setName('editor:fetch')
            ->setDescription('Fetch an editor')
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'Set an editor name'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $editorName = $input->getArgument('name');

        $output->writeln("Fetching " . $editorName);

        $em = $this->getContainer()->get('doctrine')->getEntityManager();

        switch($editorName) {

            case "manganews":

                $fetcher = new MangaNewsFetcher($em);
                $fetcher->fetch();

                break;

            default:

            $output->writeln("This editor does not exist");

                break;
        }
    }
}
<?php namespace Nerdial\Standards\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Nerdial\Standards\Generator\ChangelogGenerator;
use Nerdial\Standards\Helper\GitHelper;

class GenerateChangelogCommand extends Command
{

    protected static $defaultName = 'changelog';

    public function __construct()
    {
        parent::__construct();
    }
    protected function configure()
    {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Generate a CHANGELOG.md file based on all tags and commits')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command generates or overrides the changelog based on all commits history');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        if (!GitHelper::gitDirectoryExists()) {
            throw new \Exception('Make sure current direcory is a git repository by calling  "git init" ');
        }


        $listOfTags = \array_filter(\explode(\PHP_EOL, \shell_exec("git for-each-ref refs/tags --sort=-taggerdate --format='%(refname)' --count=2")));

        if (count($listOfTags) < 1) {
            throw new \Exception('You must have at least 2 tags to proceed.');
        }
        ChangelogGenerator::generateChanglogFile();

        $output->writeln('<info> Changelog file created </info>');

        // shell_exec("git commit --allow-empty -m  '  " . $type . ' : ' . $commitMessage . " '   ");
    }
}

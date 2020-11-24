<?php

declare(strict_types=1);

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpClient\HttpClient;

final class DownloadCommand extends Command
{
    protected static $defaultName = 'aeon:library:download';

    private ParameterBagInterface $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Downloads unpack and move to the source folder library')
            ->addArgument(
                'library',
                InputArgument::REQUIRED,
                'Library name'
            )
            ->addArgument(
                'version',
                InputArgument::REQUIRED,
                'Library version'
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $libraries = $this->parameterBag->get('aeon_libraries');

        $libraryKey = \mb_strtolower($input->getArgument('library'));
        $libraryVersion = \mb_strtolower($input->getArgument('version'));

        if (!\array_key_exists($libraryKey, $libraries)) {
            $io->error('Library '.$libraryKey.' does not exists.');

            return 1;
        }

        $libraryName = $libraries[$libraryKey]['name'];

        if (!\array_key_exists($libraryVersion, $libraries[$libraryKey]['versions'])) {
            $io->error('Library '.$libraryName.' version '.$libraryVersion.' does not exists.');

            return 1;
        }

        $library = $libraries[$libraryKey]['versions'][$libraryVersion];

        $tmp = \sys_get_temp_dir();
        $temporaryArchiveLocation = $tmp.DIRECTORY_SEPARATOR.$libraryName.'-'.$libraryVersion.'.zip';
        $temporaryLocation = $tmp.DIRECTORY_SEPARATOR.$libraryName.'-'.$libraryVersion;

        $fs = new Filesystem();
        $client = HttpClient::create();

        $progress = null;

        $response = $client->request(
            'GET',
            $library['source'],
        );

        if ($fs->exists($temporaryLocation)) {
            $fs->remove($temporaryLocation);
        }

        $fs->dumpFile($temporaryArchiveLocation, $response->getContent());

        $io->note('Temporary Archive Location: '.$temporaryArchiveLocation);

        $zip = new \ZipArchive();

        if (true === $zip->open($temporaryArchiveLocation)) {
            $zip->extractTo($temporaryArchiveLocation = $tmp.DIRECTORY_SEPARATOR);
            $zip->close();
        }

        $io->note('Temporary Location: '.$temporaryLocation);

        if ($fs->exists($library['destination'])) {
            $fs->remove($library['destination']);
        }

        $fs->mirror(
            $temporaryLocation,
            $library['destination']
        );

        $io->success('Library '.$libraryName.' version '.$libraryKey.' downloaded to: '.$library['destination']);

        return 0;
    }
}

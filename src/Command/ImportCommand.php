<?php
declare(strict_types=1);

namespace App\Command;


use App\Entity\Region;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ImportCommand
 * @package App\Command
 */
class ImportCommand extends Command
{
    protected static $defaultName = 'app:import';

    /** @var EntityManagerInterface $entityManager */
    private $entityManager;

    /**
     * ImportCommand constructor.
     * @param string $srcKoatuu
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->loadFile();
        $this->getContent();

        $this->parseRegions();
        $this->writeRegions();
        return 0;
    }

    public function loadFile()
    {

        $url = 'http://www.ukrstat.gov.ua/klasf/st_kls/koatuu.zip';
        $dir = '/var/www/app/data/';

        $zipKoatuu = $dir . 'koatuu.zip';
        copy($url, $zipKoatuu);

        $zip = new \ZipArchive();

        if ($zip->open($zipKoatuu) === TRUE) {
            for ($i = 0; $i < $zip->numFiles; ++$i) {
                $index = $zip->statIndex($i);
                if ("KOATUU_" === substr($index['name'], 0, 7)) {
                    $zip->extractTo($dir, $index['name']);
                }
            }
            $zip->close();
            unlink($zipKoatuu);
        }
    }

    /**
     * @return array
     */
    public function getContent()
    {
        $dir = '/var/www/app/data/';
        $file = scandir($dir);
        /** @var  $reader */
        $reader = new Xls();
        $spreadsheet = $reader->load($dir . $file[2]);
        $content = $spreadsheet->getActiveSheet()->toArray();
        return $content;
    }

    /**
     * @return array
     */
    public function parseRegions()
    {
        $regions = [];
        foreach ($this->getContent() as $item) {
            if (preg_grep('/^\d+0{8}$/', $item)) {
                $regions[] = $item;
            }
            unset($regions[0]);
            unset($regions[25]);
            unset($regions[26]);
        }
        return $regions;
    }

    public function writeRegions()
    {
        foreach ($this->parseRegions() as $regionValue) {
            $region = new Region();
            $region->setCode($regionValue[0]);
            $region->setName($regionValue[2]);
            $this->entityManager->persist($region);
        }
        $this->entityManager->flush();
        $this->entityManager->clear();
    }
}
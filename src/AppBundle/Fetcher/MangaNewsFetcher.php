<?php

namespace AppBundle\Fetcher;

use AppBundle\Entity\Sortie;
use AppBundle\Fetcher\FetcherInterface;

require_once dirname(__FILE__) . '/../phpQuery/phpQuery/phpQuery.php';

/**
 * Class MangaNewsFetcher
 * @package AppBundle\Fetcher
 */
class MangaNewsFetcher extends FetcherAbstract {

    private $targetURL = "http://www.manga-news.com/index.php/planning/";

    public function fetch()
    {
        //fetch for this month and this year
        $this->fetchPage(date('m'), date('Y'));

        //Fetch for the next 10 months...

        for($i = 1; $i < 10; $i++) {
            $nextMonth = date('m', strtotime('+' . $i . ' month', strtotime(date('Y-m-d'))));
            $nextYear = date('Y', strtotime('+' . $i . ' month', strtotime(date('Y-m-d'))));
            $this->fetchPage($nextMonth, $nextYear);
        }
    }

    private function fetchPage($month, $year)
    {
        $params = array('p_month' => $month, 'p_year' => $year);

        $url = $this->targetURL . '?' . http_build_query($params) . PHP_EOL;

        echo $url;

        $html = file_get_contents($url);

        $document = \phpQuery::newDocumentHTML($html);

        $rowsElements = $document->find("#planning tbody tr");

        $rowsElements->each(function ($rowNode) {

            $rowDocument = \phpQuery::newDocumentHTML($this->getInnerHTML($rowNode));

            $sortie = new Sortie();
            $sortie->setFromsite($this->targetURL);

            $i = 0;

            $rowDocument->find('td')->each(function ($colNode) use (&$i, &$sortie) {

                $val = trim($colNode->textContent);

                switch ($i) {

                    case 0:
                        $d = \DateTime::createFromFormat("d/m/Y", $val);
                        if ($d) {
                            $d->setTime(10, 0, 0);
                            $sortie->setDateSortie($d);
                        }
                        break;

                    case 1:
                        $sortie->setProductName($val);
                        break;

                    case 2:
                        $sortie->setEditor($val);
                        break;

                    default:

                        break;

                }

                $i++;

            });

            if ($sortie->getProductName() && $sortie->getDateSortie()) {
                $this->add($sortie);
            }

        });

        $this->save();
    }

}
<?php

namespace AppBundle\Fetcher;

use AppBundle\Entity\Sortie;
use AppBundle\Fetcher\FetcherInterface;
use Symfony\Component\Validator\Constraints\DateTime;

require_once dirname(__FILE__) . '/../phpQuery/phpQuery/phpQuery.php';

/**
 * Created by PhpStorm.
 * User: alexandrenguyen
 * Date: 20/03/16
 * Time: 20:01
 */

class MangaNewsFetcher extends FetcherAbstract {

    private $fromSite = "manganews.com";

    public function fetch()
    {
        $html = file_get_contents("http://www.manga-news.com/index.php/sorties/");

        $document = \phpQuery::newDocumentHTML($html);

        $rowsElements = $document->find("#sorties-list tr");

        $rowsElements->each(function($rowNode) {

            $rowDocument = \phpQuery::newDocumentHTML($this->getInnerHTML($rowNode));

            $sortie = new Sortie();

            $i = 0;

            $rowDocument->find('td')->each(function($colNode) use (&$i, &$sortie) {

                $val = trim($colNode->textContent);

                switch ($i) {

                    case 0:
                        $sortie->setProductName($val);
                        break;

                    case 1:
                        $sortie->setAuthor($val);
                        break;

                    case 2:
                        $sortie->setEditor($val);
                        break;

                    case 3:
                        $d = new \DateTime($val);
                        $sortie->setDateSortie($d);
                        break;

                    default:

                        break;

                }

                $sortie->setFromsite($this->fromSite);

                $i++;

            });

            if($sortie->getProductName() && $sortie->getDateSortie()) {
                $this->add($sortie);
            }

        });

        $this->save();

    }

}
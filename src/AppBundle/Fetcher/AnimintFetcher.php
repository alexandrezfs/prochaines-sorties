<?php
/**
 * Created by PhpStorm.
 * User: alexandrenguyen
 * Date: 02/04/16
 * Time: 09:36
 */

namespace AppBundle\Fetcher;

use AppBundle\Entity\Sortie;
use AppBundle\Fetcher\FetcherInterface;


class AnimintFetcher extends FetcherAbstract
{

    private $targetURL = "http://www.animint.com/guide/manga/";

    /**
     * @return mixed
     * Each child class will have to implements this.
     * It must use add() to add the release inside array
     * And save() at the end to persist it.
     */
    public function fetch()
    {
        $html = file_get_contents($this->targetURL);

        $document = \phpQuery::newDocumentHTML($html);

        $rowsElements = $document->find("#month_table tbody tr");

        $rowsElements->each(function($rowNode) {

            $rowDocument = \phpQuery::newDocumentHTML($this->getInnerHTML($rowNode));

            $sortie = new Sortie();
            $sortie->setFromsite($this->targetURL);

            $i = 0;

            $rowDocument->find('td')->each(function($colNode) use (&$i, &$sortie) {

                $val = trim($colNode->textContent);

                switch ($i) {

                    case 0:
                        $sortie->setProductName($val);
                        break;

                    case 1:
                        $val += date('Y');
                        $d = new \DateTime($val);
                        $sortie->setDateSortie($d);
                        break;

                    case 2:
                        $sortie->setEditor($val);
                        break;

                    default:

                        break;

                }

                $i++;

            });

            if($sortie->getProductName() && $sortie->getDateSortie()) {
                $this->add($sortie);
            }

        });

        $this->save();
    }
}
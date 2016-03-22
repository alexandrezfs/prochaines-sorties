<?php

namespace AppBundle\Fetcher;

use AppBundle\Fetcher\FetcherInterface;

require_once dirname(__FILE__) . '/../phpQuery/phpQuery/phpQuery.php';

/**
 * Created by PhpStorm.
 * User: alexandrenguyen
 * Date: 20/03/16
 * Time: 20:01
 */

class MangaNewsFetcher extends FetcherAbstract {

    public function fetch()
    {
        $html = file_get_contents("http://www.manga-news.com/index.php/sorties/");

        $document = \phpQuery::newDocumentHTML($html);

        $rowsElements = $document->find("#sorties-list tr");

        $rowsElements->each(function($rowNode) {
            echo '---row---' . PHP_EOL;

            $rowDocument = \phpQuery::newDocumentHTML($this->getInnerHTML($rowNode));

            $rowDocument->find('td')->each(function($colNode) {
                echo '---col---' . PHP_EOL;;
                echo trim($colNode->textContent) . PHP_EOL;
                echo '---endcol---' . PHP_EOL;;
            });

            echo '---endrow---' . PHP_EOL;;
        });

    }

}
<?php
/**
 * Created by PhpStorm.
 * User: alexandrenguyen
 * Date: 20/03/16
 * Time: 20:05
 */

namespace AppBundle\Fetcher;


abstract class FetcherAbstract {

    public abstract function fetch();

    public function getInnerHTML($Node)
    {
        $Document = new \DOMDocument();
        $Document->appendChild($Document->importNode($Node,true));
        return $Document->saveHTML();
    }
}
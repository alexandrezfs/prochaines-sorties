<?php
/**
 * Created by PhpStorm.
 * User: alexandrenguyen
 * Date: 20/03/16
 * Time: 20:05
 */

namespace AppBundle\Fetcher;


abstract class FetcherAbstract {


    private $sorties = array();
    private $em;

    function __construct($em)
    {
        $this->em = $em;
    }

    public abstract function fetch();

    public function add($sortie) {

        //Adding created_at and updated_at dates
        $sortie->setCreatedAt(new \DateTime('now'));
        $sortie->setUpdatedAt(new \DateTime('now'));

        echo "adding " . $sortie->getProductName() . PHP_EOL;

        $this->sorties[] = $sortie;
    }

    public function save() {

        foreach($this->sorties as $sortie) {

            echo "saving " . $sortie->getProductName() . PHP_EOL;

            $this->em->persist($sortie);
        }

        echo "flushing" . PHP_EOL;

        $this->em->flush();
    }

    public function getInnerHTML($Node)
    {
        $Document = new \DOMDocument();
        $Document->appendChild($Document->importNode($Node,true));
        return $Document->saveHTML();
    }
}
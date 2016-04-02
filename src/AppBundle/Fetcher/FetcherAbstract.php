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
    private $sortiesRepo;

    /**
     * @param $em
     * Actually get the EM and the repo
     */
    function __construct($em)
    {
        $this->em = $em;
        $this->sortiesRepo = $em->getRepository("AppBundle:Sortie");
    }

    /**
     * @return mixed
     * Each child class will have to implements this.
     * It must use add() to add the release inside array
     * And save() at the end to persist it.
     */
    public abstract function fetch();

    /**
     * @param $sortie
     * Add a release inside the array
     */
    protected function add($sortie) {

        //Adding created_at and updated_at dates
        $sortie->setCreatedAt(new \DateTime('now'));
        $sortie->setUpdatedAt(new \DateTime('now'));

        $this->sorties[] = $sortie;
    }

    /**
     * Persists the release array.
     */
    protected function save() {

        foreach($this->sorties as $sortie) {

            //Looking for an existing entry...
            $sortieExists = $this->sortiesRepo->findOneBy(array(
                "dateSortie" => $sortie->getDateSortie(),
                "productName" => $sortie->getProductName()
            ));

            //We only persists if this entry does not exists.
            if(!$sortieExists) {
                echo "saving " . $sortie->getProductName() . PHP_EOL;
                $this->em->persist($sortie);
            }

        }

        echo "flushing" . PHP_EOL;

        $this->em->flush();
    }

    /**
     * @param $Node
     * @return string
     * An util method to get HTML content inside a DomElement.
     */
    protected function getInnerHTML($Node)
    {
        $Document = new \DOMDocument();
        $Document->appendChild($Document->importNode($Node,true));
        return $Document->saveHTML();
    }
}
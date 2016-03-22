<?php
/**
 * Created by PhpStorm.
 * User: alexandrenguyen
 * Date: 20/03/16
 * Time: 20:05
 */

namespace AppBundle\Fetcher;


abstract class FetcherAbstract implements FetcherInterface {

    public abstract function fetch();
}
<?php

namespace Modus\Common\Model\Storage;

use Aura\Sql\ConnectionLocator;
use Aura\SqlQuery\QueryFactory;

abstract class Database
{
    /**
     * @var QueryFactory
     */
    protected $queryFactory;

    /**
     * @var ConnectionLocator
     */
    protected $locator;


    public function __construct(ConnectionLocator $locator, QueryFactory $queryFactory = null)
    {
        $this->locator = $locator;
        $this->queryFactory = $queryFactory;
    }
}

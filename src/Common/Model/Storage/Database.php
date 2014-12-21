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

    public function setConnectionLocator(ConnectionLocator $locator)
    {
        $this->locator = $locator;
    }

    public function setQueryFactory(QueryFactory $queryFactory)
    {
        $this->queryFactory = $queryFactory;
    }
}

<?php

namespace Modus\Common\Model\Storage;

use Aura\Sql;
use Aura\SqlQuery;

abstract class Database
{

    protected $locator;


    public function __construct(Sql\ConnectionLocator $locator, SqlQuery\QueryFactory $queryFactory = null)
    {
        $this->locator = $locator;
        $this->queryFactory = $queryFactory;
    }
}

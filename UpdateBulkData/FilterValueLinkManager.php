<?php


class FilterValueLinkManager
{
    /**
     * FilterValuesLinkManager constructor.
     *
     * @param ManagerRegistry        $registry
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct(FilterValuesLink::class, $registry, $entityManager);
    }

    /**
     * @param ExternalSource $source
     * @param string         $column
     *
     * @throws NonUniqueResultException
     *
     * @return FilterValuesLink|null
     */
    public function getFilterValueNullSourceValue(ExternalSource $source, string $column): ?FilterValuesLink
    {
        $qb = $this->getAllQB();
        $a  = $this->getAlias();

        $qb->andWhere("{$a}.sourceValue IS NULL")
            ->andWhere("{$a}.managedColumn = :column")
            ->andWhere("{$a}.source =:source")
            ->setParameters([
                'column' => $column,
                'source' => $source,
            ])
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * @param string $column
     *
     * @throws ConnectionException
     * @throws Exception|\Doctrine\DBAL\Driver\Exception
     */
    public function updateFiltersValuesLinks(string $column)
    {
        $sql = "INSERT INTO filter_values_links (source_name, source_value, managed_column)
                SELECT DISTINCT external_source_name, {$column}, '{$column}'
                FROM products as a
                WHERE NOT EXISTS(
                        SELECT DISTINCT source_value
                        FROM filter_values_links as b
                        WHERE b.source_value = a.{$column}
                           OR (b.source_value IS NULL AND a.{$column} IS NULL
                            AND b.source_name = a.external_source_name));
                ";
        /** @var Connection $connection */
        $connection = $this->registry->getConnection();

        $connection->setTransactionIsolation(TransactionIsolationLevel::READ_UNCOMMITTED); //needed for not locking reading from products table
        $query = $connection->prepare($sql);
        $connection->beginTransaction();
        $connection->setAutoCommit(false);
        $query->execute();
        $connection->commit();
    }
}
<?php

use App\DBAL\Types\FilterType;
use App\Manager\Filter\FilterValuesLinkManager;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateFiltersDataCommand extends Command
{
    /**
     * @var FilterValuesLinkManager
     */
    private FilterValuesLinkManager $filterValuesLinkManager;

    /**
     * @param FilterValuesLinkManager $filterValuesLinkManager
     * @param string|null             $name
     */
    public function __construct(
        FilterValuesLinkManager $filterValuesLinkManager,
        ?string $name = null
    ) {
        parent::__construct($name);

        $this->filterValuesLinkManager = $filterValuesLinkManager;
    }

    /**
     * {@inheritdoc}
     */
    public function configure(): void
    {
        parent::configure();

        $this
            ->setDescription('Update filters values')
            ->setHelp('This command will update filters values links')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach (FilterType::$columns as $column) {
            $this->filterValuesLinkManager->updateFiltersValuesLinks($column);
        }

        return 0;
    }
}
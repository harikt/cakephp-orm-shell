<?php
namespace Hkt\ORM;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use Cake\Datasource\ConnectionManager;

trait SchemaTrait
{
    /**
     * Helper method to get the schema collection.
     *
     * @return false|\Cake\Database\Schema\Collection
     */
    protected function _getSchema(InputInterface $input, OutputInterface $output)
    {
        $connection = $input->getOption('connection');
        $source = ConnectionManager::get($connection);
        if (!method_exists($source, 'schemaCollection')) {
            $msg = sprintf(
                'The "%s" connection is not compatible with orm caching, ' .
                'as it does not implement a "schemaCollection()" method.',
                $connection
            );
            $output->writeln('<error>' . $msg . '</error>');
            return false;
        }
        $config = $source->config();
        if (empty($config['cacheMetadata'])) {
            $output->writeln('Metadata cache was disabled in config. Enabling to clear cache.');
            $source->cacheMetadata(true);
        }
        return $source->schemaCollection();
    }
}

<?php
return [
    'service_manager' => [
        'invokables' => [
            'KmbPuppetDb\Http\Client' => 'Zend\Http\Client',
        ],
        'abstract_factories' => [
            'Zend\Log\LoggerAbstractServiceFactory',
        ],
        'factories' => [
            'KmbPuppetDb\Options\ModuleOptions' => 'KmbPuppetDb\Options\ModuleOptionsFactory',
            'KmbPuppetDb\Client' => 'KmbPuppetDb\ClientFactory',
            'KmbPuppetDb\Query\NodesEnvironmentsQueryBuilder' => 'KmbPuppetDb\Query\NodesEnvironmentsQueryBuilderFactory',
            'KmbPuppetDb\Query\NodesNamesQueryBuilder' => 'KmbPuppetDb\Query\NodesNamesQueryBuilderFactory',
            'KmbPuppetDb\Query\ReportsEnvironmentsQueryBuilder' => 'KmbPuppetDb\Query\ReportsEnvironmentsQueryBuilderFactory',
            'KmbPuppetDb\Service\Node' => 'KmbPuppetDb\Service\NodeFactory',
            'KmbPuppetDb\Service\NodeStatistics' => 'KmbPuppetDb\Service\NodeStatisticsFactory',
            'KmbPuppetDb\Service\Report' => 'KmbPuppetDb\Service\ReportFactory',
            'KmbPuppetDb\Service\ReportStatistics' => 'KmbPuppetDb\Service\ReportStatisticsFactory',
            'KmbPuppetDb\Service\FactNames' => 'KmbPuppetDb\Service\FactNamesFactory',
        ],
        'aliases' => [
            'nodeStatisticsService' => 'KmbPuppetDb\Service\NodeStatistics',
            'reportStatisticsService' => 'KmbPuppetDb\Service\ReportStatistics',
        ],
    ],
];

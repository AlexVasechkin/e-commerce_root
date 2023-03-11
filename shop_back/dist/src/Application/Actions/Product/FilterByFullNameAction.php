<?php

namespace App\Application\Actions\Product;

use App\Application\Services\Elasticsearch\Elasticsearch;

class FilterByFullNameAction
{
    private Elasticsearch $es;

    public function __construct(Elasticsearch $es)
    {
        $this->es = $es;
    }

    private function reloadDataSet(): array
    {
        $resultSet = [];

        $searchAfter = 0;

        do {
            $esResponse = $this->es->client->search([
                'index' => 'products',
                'size' => 10000,
                '_source' => ['id', 'full_name'],
                'body' => [
                    'query' => [
                        'bool' => [
                            'must' => []
                        ]
                    ],
                    'search_after' => [$searchAfter]
                ],
                'sort' => ['id']
            ])->asArray();

            $hits = ($esResponse['hits'] ?? [])['hits'] ?? [];

            if (count($hits) === 0) {
                break;
            }

            foreach ($hits as $hit) {

                $searchAfter = intval($hit['_id']);

                try {
                    $resultSet[] = [
                        'id' => $hit['_source']['id'],
                        'name' => $hit['_source']['full_name']
                    ];
                } catch (\Throwable $e) {}

            }

        } while (true);

        return $resultSet;
    }

    private function filterByWord(string $word, array &$dataSet): array
    {
        return array_values(array_column(array_filter($dataSet, function (array $item) use ($word) {
            return mb_strripos($item['name'], $word, 0, 'UTF-8') !== false;
        }), 'id'));
    }

    public function execute(string $searchString): array
    {
        $dataSet = $this->reloadDataSet();

        $words = explode(' ', $searchString);

        $resultIdList = array_column($dataSet, 'id');

        foreach ($words as $word) {
            $resultIdList = array_intersect(
                $resultIdList,
                $this->filterByWord($word, $dataSet)
            );
        }

        return array_values(array_unique($resultIdList));
    }
}

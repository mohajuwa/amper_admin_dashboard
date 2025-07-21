<?php
// app/Traits/GenericListController.php

namespace App\Traits;

trait GenericListController
{
    /**
     * Render a generic list view using configuration
     */
    protected function renderGenericList($records, $configMethod, $additionalData = [])
    {
        // Get configuration
        $config = $this->$configMethod();
        
        // Validate required configuration keys
        if (!isset($config['pageConfig']) || !isset($config['tableConfig']) || !isset($config['entityConfig'])) {
            throw new \Exception('Invalid configuration: missing required keys (pageConfig, tableConfig, entityConfig)');
        }

        // Prepare data for the view
        $viewData = array_merge([
            'records' => $records,
            'pageConfig' => $config['pageConfig'],
            'tableConfig' => $config['tableConfig'],
            'entityConfig' => $config['entityConfig'],
        ], $additionalData);

        // Determine the view to render
        $viewName = $config['pageConfig']['view'] ?? 'admin.partials.generic-list';

        return view($viewName, $viewData);
    }

    /**
     * Get common pagination parameters
     */
    protected function getPaginationParams($request)
    {
        return [
            'per_page' => $request->get('per_page', 15),
            'page' => $request->get('page', 1)
        ];
    }

    /**
     * Apply common filters to query
     */
    protected function applyCommonFilters($query, $request, $filters = [])
    {
        foreach ($filters as $field => $type) {
            if ($request->filled($field)) {
                switch ($type) {
                    case 'exact':
                        $query->where($field, $request->get($field));
                        break;
                    case 'like':
                        $query->where($field, 'like', '%' . $request->get($field) . '%');
                        break;
                    case 'date_from':
                        $query->whereDate($field, '>=', $request->get($field));
                        break;
                    case 'date_to':
                        $query->whereDate($field, '<=', $request->get($field));
                        break;
                    case 'numeric_from':
                        $query->where($field, '>=', $request->get($field));
                        break;
                    case 'numeric_to':
                        $query->where($field, '<=', $request->get($field));
                        break;
                    default:
                        $query->where($field, $request->get($field));
                }
            }
        }

        return $query;
    }
}
<?php
use Illuminate\Support\Facades\DB;

// app/Helpers/Helper.php
if (! function_exists('map_options')) {
    /**
     * Helper function to map the model options.
     *
     * @param string $model
     * @param string $idField
     * @param string $nameField
     * @return \Illuminate\Support\Collection
     */
    function map_options($model, $idField, $nameField)
    {
        return $model::all()->map(function($item) use ($idField, $nameField) {
            return [
                'id' => $item->$idField,
                'name' => $item->$nameField
            ];
        });
    }

    function map_options_raw($table, $idField, $nameField)
    {
        return DB::table($table)->get()->map(function($item) use ($idField, $nameField) {
            return [
                'id' => $item->$idField,
                'name' => $item->$nameField
            ];
        });
    }

    function create_collection(array $items)
    {
        return collect($items)->map(fn($item, $key) => ['id' => $key + 1, 'name' => $item]);
    }
}

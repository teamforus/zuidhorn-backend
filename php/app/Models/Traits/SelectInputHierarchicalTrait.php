<?php

namespace App\Models\Traits;
use Illuminate\Support\Facades\DB;

/**
 * summary
 */
trait SelectInputHierarchicalTrait
{
    public static function hierarchicalSelectOptions($placeholder = true, $target_id = false)
    {
        $rows = collect(DB::select(self::toSql()))->keyBy('id')->toArray();
        $options = [];

        foreach ($rows as &$row) {
            if (!isset($row->childs))
                $row->childs = [];

            if (is_null($row->parent_id)) {
                array_push($options, $row);
            } elseif (isset($rows[$row->parent_id])) {
                if (!isset($rows[$row->parent_id]->childs))
                    $rows[$row->parent_id]->childs = [];

                array_push($rows[$row->parent_id]->childs, $row);
            }
        }

        $rows = $options;
        $options = [];

        if ($placeholder) {
            $options[null] = 'Select category...';

            if (is_string($placeholder))
                $options[null] = $placeholder;
        }

        $array_walk = function(
            $rows, $offset, $array_walk) use (&$options, $target_id) {
            
            foreach ($rows as $row) {
                if ($row->id == $target_id)
                    continue;

                $options[$row->id] = 
                str_repeat('-&nbsp;', ($offset + 1) * 1) . $row->name;

                if (count($row->childs) > 0)
                    $array_walk($row->childs, $offset + 1, $array_walk);
            }
        };


        $array_walk($rows, 0, $array_walk);

        return $options;
    }
}

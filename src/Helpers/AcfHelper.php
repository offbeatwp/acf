<?php

namespace OffbeatWP\Acf\Helpers;

final class AcfHelper
{
    /**
     * Returns an option or <i>null</i> if the option does not pass the (optional) provided filter.
     * @see filter_var()
     * @param string $metaKey The key of the option to retrieve.
     * @param int $filter [optional] The ID of the filter to apply. The manual page lists the available filters.
     * @param array|int $options Associative array of options or bitwise disjunction of flags. If filter accepts options, flags can be provided in "flags" field of array. For the "callback" filter, callable type should be passed. The callback must accept one argument, the value to be filtered, and return the value after filtering/sanitizing it.
     * @return mixed
     */
    public static function getOption(string $metaKey, int $filter = FILTER_DEFAULT, $options = 0)
    {
        $value = get_field($metaKey, 'option');
        return filter_var($value, $filter) ? $value : null;
    }
}
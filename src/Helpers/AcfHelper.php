<?php

namespace OffbeatWP\Acf\Helpers;

final class AcfHelper
{
    /**
     * Returns an option or <i>null</i> if the option does not pass the (optional) provided filter.
     * @see filter_var()
     * @param string $metaKey The key of the option to retrieve.
     * @param int $filter [optional] The ID of the filter to apply. The manual page lists the available filters.
     * @return mixed
     */
    public static function getOption(string $metaKey, int $filter = FILTER_DEFAULT)
    {
        $value = get_field($metaKey, 'option');
        return filter_var($value, $filter) ? $value : null;
    }
}
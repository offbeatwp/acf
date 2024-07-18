<?php

namespace OffbeatWP\Acf\Traits;

trait AcfFieldsTrait
{
    /**
     * This function will return a custom field value for a specific field name/key + post_id.<br>
     * There is a 3rd parameter to turn on/off formating.<br>
     * This means that an image field will not use its 'return option' to format the value but return only what was saved in the database.
     * @param string $name The field name or key.
     * @param bool $format Whether or not to format the value as described above.
     * @return mixed
     */
    final public function getField(string $name, bool $format = true)
    {
        if (!function_exists('get_field')) {
            return null;
        }

        return get_field($name, $this->getId(), $format);
    }

    /**
     * This function will return an array containing all the field data for a given field_name.
     * @param string $name The field name or key.
     * @param bool $format Whether to format the field value.
     * @return array|null
     */
    final public function getFieldObject(string $name, bool $format = true)
    {
        if (!function_exists('get_field_object')) {
            return null;
        }

        return get_field_object($name, $this->getId(), $format);
    }

    /**
     * This function will update a value in the database
     * @param string $name The field name or key.
     * @param mixed $value The value to save in the database.
     * @return bool
     */
    final public function updateField(string $name, $value): bool
    {
        if (!function_exists('update_field')) {
            return false;
        }

        return update_field($name, $value, $this->getId());
    }
}
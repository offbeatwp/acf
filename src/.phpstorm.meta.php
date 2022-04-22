<?php // This class exists so that PHPStorm editors will not complain about certain macro'd methods not existing.

namespace OffbeatWP\Content\Post {
    class PostModel {
        public function __construct($post = null);

        /**
         * This function will return a custom field value for a specific field name/key.
         * @param non-empty-string $key The field name or key.
         * @param bool $format Whether or not to format the value. When <i>false</i>, a field will not use its 'return option' to format the value but return only what was saved in the database.
         * @return mixed
         */
        public function getField(string $key, bool $format = true);

        /**
         * Returns the settings of a specific field.
         * Each field contains many settings such as a label, name and type.
         * This function can be used to load these settings as an array along with the field’s value.
         * @param non-empty-string $key The field name or key.
         * @param bool $format Whether or not to format the value.
         * @return mixed
         */
        public function getFieldObject(string $key, bool $format = true);

        /**
         * @param non-empty-string $key The field name or key.
         * @param mixed $value The value to save in the database.
         * @return bool Returns <i>true</i> on successful update or <i>false</i> on failure.
         */
        public function updateField(string $key, $value): bool;
    }
}

namespace OffbeatWP\Content\Taxonomy {
    class TermModel {
        public function __construct($term);

        /**
         * This function will return a custom field value for a specific field name/key.
         * @param non-empty-string $key The field name or key.
         * @param $format Whether or not to format the value. When <i>false</i>, a field will not use its 'return option' to format the value but return only what was saved in the database.
         * @return mixed
         */
        public function getField(string $key, bool $format = true);
    }
}
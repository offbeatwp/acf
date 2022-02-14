<?php

// This class exists so that PHPStorm will not complain about certain macro'd methods not existing.
namespace OffbeatWP\Content\Post {
    class PostModel {
        /**
         * This function will return a custom field value for a specific field name/key.
         * @param non-empty-string $key The field name or key.
         * @param bool $format Whether or not to format the value. When <i>false</i>, a field will not use its 'return option' to format the value but return only what was saved in the database.
         * @return mixed
         */
        public function getField(string $key, bool $format = true);

        /**
         * @param non-empty-string $key The field name or key.
         * @param mixed $value The value to save in the database.
         * @return bool True on successful update, false on failure.s
         */
        public function updateField(string $key, $value): bool;
    }

    class TermModel {
        /**
         * This function will return a custom field value for a specific field name/key.
         * @param non-empty-string $key
         * @param $format Whether or not to format the value. When <i>false</i>, a field will not use its 'return option' to format the value but return only what was saved in the database.
         * @return mixed
         */
        public function getField(string $key, bool $format = true);
    }
}
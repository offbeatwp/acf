<?php

namespace OffbeatWP\Content\Post {
    class PostModel {
        /**
         * @param non-empty-string $key
         * @param bool $format
         * @return mixed
         */
        public function getField(string $key, bool $format = true);

        /**
         * @param non-empty-string $key
         * @param mixed $value
         * @return bool
         */
        public function updateField(string $key, $value): bool;
    }

    class TermModel {
        /**
         * @param non-empty-string $key
         * @param bool $format
         * @return mixed
         */
        public function getField(string $key, bool $format = true);
    }
}
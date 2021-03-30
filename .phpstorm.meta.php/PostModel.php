<?php

namespace  {
    exit("This file should not be included, only analyzed by your IDE");
}


namespace OffbeatWP\Content\Post {
    class PostModel {
        public function getField(string $selector, bool $format_value = true): mixed;
        public function getFieldObject(string $selector, bool $format_value = true): array;
        public function updateField(string $selector, bool $post_id = true): bool;
    }
}
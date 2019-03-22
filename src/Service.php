<?php
namespace OffbeatWP\Acf;

use OffbeatWP\Content\Taxonomy\TermModel;
use OffbeatWP\Services\AbstractService;
use OffbeatWP\Content\Post\PostModel;

class Service extends AbstractService {

    public function register() {
        offbeat('hooks')->addFilter('post_attribute', Hooks\AcfPostAttributeFilter::class, 10, 3);
        offbeat('hooks')->addFilter('term_attribute', Hooks\AcfTermAttributeFilter::class, 10, 3);

        PostModel::macro('getField', function ($name, $format = true) {
            return get_field($name, $this->getId(), $format);
        });

        TermModel::macro('getField', function ($name, $format = true) {
            return get_field($name, $this->wpTerm, $format);
        });

        offbeat('hooks')->addFilter('acf/update_value/type=relationship', Hooks\AcfPostRelationships::class, 10, 4);
        
        offbeat('hooks')->addAction('acf/init', Hooks\AcfGuiAction::class);
    }
}

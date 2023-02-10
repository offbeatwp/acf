<?php
namespace OffbeatWP\Acf\ComponentsGui;

use OffbeatWP\Services\AbstractService;
use OffbeatWP\Form\Form;
use OffbeatWP\AcfCore\FieldsMapperReverse;

class ComponentsGuiService extends AbstractService {

    public function register() {
        add_filter('acf/location/rule_types', [$this, 'locationRuleTypes']);
        add_filter('acf/location/rule_values/offbeatwp_component', [$this,'locationRulesValues']);
        add_filter('acf/location/rule_match/offbeatwp_component', [$this, 'locationRulesMatch'], 10, 3);
        add_filter('offbeatwp/component/form', [$this, 'registerFieldsOnComponent'], 10, 2);
    }

    /**
     * @param array $choices
     * @return array
     */
    public function locationRuleTypes($choices): array
    {
        $choices['OffbeatWP']['offbeatwp_component'] = 'Component';

        return $choices;
    }

    /**
     * @param array $choices
     * @return array
     */
    public function locationRulesValues($choices)
    {
        $components = offbeat('components')->get();

        if ($components) {
            foreach ($components as $component) {
                if (!method_exists($component, 'settings') || (!$component::supports('pagebuilder') && !$component::supports('widget') && !$component::supports('editor'))) {
                    continue;
                }

                $componentSettings = $component::settings();
                $choices[$component::getSlug()] = $componentSettings['name'];
            }
        }
        
        return $choices;
    }

    public function locationRulesMatch($match, $rule, $options)
    {   
        if (!isset($options['offbeatwp_component']) || $options['offbeatwp_component'] != $rule['value']) {
            return $match;
        }

        return true;
    }

    public function registerFieldsOnComponent($form, $component): Form
    {
        if (!function_exists('acf_get_field_groups')) {
            return $form;
        }

        $transientName = 'offbeat/acf/components/fieldgroups/' . $component::getSlug();
        $fieldGroups = get_transient($transientName);

        if ($fieldGroups === false) {
            $fieldGroups = acf_get_field_groups(['offbeatwp_component' => $component::getSlug()]);

            set_transient($transientName, $fieldGroups);
        }

        if (empty($fieldGroups)) {
            return $form;
        }

        $fields = [];

        foreach ($fieldGroups as $fieldGroup) {
            $fieldGroupFields = acf_get_fields($fieldGroup['key']);

            if ($fieldGroupFields) {
                $fields = array_merge($fields, $fieldGroupFields);
            }
        }

        $acfDefinedForm = new Form();
        $acfDefinedForm->setFieldPrefix($component::getSlug());
        FieldsMapperReverse::map($fields, $acfDefinedForm);

        $injectAcfFields = $component::getSetting('injectAcfFields');

        if ($injectAcfFields === 'top') {
            $acfDefinedForm->add($form);
            return $acfDefinedForm;
        }

        $form->add($acfDefinedForm, true);

        return $form;
    }
}

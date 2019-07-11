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

    public function locationRuleTypes($choices)
    {
        $choices['OffbeatWP']['offbeatwp_component'] = 'Component';

        return $choices;
    }

    public function locationRulesValues($choices)
    {
        $components = offbeat('components')->get();

        if (!empty($components)) foreach($components as $componentKey => $component) {
            if (!method_exists($component, 'settings') || (!$component::supports('pagebuilder') && !$component::supports('widget') && !$component::supports('editor'))) continue;

            $componentSettings = $component::settings();
            $choices[$component::getSlug()] = $componentSettings['name'];
        }
        
        return $choices;
    }

    public function locationRulesMatch($match, $rule, $options)
    {   
        if (!isset($options['offbeatwp_component']) || $options['offbeatwp_component'] != $rule['value']) return $match;

        return true;
    }

    public function registerFieldsOnComponent($form, $component) {
        $fieldGroups = acf_get_field_groups(['offbeatwp_component' => $component::getSlug()]);

        if ($component::getSlug() == 'open-acount-extended') {
            error_log('pino');
            error_log(print_r($fieldGroups,true));
        }

        if (empty($fieldGroups)) return $form;

        $fields = [];

        foreach ($fieldGroups as $fieldGroup) {
            $fieldGroupFields = acf_get_fields($fieldGroup['key']);

            if(!empty($fieldGroupFields))
                $fields = array_merge($fields, $fieldGroupFields);
        }

        $acfDefinedForm = new Form();
        $acfDefinedForm->setFieldPrefix($component::getSlug());
        FieldsMapperReverse::map($fields, $acfDefinedForm);

        $form->add($acfDefinedForm, true);

        return $form;

    }
}

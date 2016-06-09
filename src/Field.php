<?php

namespace ContentTypePicker;

class Field extends \acf_field
{
  public $defaults;

  public function __construct($logger)
  {
    $this->name = 'content_type_picker';
    $this->label = __('Content Type Picker');
    $this->category = __("Choice",'acf');
    $this->defaults = array(
      "multiple" =>  0
    );

    parent::__construct();
  }

  function render_field_settings( $field )
  {
    $key = $field['name'];

    acf_render_field_setting($field, array(
      'label'        => 'Available Vocabs',
      'instructions' => 'Choose content types to activate on this field.',
      'type'         =>  'checkbox',
      'name'         =>  'content_types',
      'choices'      =>  $this->getContentTypes()
    ));

    acf_render_field_setting($field, array(
      'label' => 'Select multiple values?',
      'type'  =>  'radio',
      'name'  =>  'multiple',
      'choices' =>  array(
        1 =>  __("Yes",'acf'),
        0 =>  __("No",'acf'),
      )
    ));

  }

  protected function getContentTypes()
  {
    $types = get_post_types(array("publicly_queryable" => true), "obejcts");

    foreach ($types as $type => &$details) {
      $details = $details->label;
    }

    return $types;
  }

  public function render_field($field)
  {
    $value = is_array($field["value"]) ? $field["value"] : array($field["value"]);

    $multiple = "";
    
    if($field["multiple"]) {

      $multiple = ' multiple="multiple" size="5" ';
      $field['name'] .= '[]';
    }

    echo '<select id="' . $field['id'] . '" class="' . $field['class'] . '" name="' . $field['name'] . '" ' . $multiple . ' >';

    $allTypes = $this->getContentTypes();
    $availableTypes = $field["content_types"];

    if (!empty($availableTypes)) {

      foreach($availableTypes as $type) {

        $selected = in_array($type, $value) ? "selected='selected'" : "";
        echo "<option value='{$type}' {$selected}>{$allTypes[$type]}</option>";
      }
    }

    echo "</select>";
  }

}

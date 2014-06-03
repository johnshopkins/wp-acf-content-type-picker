<?php

namespace ContentTypePicker;

class Field extends \acf_field
{   
  public $settings;
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

    $this->settings = array(
        'path' => apply_filters('acf/helpers/get_path', __FILE__),
        'dir' => apply_filters('acf/helpers/get_dir', __FILE__),
        'version' => '1.0.0'
    );
  }

  function create_options( $field )
  {
    $key = $field['name'];

    ?>
    <tr class="field_option field_option_<?php echo $this->name; ?>">
      <td class="label">
        <label><?php _e("Select multiple values?",'acf'); ?></label>
      </td>
      <td>
        <?php 
        do_action('acf/create_field', array(
          'type'  =>  'radio',
          'name'  =>  'fields['.$key.'][multiple]',
          'value' =>  $field['multiple'],
          'choices' =>  array(
            1 =>  __("Yes",'acf'),
            0 =>  __("No",'acf'),
          ),
          'layout'  =>  'horizontal',
        ));
        ?>
      </td>
    </tr>
    <?php
  }

  protected function getContentTypes()
  {
    return get_post_types(array(
      "public" => true
    ), "objects");
  }
    
  public function create_field($field)
  {
    $value = $field["value"] ? $field["value"] : "inherit";

    $multiple = "";
    if($field["multiple"]) {
      
      $multiple = ' multiple="multiple" size="5" ';
      $field['name'] .= '[]';
    } 

    echo "<div class='acf-input-wrap'>";
    echo '<select id="' . $field['id'] . '" class="' . $field['class'] . '" name="' . $field['name'] . '" ' . $multiple . ' >';

    $types = $this->getContentTypes();

    foreach($types as $k => $v) {

      $selected = in_array($k, $value) ? "selected='selected'" : "";
      echo "<option value='{$k}' {$selected}>{$v->label}</option>";
    }

    echo "</select>";
    echo "</div>";
  }

}

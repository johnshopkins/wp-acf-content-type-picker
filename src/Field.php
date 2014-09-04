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
        <label><?php _e("Available Vocabs",'acf'); ?></label>
        <p class="description"><?php _e("Choose content types to activate on this field.",'acf'); ?></p>
      </td>
      <td>
        <?php

        do_action('acf/create_field', array(
          'type'      =>  'checkbox',
          'name'      =>  'fields['.$key.'][content_types]',
          'value'     =>  isset($field['content_types']) ? $field['content_types'] : 1,
          'layout'    =>  'horizontal',
          'choices'   =>  $this->getContentTypes()
        ));
        
        ?>
      </td>
    </tr>
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
    $types = get_post_types(array("publicly_queryable" => true), "obejcts");

    foreach ($types as $type => &$details) {
      $details = $details->label;
    }

    return $types;
  }
    
  public function create_field($field)
  {
    $value = is_array($field["value"]) ? $field["value"] : array($field["value"]);

    $multiple = "";
    if($field["multiple"]) {
      
      $multiple = ' multiple="multiple" size="5" ';
      $field['name'] .= '[]';
    } 

    echo "<div class='acf-input-wrap'>";
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
    echo "</div>";
  }

}

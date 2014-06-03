<?php
/*
Plugin Name: ContentTypePicker
Description: 
Author: johnshopkins
Version: 0.1
*/

class ContentTypePickerMain
{
  protected $logger;

  public function __construct($logger)
  {
    $this->logger = $logger;

    add_action('acf/register_fields', function () {
      new \ContentTypePicker\Field($this->logger);
    });
  }
}

new ContentTypePickerMain($wp_logger);

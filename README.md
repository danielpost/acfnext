ACF PHP
=========================

A framework to register [Advanced Custom Fields](https://wordpress.org/plugins/advanced-custom-fields/) fields using PHP.

## Why use ACF PHP?

ACF PHP makes it easier to register ACF fields using PHP by automating the tedious parts of adding new fields and adding defaults that make sense.

**Without ACF PHP:**

```php
array (
	'key' => 'field_random_key',
	'label' => 'Header Title',
	'name' => 'header_title',
	'type' => 'text',
),
array (
	'key' => 'field_another_random_key',
	'label' => 'Header Subtitle',
	'name' => 'header_subtitle',
	'type' => 'text',
),
array (
	'key' => 'field_yet_another_random_key',
	'label' => 'Header Background Image',
	'name' => 'header_bg_image',
	'type' => 'image',
)
```

**With ACF PHP:**

```php
'header_title',
'header_subtitle'
'header_bg_image' => array(
	'label' => 'Header Background Image',
	'type' => 'image',
)
```

## Features

* Automatically generates a unique key for each field using the field group key and field name.
* Adds a label automatically if you don’t add one using the field name, and adds a default field type (example: `header_title` becomes a Text field with label `Header Title`).
* Easily reuse fields by putting them in a variable.
* Options to hide the "Custom Fields" menu (including redirects when accessed directly) and to disable ACF on the front end (useful when using native WordPress function to retrieve post meta, eg. `get_post_meta()`).
* All ACF fields are supported, including Repeaters and Flexible Content.

## Usage

Download the `.zip` file from GitHub to install in your WordPress admin.

All options for ACF fields are supported. For more information, please check out the official [Advanced Custom Fields website](https://www.advancedcustomfields.com/resources/register-fields-via-php/).

**Basic example:**

*Setting up the metabox:*

```php
$page_settings_metabox = array(
	'key' => 'page_settings', // make sure the key is unique for each group
	'title' => 'Page Settings',
	'location' => array(
		array(
			array(
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'page',
			),
		),
	),
	'fields' => array(
		'text' => array(
			'required' => 1,
		),
		'textarea' => array(
			'type' => 'textarea',
			'rows' => 5,
			'instructions' => 'Add optional field instructions.',
		),
	),
);
```

*Register the metabox:*

```php
if ( function_exists( 'acf_php_add_local_field_group' ) ) {
	acf_php_add_local_field_group( $page_settings_metabox );
}
```

*Retrieve the meta values:*

```php
$text_content = get_post_meta( get_the_ID(), 'text', true );
$textarea_content = get_post_meta( get_the_ID(), 'textarea', true );
```

Alternatively, use standard ACF functions.

**Repeater example:**

```php
'fields' => array(
	'repeater' => array(
		'type' => 'repeater',
		'button_label' => 'Add Row',
		'sub_fields' => array(
			'text' => array(
				'required' => 1,
			),
			'textarea' => array(
				'type' => 'textarea',
				'rows' => 5,
				'instructions' => 'Add optional field instructions.',
			),
		),
	),
),
```

**Flexible Content example:**

```php
'fields' => array(
	'flexible_content' => array(
		'type' => 'flexible_content',
		'button_label' => 'Add Section',
		'layouts' => array(
			'example_layout' => array(
				'sub_fields' => array(
					'text_field',
					'true_false_field' => array(
						'type' => 'true_false',
					),
				),
			),
		),
	),
),
```

**Reusable field example:**

```php
$checkbox = array(
	'type' => 'checkbox',
	'choices' => array(
		'red' => 'Red',
		'white' => 'White',
		'blue' => 'Blue',
	),
);

'fields' => array(
	'checkbox_field' => $checkbox,
),
```

## To-do
* Improve ACF_PHP_Metabox class (leaner code).
* Add support for multiple fields in a single variable.
* Improve field group location settings.


<?php

    /*  for PRO users! - 
     * @package     Redux_Framework
     * @subpackage  Fields
     * @access      public
     * @global      $optname
     * @internal    Internal Note string
     * @link        http://reduxframework.com
     * @method      Test
     * @name        $globalvariablename
     * @param       string  $this->field['test']    This is cool.
     * @param       string|boolean  $field[default] Default value for this field.
     * @return      Test
     * @see         ParentClass
     * @since       Redux 3.0.9
     * @todo        Still need to fix this!
     * @var         string cool
     * @var         int notcool
     * @param       string[] $options {
     * @type        boolean $required Whether this element is required
     * @type        string  $label    The display name for this element
     */

// Exit if accessed directly
    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    }

    if ( ! class_exists( 'ReduxFramework_textarea' ) ) {
        class ReduxFramework_textarea {

            /*  for PRO users! - *
             * Field Constructor.
             *
             * @param       $value  Constructed by Redux class. Based on the passing in $field['defaults'] value and what is stored in the database.
             * @param       $parent ReduxFramework object is passed for easier pointing.
             *
             * @since ReduxFramework 1.0.0
             * @type string $field  [test] Description. Default <value>. Accepts <value>, <value>.
             */
            function __construct( $field = array(), $value = '', $parent ) {
                $this->parent = $parent;
                $this->field  = $field;
                $this->value  = $value;
            }

            /*  for PRO users! - *
             * Field Render Function.
             * Takes the vars and outputs the HTML for the field in the settings
             *
             * @since ReduxFramework 1.0.0
             *
             * @param array $arr (See above)
             *
             * @return Object A new editor object.
             **/
            function render() {

                $this->field['placeholder'] = isset( $this->field['placeholder'] ) ? $this->field['placeholder'] : "";
                $this->field['rows']        = isset( $this->field['rows'] ) ? $this->field['rows'] : 6;

                ?>
                <textarea name="<?php echo $this->field['name'] . $this->field['name_suffix']; ?>"
                          id="<?php echo $this->field['id']; ?>-textarea"
                          placeholder="<?php echo esc_attr( $this->field['placeholder'] ); ?>"
                          class="large-text <?php echo $this->field['class']; ?>"
                          rows="<?php echo $this->field['rows']; ?>"><?php echo $this->value; ?></textarea>
            <?php
            }
        }
    }
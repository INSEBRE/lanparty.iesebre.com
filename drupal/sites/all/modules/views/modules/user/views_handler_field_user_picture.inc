<?php
// $Id: views_handler_field_user_picture.inc,v 1.1 2008/09/03 19:21:30 merlinofchaos Exp $

/**
 * Field handler to provide simple renderer that allows using a themed user link
 */
class views_handler_field_user_picture extends views_handler_field {
  function construct() {
    parent::construct();
    $this->additional_fields['uid'] = 'uid';
    $this->additional_fields['name'] = 'name';
  }

  function render($values) {
    // Fake an account object.
    $account = new stdClass();
    $account->uid = $values->{$this->aliases['uid']};
    $account->name = $values->{$this->aliases['name']};
    $account->picture = $values->{$this->field_alias};

    return theme('user_picture', $account);
  }
}

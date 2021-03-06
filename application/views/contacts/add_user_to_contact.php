<?php

  set_page_title($user->isNew() ? lang('add user account') : lang('edit user account'));
  if ($company->isOwner()) {
    administration_tabbed_navigation(ADMINISTRATION_TAB_COMPANY);
    administration_crumbs(array(
      array(lang('company'), $company->getViewUrl()),
      array($contact->getDisplayName(), $contact->getCardUrl()),
      array($user->isNew() ? lang('add user account') : lang('edit user account'))
    ));
  } else {
    administration_tabbed_navigation(ADMINISTRATION_TAB_CLIENTS);
    administration_crumbs(array(
      array(lang('clients'), get_url('administration', 'clients')),
      array($company->getName(), $company->getViewUrl()),
      array($contact->getDisplayName(), $contact->getCardUrl()),
      array($user->isNew() ? lang('add user account') : lang('edit user account'))
    ));
  } // if
  
  add_stylesheet_to_page('admin/user_permissions.css');

?>
<?php if ($contact->hasAvatar()) { ?>
  <span class="icon"><img src="<?php echo $contact->getAvatarUrl() ?>" alt="<?php echo clean($contact->getDisplayName()) ?> <?php echo lang('avatar') ?>" /></span>
<?php } ?>
<?php echo lang('contact').': '.$contact->getDisplayName() ?>
<div class="clear"></div>
  
<form action="<?php if ($user->isNew()) { echo $contact->getAddUserAccountUrl(); } else { echo $contact->getEditUserAccountUrl(); }?>" method="post">

<?php tpl_display(get_template_path('form_errors')) ?>

  <div>
    <?php echo label_tag(lang('username'), 'userFormName', true) ?>
    <?php echo text_field('user[username]', array_var($user_data, 'username'), array('class' => 'medium', 'id' => 'userFormName')) ?>
  </div>
  
  <div>
    <?php echo label_tag(lang('email address'), 'userFormEmail', true) ?>
    <?php echo text_field('user[email]', array_var($user_data, 'email'), array('class' => 'long', 'id' => 'userFormEmail')) ?>
  </div>
  
  <div>
    <?php echo label_tag(lang('timezone'), 'userFormTimezone', true)?>
    <?php echo select_timezone_widget('user[timezone]', array_var($user_data, 'timezone'), array('id' => 'userFormTimezone', 'class' => 'long combobox')) ?>
  </div>
  
<?php if ($user->isNew() || logged_user()->isAdministrator()) { ?>
  <fieldset>
    <legend><?php echo lang('password') ?></legend>
    <div>
      <?php echo radio_field('user[password_generator]', array_var($user_data, 'password_generator') == 'random', array('value' => 'random', 'class' => 'checkbox', 'id' => 'userFormRandomPassword')) ?> <?php echo label_tag(lang('user password generate'), 'userFormRandomPassword', false, array('class' => 'checkbox'), '') ?>
    </div>
    <div>
      <?php echo radio_field('user[password_generator]', array_var($user_data, 'password_generator') == 'specify', array('value' => 'specify', 'class' => 'checkbox', 'id' => 'userFormSpecifyPassword')) ?> <?php echo label_tag(lang('user password specify'), 'userFormSpecifyPassword', false, array('class' => 'checkbox'), '') ?>
    </div>
    <div id="userFormPasswordInputs">
      <div>
        <?php echo label_tag(lang('password'), 'userFormPassword', true) ?>
        <?php echo password_field('user[password]', null, array('id' => 'userFormPassword')) ?>
      </div>
      
      <div>
        <?php echo label_tag(lang('password again'), 'userFormPasswordA', true) ?>
        <?php echo password_field('user[password_a]', null, array('id' => 'userFormPasswordA')) ?>
      </div>
    </div>
  </fieldset>
<?php } // if ?>
<?php if (logged_user()->isAdministrator()) { ?>
  <div>
    <?php echo label_tag(lang('can manage projects'), null, true) ?>
    <?php echo yes_no_widget('user[can_manage_projects]', 'userFormCanManageProjects', array_var($user_data, 'can_manage_projects'), lang('yes'), lang('no')) ?>
  </div>
<?php } // if ?>
<?php if ($company->isOwner()) { ?>
  <fieldset>
    <legend><?php echo lang('options') ?></legend>
<?php if (!$contact->isAccountOwner()) { ?>
    <div>
      <?php echo label_tag(lang('is administrator'), null, true) ?>
      <?php echo yes_no_widget('user[is_admin]', 'userFormIsAdmin', array_var($user_data, 'is_admin'), lang('yes'), lang('no')) ?>
    </div>
<?php } // if ?>
    <div>
      <?php echo label_tag(lang('is auto assign'), null, true) ?>
      <?php echo yes_no_widget('user[auto_assign]', 'userFormAutoAssign', array_var($user_data, 'auto_assign'), lang('yes'), lang('no')) ?>
    </div>
<?php   if (extension_loaded('ldap')) { ?>
    <div>
      <?php echo label_tag(lang('use LDAP'), null, true) ?>
      <?php echo yes_no_widget('user[use_LDAP]', 'userFormUseLDAP', array_var($user_data, 'use_LDAP'), lang('yes'), lang('no')) ?>
    </div>
<?php   } else { ?>
    <input type="hidden" name="user[use_LDAP]" value="0" />
    <div>
      <?php echo label_tag(lang('use LDAP'), null, false) ?>
      <?php echo lang('no ldap functions') ?>
    </div>
<?php   } // if ?>
  </fieldset>
<?php } else { ?>
  <input type="hidden" name="user[is_admin]" value="0" />
  <input type="hidden" name="user[auto_assign]" value="0" />
  <input type="hidden" name="user[use_LDAP]" value="0" />
<?php } // if ?>
  
  <div class="formBlock">
    <?php echo label_tag(lang('send new account notification'), null, true) ?>
    <?php echo yes_no_widget('user[send_email_notification]', 'userFormEmailNotification', array($user_data, 'send_email_notification'), lang('yes'), lang('no')) ?>
    <br /><span class="desc"><?php echo lang('send new account notification desc') ?></span>
  </div>
  
  <?php echo submit_button($user->isNew() ? lang('add user account') : lang('edit user account')) ?>
</form>
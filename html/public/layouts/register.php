<?php defined('ABSPATH') or die; ?>
<div class="container">
    <form name="access" action="<?php print $this->form_open() ?>" method="post">
        <fieldset>
            <caption><?php print __('Login','coers_passkey') ?></caption>
            <input type="text" name="email" value="" placeholder="<?php
                print __('Enter your email address','coders_passkey') ?>" ?>
        </fieldset>
        <button class="button" type="submit" name="action" value="login"><?php
            print __('Enter','coders_passkey') ?></button>
    </form>
</div>
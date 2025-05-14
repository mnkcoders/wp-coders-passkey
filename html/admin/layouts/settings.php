<?php defined('ABSPATH') or die;?>
<h1 class="wp-heading-inline"><?php print get_admin_page_title() ?></h1>

<?php $this->part_messages() ?>

<a class="button" href="<?php
    print $this->link_settings(array('action'=>'rewrite')) ?>" target="_self"><?php
    print __('Flush Rewrite Rules','coders_passkey') ?></a>
<?php defined('ABSPATH') or die;?>
<ul class="messages container">
    <?php foreach($this->list_messages() as $message ) : ?>
    <li class="message dismissible type-<?php
        print $message['type'] ?>"><?php
        print $message['content'] ?></li>
    <?php endforeach; ?>
</ul>
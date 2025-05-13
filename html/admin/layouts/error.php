<?php defined('ABSPATH') or die; ?>
<div class="container">
    <?php foreach( $this->list_messages() as $message ) : ?>
    <p class="<?php print $message['type'] ?>"><?php print $message['content']; ?></p>
    <?php endforeach; ?>
</div>
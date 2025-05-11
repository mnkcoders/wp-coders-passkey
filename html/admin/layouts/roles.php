<?php defined('ABSPATH') or die;?>
<h1 class="wp-heading-inline"><?php print get_admin_page_title() ?></h1>

<ul>
<?php foreach( $this->list_roles() as $role ) : ?>

    <li><?php var_dump($role) ?></li>

<?php endforeach; ?>
</ul>
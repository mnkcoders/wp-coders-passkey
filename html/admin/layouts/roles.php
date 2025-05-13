<?php defined('ABSPATH') or die;?>
<h1 class="wp-heading-inline"><?php print get_admin_page_title() ?></h1>

<table class="wp-list-table widefat fixed striped table-view-excerpt roles">
    <thead>
        <tr>
            <th><?php print __('Role','coders_passkey')  ?></th>
            <th><?php print __('Title','coders_passkey')  ?></th>
            <th><?php print __('Created','coders_passkey')  ?></th>
        </tr>        
    </thead>
    <tbody>
<?php foreach( $this->list_roles() as $role ) : ?>
        <tr>
            <td><a href="<?php
                print $this->link_roles(array('role'=>$role->role))?>" target="_self"><?php
                print $role->role ?></a></td>
            <td><?php print $role->title ?></td>
            <td><?php print $role->created ?></td>
        </tr>
<?php endforeach; ?>        
    </tbody>
    <tfoot>
        <tr>
            <td><?php print __('Role','coders_passkey')  ?></td>
            <td><?php print __('Title','coders_passkey')  ?></td>
            <td><?php print __('Created','coders_passkey')  ?></td>
        </tr>
    </tfoot>
</table>
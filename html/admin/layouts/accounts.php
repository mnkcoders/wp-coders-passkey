<?php defined('ABSPATH') or die;?>
<h1 class="wp-heading-inline"><?php print get_admin_page_title() ?></h1>

<table class="wp-list-table widefat fixed striped table-view-excerpt roles">
    <thead>
        <tr>
            <th><?php print __('Account ID','coders_passkey')  ?></th>
        </tr>        
    </thead>
    <tbody>
<?php foreach( $this->list_accounts() as $account ) : ?>
        <tr>
            <td></td>
        </tr>
<?php endforeach; ?>        
    </tbody>
    <tfoot>
        <tr>
            <td></td>
        </tr>
    </tfoot>
</table>
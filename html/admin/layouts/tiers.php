<?php defined('ABSPATH') or die;?>
<h1 class="wp-heading-inline"><?php print get_admin_page_title() ?></h1>

<table class="wp-list-table widefat fixed striped table-view-excerpt roles">
    <thead>
        <tr>
            <th><?php print __('Tier','coders_passkey')  ?></th>
        </tr>        
    </thead>
    <tbody>
<?php foreach( $this->list_tiers() as $tier ) : ?>
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
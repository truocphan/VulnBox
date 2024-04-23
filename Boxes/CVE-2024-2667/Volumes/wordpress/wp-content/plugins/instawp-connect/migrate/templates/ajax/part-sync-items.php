<?php if ( ! empty( $events ) ) : ?>
    <?php foreach ( $events as $event ) : ?>
        <?php
            $label_class      = '';
            $label_attributes = '';
            $status           = $event->status;
            $datetime         = wp_date( 'M j, Y H:i A', strtotime( $event->date ) );

            if ( $status === 'completed' ) {
                $label_class      .= ' hint--top hint--low';
                $label_attributes .= sprintf(' aria-label="%s: %s"', __(  'Synced', 'instawp-connect' ), wp_date( 'M j, Y H:i A', strtotime( $event->synced_date ) ) );
            }
        ?>
        <tr>
            <td class="whitespace-nowrap py-3 px-3 text-sm font-medium text-grayCust-300 text-center">
                <input type="checkbox" name="event[]"  value="<?php echo $event->id; ?>" class="single-event-cb" />
            </td>
            <td class="whitespace-nowrap py-3 px-3 text-sm font-medium text-grayCust-300"><?php echo esc_html( $event->event_name ); ?></td>
            <td class="whitespace-nowrap px-3 py-3 font-medium text-sm text-grayCust-300 instawp-event-title-td"><?php echo esc_html( $event->title ); ?></td>
            <td class="whitespace-nowrap px-3 py-3 font-medium text-sm text-grayCust-300"><?php echo esc_html( $datetime ); ?></td>
            <td class="whitespace-nowrap px-3 py-3 text-center font-medium text-sm text-grayCust-300">
            <div class="flex flex-col items-center">
                <div class="bg-[#005e54] text-primary-900 text-sm font-medium mr-2 px-3 py-1 rounded-full synced_status <?php echo $status.' '.$label_class; ?>" <?php echo $label_attributes; ?>><?php echo esc_html( ucfirst($status) ); ?></div>
                <?php if ( isset( $event->log ) && $event->log != '' ) : ?>
                    <div class="hint--top hint--low" aria-label="<?php echo $event->log; ?>">
                        <svg class="w-4 h-4 mr-2" width="14" height="16" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20ZM11 7H13V9H11V7ZM11 11H13V17H11V11Z"></path></svg>
                    <div>
                <?php endif ?>
                <!-- <div class="py-1 px-2 inline-block rounded-full text-primary-900 font-medium synced_status <?php //echo $status.' '.$label_class; ?>" <?php //echo $label_attributes; ?>>
                    <?php //echo esc_html( ucfirst($status) ); ?>
                </div> -->
                    <?php // if( $event && $event->synced_message !='' && $status == 'error' ) { ?>
                        <!-- <div class="py-1 px-2 inline-block rounded-full text-primary-900 font-medium text-red-500"><?php //echo esc_html( $event->synced_message ); ?></div> -->
                    <?php //} ?>
                </div>
            </td>
            <!-- <td class="whitespace-nowrap cursor-pointer  text-center px-6 py-6 font-medium text-sm text-primary-900">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM12 20C16.4183 20 20 16.4183 20 12C20 7.58172 16.4183 4 12 4C7.58172 4 4 7.58172 4 12C4 16.4183 7.58172 20 12 20ZM11 7H13V9H11V7ZM11 11H13V17H11V11Z"></path></svg>
                <?php echo ($status != 'completed') ? '<button type="button" id="btn-sync-'.$event->id.'" data-id="'.$event->id.'" class="two-way-sync-btn btn-single-sync"><span>Sync changes </span></button><span class="sync-success"></span>' : '<p class="sync_completed">Synced</p>';  ?>
            </td> -->
        </tr>
    <?php endforeach; ?>
    <?php else : ?> 
        <tr>
            <td colspan="5" class="whitespace-nowrap py-6 px-6 text-sm font-medium text-grayCust-300 w-0.5 text-center">
                <?php echo esc_html('No events found!', 'instawp-connect') ?>
            </td>
        </tr> 
<?php endif?>
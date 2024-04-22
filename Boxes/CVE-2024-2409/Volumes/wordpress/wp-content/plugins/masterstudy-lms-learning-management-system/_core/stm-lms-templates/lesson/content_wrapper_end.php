<?php

/**
 * @var $lesson_type
 * @var $item_id
 */

if( empty($item_id) ) {
    $item_id = '';
}
STM_LMS_Templates::show_lms_template('lesson/files', compact('item_id'));

switch($lesson_type) {
	case ('video') : ?>
        </div>
        </div>
		</div>
		<?php break;
    case ('slide') : ?>
        </div>
        </div>
        </div>
        <?php break;
	default: ?>
        </div>
        </div>
        </div>
<?php }
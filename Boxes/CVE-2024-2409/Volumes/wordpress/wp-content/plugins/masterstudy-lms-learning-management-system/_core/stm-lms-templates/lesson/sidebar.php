<?php
stm_lms_register_script('lesson_sidebar');
STM_LMS_Templates::show_lms_template('lesson/comments', compact('post_id', 'item_id', 'is_previewed'));
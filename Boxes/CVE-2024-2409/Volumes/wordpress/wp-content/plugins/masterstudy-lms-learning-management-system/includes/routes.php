<?php

use MasterStudy\Lms\Routing\Router;

/** @var Router $router */

$router->middleware(
	apply_filters(
		'masterstudy_lms_routes_middleware',
		array(
			\MasterStudy\Lms\Routing\Middleware\Authentication::class,
			\MasterStudy\Lms\Routing\Middleware\Instructor::class,
			\MasterStudy\Lms\Routing\Middleware\PostGuard::class,
		)
	)
);

$router->get(
	'/healthcheck',
	\MasterStudy\Lms\Http\Controllers\HealthCheckController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\HealthCheck::class,
);

$router->get(
	'/course-builder/settings',
	\MasterStudy\Lms\Http\Controllers\CourseBuilder\GetSettingsController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\CourseBuilder\GetSettings::class,
);

$router->put(
	'/course-builder/custom-fields/{post_id}',
	\MasterStudy\Lms\Http\Controllers\CourseBuilder\UpdateCustomFieldsController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\CourseBuilder\UpdateCustomFields::class,
);

$router->get(
	'/courses/new',
	\MasterStudy\Lms\Http\Controllers\Course\AddNewController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Course\AddNew::class
);

$router->get(
	'/courses',
	\MasterStudy\Lms\Http\Controllers\Course\GetCoursesController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Course\GetCourses::class
);

$router->post(
	'/courses/create',
	\MasterStudy\Lms\Http\Controllers\Course\CreateController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Course\Create::class
);

$router->post(
	'/courses/category',
	\MasterStudy\Lms\Http\Controllers\Course\CreateCategoryController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Course\CreateCategory::class
);

$router->get(
	'/courses/{course_id}/edit',
	\MasterStudy\Lms\Http\Controllers\Course\EditController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Course\Edit::class
);

$router->get(
	'/courses/{course_id}/settings',
	\MasterStudy\Lms\Http\Controllers\Course\GetSettingsController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Course\GetSettings::class
);

$router->put(
	'/courses/{course_id}/settings',
	\MasterStudy\Lms\Http\Controllers\Course\UpdateSettingsController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Course\UpdateSettings::class
);

$router->get(
	'/courses/{course_id}/settings/faq',
	\MasterStudy\Lms\Http\Controllers\Course\GetFaqSettingsController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Course\GetFaqSettings::class
);

$router->put(
	'/courses/{course_id}/settings/faq',
	\MasterStudy\Lms\Http\Controllers\Course\UpdateFaqSettingsController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Course\UpdateFaqSettings::class
);

$router->put(
	'/courses/{course_id}/settings/certificate',
	\MasterStudy\Lms\Http\Controllers\Course\UpdateCertificateSettingsController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Course\UpdateCertificateSettings::class
);

$router->get(
	'/courses/{course_id}/settings/pricing',
	\MasterStudy\Lms\Http\Controllers\Course\GetPricingSettingsController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Course\GetPricingSettings::class
);

$router->put(
	'/courses/{course_id}/settings/pricing',
	\MasterStudy\Lms\Http\Controllers\Course\UpdatePricingSettingsController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Course\UpdatePricingSettings::class
);

$router->put(
	'/courses/{course_id}/settings/files',
	\MasterStudy\Lms\Http\Controllers\Course\UpdateFilesSettingsController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Course\UpdateFilesSettings::class
);

$router->put(
	'/courses/{course_id}/settings/access',
	\MasterStudy\Lms\Http\Controllers\Course\UpdateAccessSettingsController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Course\UpdateAccessSettings::class
);

$router->put(
	'/courses/{course_id}/status',
	\MasterStudy\Lms\Http\Controllers\Course\UpdateStatusController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Course\UpdateStatus::class
);

$router->get(
	'/courses/{course_id}/curriculum',
	\MasterStudy\Lms\Http\Controllers\Course\Curriculum\GetCurriculumController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Course\Curriculum\GetCurriculum::class
);

$router->post(
	'/courses/{course_id}/curriculum/section',
	\MasterStudy\Lms\Http\Controllers\Course\Curriculum\CreateSectionController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Course\Curriculum\CreateSection::class
);

$router->put(
	'/courses/{course_id}/curriculum/section',
	\MasterStudy\Lms\Http\Controllers\Course\Curriculum\UpdateSectionController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Course\Curriculum\UpdateSection::class
);

$router->delete(
	'/courses/{course_id}/curriculum/section/{section_id}',
	\MasterStudy\Lms\Http\Controllers\Course\Curriculum\DeleteSectionController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Course\Curriculum\DeleteSection::class
);

$router->post(
	'/courses/{course_id}/curriculum/material',
	\MasterStudy\Lms\Http\Controllers\Course\Curriculum\CreateMaterialController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Course\Curriculum\CreateMaterial::class
);

$router->put(
	'/courses/{course_id}/curriculum/material',
	\MasterStudy\Lms\Http\Controllers\Course\Curriculum\UpdateMaterialController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Course\Curriculum\UpdateMaterial::class
);

$router->delete(
	'/courses/{course_id}/curriculum/material/{material_id}',
	\MasterStudy\Lms\Http\Controllers\Course\Curriculum\DeleteMaterialController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Course\Curriculum\DeleteMaterial::class
);

$router->get(
	'/courses/{course_id}/curriculum/import',
	\MasterStudy\Lms\Http\Controllers\Course\Curriculum\ImportSearchController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Course\Curriculum\ImportSearch::class
);

$router->post(
	'/courses/{course_id}/curriculum/import',
	\MasterStudy\Lms\Http\Controllers\Course\Curriculum\ImportMaterialsController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Course\Curriculum\ImportMaterials::class
);

$router->get(
	'/courses/{course_id}/announcement',
	\MasterStudy\Lms\Http\Controllers\Course\GetAnnouncementController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Course\GetAnnouncement::class
);

$router->put(
	'/courses/{course_id}/announcement',
	\MasterStudy\Lms\Http\Controllers\Course\UpdateAnnouncementController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Course\UpdateAnnouncement::class
);

$router->post(
	'/lessons',
	\MasterStudy\Lms\Http\Controllers\Lesson\CreateController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Lesson\Create::class
);

$router->put(
	'/lessons/{lesson_id}',
	\MasterStudy\Lms\Http\Controllers\Lesson\UpdateController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Lesson\Update::class
);

$router->get(
	'/lessons/{lesson_id}',
	\MasterStudy\Lms\Http\Controllers\Lesson\GetController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Lesson\Get::class
);

$router->post(
	'/quizzes',
	\MasterStudy\Lms\Http\Controllers\Quiz\CreateController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Quiz\Create::class
);

$router->get(
	'/quizzes/{quiz_id}',
	\MasterStudy\Lms\Http\Controllers\Quiz\GetController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Quiz\Get::class
);

$router->put(
	'/quizzes/{quiz_id}',
	\MasterStudy\Lms\Http\Controllers\Quiz\UpdateController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Quiz\Update::class
);

$router->delete(
	'/quizzes/{quiz_id}',
	\MasterStudy\Lms\Http\Controllers\Quiz\DeleteController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Quiz\Delete::class
);

$router->put(
	'/quizzes/{quiz_id}/questions',
	\MasterStudy\Lms\Http\Controllers\Quiz\UpdateQuestionsController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Quiz\UpdateQuestions::class
);

$router->get(
	'/questions/categories',
	\MasterStudy\Lms\Http\Controllers\Question\GetCategoriesController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Question\GetCategories::class
);

$router->post(
	'/questions/category',
	\MasterStudy\Lms\Http\Controllers\Question\CreateCategoryController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Question\CreateCategory::class
);

$router->post(
	'/questions',
	\MasterStudy\Lms\Http\Controllers\Question\CreateController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Question\Create::class
);

$router->get(
	'/questions/{question_id}',
	\MasterStudy\Lms\Http\Controllers\Question\GetController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Question\Get::class
);

$router->put(
	'/questions/{question_id}',
	\MasterStudy\Lms\Http\Controllers\Question\UpdateController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Question\Update::class
);

$router->delete(
	'/questions/{question_id}',
	\MasterStudy\Lms\Http\Controllers\Question\DeleteController::class,
	\MasterStudy\Lms\Routing\Swagger\Routes\Question\Delete::class
);

$router->group(
	array(
		'middleware' => array(
			\MasterStudy\Lms\Routing\Middleware\Authentication::class,
			\MasterStudy\Lms\Routing\Middleware\PostGuard::class,
		),
	),
	function ( Router $router ) {
		$router->post(
			'/media',
			\MasterStudy\Lms\Http\Controllers\Media\UploadController::class,
			\MasterStudy\Lms\Routing\Swagger\Routes\Media\Upload::class
		);

		$router->delete(
			'/media/{media_id}',
			\MasterStudy\Lms\Http\Controllers\Media\DeleteController::class,
			\MasterStudy\Lms\Routing\Swagger\Routes\Media\Delete::class
		);
	}
);

$router->group(
	array(
		'middleware' => apply_filters(
			'masterstudy_lms_routes_middleware',
			array(
				\MasterStudy\Lms\Routing\Middleware\Authentication::class,
				\MasterStudy\Lms\Routing\Middleware\Instructor::class,
				\MasterStudy\Lms\Routing\Middleware\PostGuard::class,
				\MasterStudy\Lms\Routing\Middleware\CommentGuard::class,
			)
		),
		'prefix'     => '/comments',
	),
	function ( Router $router ) {
		$router->get(
			'/{post_id}',
			\MasterStudy\Lms\Http\Controllers\Comment\GetController::class,
			\MasterStudy\Lms\Routing\Swagger\Routes\Comment\Get::class,
		);

		$router->post(
			'/{post_id}',
			\MasterStudy\Lms\Http\Controllers\Comment\CreateController::class,
			\MasterStudy\Lms\Routing\Swagger\Routes\Comment\Create::class,
		);

		$router->post(
			'/{comment_id}/reply',
			\MasterStudy\Lms\Http\Controllers\Comment\ReplyController::class,
			\MasterStudy\Lms\Routing\Swagger\Routes\Comment\Reply::class,
		);

		$router->post(
			'/{comment_id}/approve',
			\MasterStudy\Lms\Http\Controllers\Comment\ApproveController::class,
			\MasterStudy\Lms\Routing\Swagger\Routes\Comment\Approve::class,
		);

		$router->post(
			'/{comment_id}/unapprove',
			\MasterStudy\Lms\Http\Controllers\Comment\UnapproveController::class,
			\MasterStudy\Lms\Routing\Swagger\Routes\Comment\Unapprove::class,
		);

		$router->post(
			'/{comment_id}/spam',
			\MasterStudy\Lms\Http\Controllers\Comment\SpamController::class,
			\MasterStudy\Lms\Routing\Swagger\Routes\Comment\Spam::class,
		);

		$router->post(
			'/{comment_id}/unspam',
			\MasterStudy\Lms\Http\Controllers\Comment\UnspamController::class,
			\MasterStudy\Lms\Routing\Swagger\Routes\Comment\Unspam::class,
		);

		$router->post(
			'/{comment_id}/trash',
			\MasterStudy\Lms\Http\Controllers\Comment\TrashController::class,
			\MasterStudy\Lms\Routing\Swagger\Routes\Comment\Trash::class,
		);

		$router->post(
			'/{comment_id}/untrash',
			\MasterStudy\Lms\Http\Controllers\Comment\UntrashController::class,
			\MasterStudy\Lms\Routing\Swagger\Routes\Comment\Untrash::class,
		);

		$router->post(
			'/{comment_id}/update',
			\MasterStudy\Lms\Http\Controllers\Comment\UpdateController::class,
			\MasterStudy\Lms\Routing\Swagger\Routes\Comment\Update::class,
		);
	}
);

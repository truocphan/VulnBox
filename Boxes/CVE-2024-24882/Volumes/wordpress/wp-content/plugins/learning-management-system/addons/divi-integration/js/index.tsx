import CourseCategoriesModule from './modules/CourseCategoriesModule';
import CourseListModule from './modules/CourseListModule';

jQuery(window).on('et_builder_api_ready', (_: any, API: any) => {
	API.registerModules([CourseListModule, CourseCategoriesModule]);
});

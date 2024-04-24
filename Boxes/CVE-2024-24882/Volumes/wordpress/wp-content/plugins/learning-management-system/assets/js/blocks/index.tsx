import { registerCourseCategoriesBlock } from './course-categories/block';
import { registerCoursesBlock } from './courses/block';
import { initCSSGenerators } from './helpers/initCSSGenerators';
import { registerDeviceTypeStore } from './helpers/registerDeviceTypeStore';
import { updateBlocksCategoryIcon } from './helpers/updateBlocksCategoryIcon';

updateBlocksCategoryIcon();
registerDeviceTypeStore();
registerCoursesBlock();
registerCourseCategoriesBlock();
initCSSGenerators();

import { select, subscribe } from '@wordpress/data';
import { saveFrontedCSS } from '../utils/frontedCss';
import { saveWidgetsCSS } from './saveWidgetsCSS';

let isSavingWidgetsCSS = false;

export const initCSSGenerators = () => {
	subscribe(() => {
		if (select('core/editor')) {
			const { isSavingPost, isAutosavingPost } = select('core/editor');

			if (isSavingPost() && !isAutosavingPost()) {
				saveFrontedCSS();
			}
		}

		if (
			select('core/edit-widgets')?.isSavingWidgetAreas() &&
			!isSavingWidgetsCSS
		) {
			isSavingWidgetsCSS = true;

			saveWidgetsCSS().finally(() => {
				isSavingWidgetsCSS = false;
			});
		}
	});

	if ('yes' === _MASTERIYO_BLOCKS_DATA_.isCustomizer) {
		wp.customize.bind('saved', saveWidgetsCSS);
	}
};

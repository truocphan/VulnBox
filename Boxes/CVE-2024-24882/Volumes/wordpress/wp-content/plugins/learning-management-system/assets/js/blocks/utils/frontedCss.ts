import apiFetch from '@wordpress/api-fetch';
import { select } from '@wordpress/data';
import { generateFrontEndCSS } from './generateFrontEndCSS';
import { hasMasteriyoBlocks } from './hasMasteriyoBlocks';

export function saveFrontedCSS() {
	const allBlocks: any = select('core/block-editor').getBlocks();
	const { getCurrentPostId } = select('core/editor');
	let css = '';

	if (hasMasteriyoBlocks(allBlocks)) {
		css = generateFrontEndCSS(allBlocks);
	}
	return apiFetch({
		path: '/masteriyo/v1/blocks/save_css',
		method: 'POST',
		data: { css, postId: getCurrentPostId() },
	});
}

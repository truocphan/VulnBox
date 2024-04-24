import { WPBlock } from '../types';
import { generateBlockCSS } from './generateBlockCSS';
import { minifyCSS } from './minifyCSS';

export function generateFrontEndCSS(blocks: WPBlock[]): string {
	let css = '';

	blocks.forEach((row) => {
		const { attributes, name } = row;
		const blockName = name.split('/');

		if (blockName[0] === 'masteriyo' && attributes.clientId) {
			css += generateBlockCSS(
				attributes,
				blockName[1],
				attributes.clientId,
				'save'
			);
		}

		if (row.innerBlocks && row.innerBlocks.length > 0) {
			css += generateFrontEndCSS(row.innerBlocks);
		}
	});

	return minifyCSS(css);
}

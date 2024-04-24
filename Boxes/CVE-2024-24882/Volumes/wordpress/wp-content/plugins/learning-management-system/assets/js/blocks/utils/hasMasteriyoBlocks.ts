import { WPBlock } from '../types';

export function hasMasteriyoBlocks(blocks: WPBlock[]): boolean {
	for (let i = 0; i < blocks.length; i += 1) {
		const block = blocks[i];

		if (block.name.indexOf('masteriyo/') !== -1) {
			return true;
		}
		if (block.innerBlocks && block.innerBlocks.length > 0) {
			if (hasMasteriyoBlocks(block.innerBlocks)) {
				return true;
			}
		}
	}
	return false;
}

import { getBlockType } from '@wordpress/blocks';
import { BlockAttributesDefinition } from '../types';

export function getBlockAttributesDef(
	blockName: string
): BlockAttributesDefinition | undefined {
	const def: any = getBlockType(blockName);

	return def?.attributes;
}

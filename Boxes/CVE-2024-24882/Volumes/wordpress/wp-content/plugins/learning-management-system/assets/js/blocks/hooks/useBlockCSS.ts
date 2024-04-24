import { useEffect } from '@wordpress/element';
import { BlockAttributesData } from '../types';
import { generateBlockCSS } from '../utils/generateBlockCSS';
import { updateInlineCSS } from '../utils/updateInlineCSS';

interface PropsType {
	clientId: string;
	deviceType: string;
	attributes: BlockAttributesData;
	blockName: string;
}

export const useBlockCSS = (props: PropsType) => {
	const { clientId, blockName, deviceType, attributes } = props;

	useEffect(() => {
		if (!clientId) {
			return;
		}
		updateInlineCSS(
			`masteriyo-block-css-${clientId}`,
			generateBlockCSS(attributes, blockName, clientId, 'editor')
		);
	}, [clientId, blockName, deviceType, attributes]);
};

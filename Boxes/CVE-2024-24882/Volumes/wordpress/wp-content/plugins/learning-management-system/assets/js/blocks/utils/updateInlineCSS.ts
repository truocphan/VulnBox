import { createStyleElement } from './createStyleElement';

export function updateInlineCSS(styleId: string, css: string) {
	let styleElement = document.getElementById(styleId);

	if (!styleElement) {
		styleElement = createStyleElement(styleId);

		document.head?.appendChild(styleElement);
	}
	styleElement.innerHTML = css;

	if ('yes' === _MASTERIYO_BLOCKS_DATA_.isCustomizer && window.frames?.length) {
		const frames = window.frames;

		for (let i = 0; i < frames.length; i++) {
			if (!frames[i].name) {
				continue;
			}
			const iframeDocument = frames[i].document;
			let styleElement2 = iframeDocument.getElementById(styleId);

			if (!styleElement2) {
				styleElement2 = createStyleElement(styleId);

				iframeDocument.head?.appendChild(styleElement2);
			}
			styleElement2.innerHTML = css;
		}
	}
}

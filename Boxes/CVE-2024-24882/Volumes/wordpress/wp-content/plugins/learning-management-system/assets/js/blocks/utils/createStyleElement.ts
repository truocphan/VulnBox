export function createStyleElement(
	id: string,
	content: string = ''
): HTMLStyleElement {
	const styleElement = document.createElement('style');

	styleElement.setAttribute('type', 'text/css');
	styleElement.setAttribute('id', id);
	styleElement.innerHTML = content;

	return styleElement;
}

// TODO Use a npm package like csso instead of manually handling the cleaning of generated CSS.
export function minifyCSS(css: string): string {
	return css
		.replace(/([^0-9a-zA-Z.#])\s+/g, '$1')
		.replace(/\s([^0-9a-zA-Z.#]+)/g, '$1')
		.replace(/;}/g, '}')
		.replace(/;;/g, ';')
		.replace(/\/\*.*?\*\//g, '');
}

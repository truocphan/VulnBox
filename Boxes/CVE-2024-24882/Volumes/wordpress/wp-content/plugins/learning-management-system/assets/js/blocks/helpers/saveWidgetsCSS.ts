import apiFetch from '@wordpress/api-fetch';
import { parse } from '@wordpress/blocks';
import { generateFrontEndCSS } from '../utils/generateFrontEndCSS';

export const saveWidgetsCSS = () => {
	return new Promise<void>((resolve, reject) => {
		apiFetch({
			path: 'wp/v2/widgets',
			method: 'GET',
		})
			.then((response: any) => {
				if (!response) {
					return resolve();
				}
				const content = response
					.map((datum: any) =>
						datum?.instance?.raw?.content ? datum.instance.raw.content : ''
					)
					.join('');
				const parsedContent: any[] = parse(content);
				const css = generateFrontEndCSS(parsedContent);

				apiFetch({
					path: 'masteriyo/v1/settings',
					method: 'POST',
					data: { general: { widgets_css: css } },
				}).finally(resolve);
			})
			.catch(reject);
	});
};

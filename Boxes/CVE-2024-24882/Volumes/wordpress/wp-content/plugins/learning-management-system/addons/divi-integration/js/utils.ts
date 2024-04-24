import { isArray, isEmpty } from '../../../assets/js/back-end/utils/utils';

export function processModuleStyleTemplates(moduleSlug: string, props: any) {
	const styleTemplates = window._MASTERIYO_STYLE_TEMPLATES_?.[moduleSlug] || [];
	const specialSettings = window._MASTERIYO_SPECIAL_SETTINGS_ || {};

	if (!isArray(styleTemplates) || !specialSettings) {
		return;
	}

	Object.entries(specialSettings).forEach(([settingName, type]) => {
		if (type === 'padding' || type === 'margin') {
			const value = props[settingName];

			if (value) {
				const splitValues = value.split('|');

				props[`${settingName}.TOP`] = splitValues[0];
				props[`${settingName}.RIGHT`] = splitValues[1];
				props[`${settingName}.BOTTOM`] = splitValues[2];
				props[`${settingName}.LEFT`] = splitValues[3];
			}
		}
	});

	const styles: any[] = [];

	styleTemplates?.forEach((style) => {
		const preparedStyle = {
			selector: style.selector,
			declaration: style.declaration,
		};

		if (
			!isEmpty(style.condition) &&
			style.condition.conditions &&
			isArray(style.condition.conditions)
		) {
			const conditions = style.condition.conditions;
			const relation = (style.condition.relation + '' || 'OR').toUpperCase();

			for (let i = 0; i < conditions.length; i++) {
				if (isEmpty(conditions[i])) {
					continue;
				}

				const condition = conditions[i];
				const settingName = condition.setting_name;
				const compare = condition.compare;
				const value = condition.value;
				let conditionMet = false;

				switch (compare) {
					case '__empty__':
						conditionMet = isEmpty(props[settingName]);
						break;

					case '__not_empty__':
						conditionMet = !isEmpty(props[settingName]);
						break;

					case '!=':
						conditionMet = props[settingName] != value;
						break;

					case '=':
					default:
						conditionMet = props[settingName] == value;
						break;
				}

				if (relation === 'AND') {
					if (!conditionMet) {
						return;
					}
				} else if (relation === 'OR') {
					if (conditionMet) {
						break;
					} else if (conditions.length - 1 === i) {
						return;
					}
				}
			}
		}

		if (style.dynamic) {
			if (isEmpty(style.declaration) || typeof style.declaration !== 'string') {
				return;
			}

			const matches = style.declaration?.match(/{{([A-Za-z\_\.]+)}}/g);

			matches?.forEach((match: string) => {
				const settingName = match.slice(2, -2);
				const value = isEmpty(props[settingName]) ? '' : props[settingName];

				preparedStyle.declaration = preparedStyle.declaration.replaceAll(
					match,
					value
				);
			});

			styles.push(preparedStyle);
		} else {
			styles.push(preparedStyle);
		}
	});

	return [styles];
}

import { DECLARATION_WITH_DIRECTION } from '../constants';
import { convertDirectionToString } from '../utils';

export const useBlockStyle = (blockName, properties = {}, device, accumulatedStyles = {} ) => {
	const deviceSuffix = device ? `-${device}` : '';
	const className = `wp-block-masterstudy-${blockName}`;
	const variables = [];
	const variablesObject = {};

	Object.entries(properties).forEach(([propertyName, propertyValue]) => {
		const isDirectionalProperty = DECLARATION_WITH_DIRECTION.includes(propertyName);
		const variableName = `--${className}${deviceSuffix}--${propertyName}`;
		const directionPattern = new RegExp( DECLARATION_WITH_DIRECTION.join('|'), 'i' );
		const valueToUse = isDirectionalProperty || directionPattern.test(propertyName) ? convertDirectionToString(propertyValue) : ( typeof propertyValue === 'string' ? propertyValue.trim() : propertyValue );

		if (valueToUse !== 'undefined' && propertyValue) {
			variables.push(`${variableName}:${valueToUse}`);
			variablesObject[variableName] = valueToUse;
			accumulatedStyles[variableName] = valueToUse;
		}

	});

	return {
		blockClassName: className,
		blockStyleVariables: variables.join(';'),
		blockStyleObject: variablesObject,
		accumulatedStyles: accumulatedStyles
	};
};

export const useBlockAdaptiveStyle = (blockName, device, properties) => {
  	return useBlockStyle(blockName, properties, device);
};


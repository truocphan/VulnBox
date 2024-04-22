import { DECLARATION_WITH_DIRECTION, DIRECTIONS } from '../constants';

export const convertDirectionToString = (direction) => {
	const values = DIRECTIONS.map(prop => direction[prop] ? direction[prop] : 'undefined');
	const uniqueValues = [...new Set(values)];

	return uniqueValues.length === 1
		? uniqueValues[0]
		: values.join(' ').replace(/undefined/g, '0px');
};

export const uniqueId = () => {
  	const timestamp  = new Date().getTime();
	const randString = Math.random().toString(36).substring(2, 15);
	return `${randString}-${timestamp}`;
}

export const convertCssValueToObject = ( cssProperty, cssValue ) => {
	const directionPattern = new RegExp( DECLARATION_WITH_DIRECTION.join('|'), 'i' );

	if (!directionPattern.test(cssProperty)) return cssValue;

	if ( typeof cssValue === 'object' && cssValue !== null ) {
		if ( DIRECTIONS.every( prop => prop in cssValue ) ) return cssValue;
	}

	if ( typeof cssValue === 'string' || typeof cssValue === 'number' ) {
		const values = cssValue.toString().split(/\s+/);
		const convertedObject = {};
		DIRECTIONS.forEach((direction, index) => {
			let value = values.length === 1 ? values[0] : '';
				value = values.length === 2 ? values[index % 2 ? 1: 0] : value;
				value = values.length === 3 ? values[index < 3 ? index : 1] : value;
				value = values.length === 4 ? values[index] : value;
				convertedObject[direction] = value;
		});
		return convertedObject;
	}
  	return ''; 
}
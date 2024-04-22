
import { convertCssValueToObject } from './../utils';

export const setDeviceAttribute = ( device, attributes, setAttributes, properties ) => {
	const { type, cssProperty, value } = properties;

	const deviceAttributes = { [device]: { ...attributes[device] } };

	if (type === 'string') {
		if (!value) {
			delete deviceAttributes[device][cssProperty];
		} else {
			deviceAttributes[device] = {
				...deviceAttributes[device],
				[cssProperty]: value,
			};
		}
		setAttributes(deviceAttributes);
	} else {
		let newValues = {
			[cssProperty]: value,
		};

		if (cssProperty in attributes[device]) {
			newValues = {
				[cssProperty]: {
					...attributes[device][cssProperty],
					...newValues[cssProperty],
				},
			};
		}

		for (const direction in newValues[cssProperty]) {
			if (!newValues[cssProperty][direction]) {
				delete newValues[cssProperty][direction];
			}
		}

		if (Object.keys(newValues).length < 1) {
			newValues[cssProperty] = undefined;
		}

		deviceAttributes[device] = {
			...deviceAttributes[device],
			...newValues,
		};

		setAttributes(deviceAttributes);
	}
}

export const getDeviceAttribute = ( device, cssProperty, attributes, defaultVal ) => {
	const subtitute = typeof defaultVal !== 'undefined' ? convertCssValueToObject(cssProperty, defaultVal) : '';
	return attributes[device][cssProperty] || subtitute;
} 

export const getDeviceAttributes = ( device, attributes, properties ) => {
	const output = {};
	properties.forEach( property => {
		if ( property.cssProperty ) {
			output[property.cssProperty] = getDeviceAttribute( device, property.cssProperty, attributes, property.default );
		}
	});
	return output;
} 
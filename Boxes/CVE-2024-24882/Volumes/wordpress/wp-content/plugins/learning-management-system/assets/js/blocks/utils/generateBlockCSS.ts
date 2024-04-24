import {
	BlockAttributeDefinition,
	BlockAttributesData,
	BlockAttributeValue,
	BlockSettingStyle,
	StringToAnyObject,
} from '../types';
import { getBlockAttributesDef } from './getBlockAttributesDefinition';
import { getDeviceType } from './getDeviceType';
import { replacePlaceholders } from './replacePlaceholders';

interface CSSForDevices {
	allDevice: string[];
	desktop: string[];
	tablet: string[];
	mobile: string[];
}

// eslint-disable-next-line no-unused-vars
type SettingStyleGenerator = (props: {
	blockID: string;
	settingName: string;
	settingValue: any;
	settings: BlockAttributesData;
	settingDef: BlockAttributeDefinition;
}) => CSSForDevices;

const settingStyleGenerators: {
	[name: string]: SettingStyleGenerator;
} = {
	empty: () => ({ allDevice: [], desktop: [], tablet: [], mobile: [] }),
	border: (props) => {
		const { settingValue, settingDef } = props;
		const stylesDef = settingDef.style;
		const css: CSSForDevices = {
			allDevice: [],
			desktop: [],
			tablet: [],
			mobile: [],
		};

		if (!stylesDef) {
			return css;
		}

		stylesDef.forEach((styleDef) => {
			if (!meetsConditions(props.settings, styleDef)) {
				return;
			}
			let selector = replacePlaceholders(styleDef.selector, {
				WRAPPER: '.masteriyo-block-' + props.blockID,
			});

			if (settingValue.color) {
				css.allDevice.push(
					`${selector} { border-color:  ${settingValue.color}; }`
				);
			}
			if (settingValue.type) {
				css.allDevice.push(
					`${selector} { border-style: ${settingValue.type}; }`
				);
			}

			if (settingValue.size) {
				['desktop', 'tablet', 'mobile'].forEach((deviceType) => {
					if (!settingValue.size[deviceType]) {
						return;
					}
					const unit = settingValue.size[deviceType].unit || 'px';
					const top = settingValue.size[deviceType].top || 0;
					const right = settingValue.size[deviceType].right || 0;
					const bottom = settingValue.size[deviceType].bottom || 0;
					const left = settingValue.size[deviceType].left || 0;

					css[deviceType].push(
						`${selector} { border-width: ${top}${unit} ${right}${unit} ${bottom}${unit} ${left}${unit}; }`
					);
				});
			}
			if (settingValue.radius) {
				['desktop', 'tablet', 'mobile'].forEach((deviceType) => {
					if (settingValue.radius && settingValue.radius[deviceType]) {
						const unit = settingValue.radius[deviceType].unit || 'px';
						const top = settingValue.radius[deviceType].top || 0;
						const right = settingValue.radius[deviceType].right || 0;
						const bottom = settingValue.radius[deviceType].bottom || 0;
						const left = settingValue.radius[deviceType].left || 0;

						css[deviceType].push(
							`${selector} { border-radius: ${top}${unit} ${right}${unit} ${bottom}${unit} ${left}${unit}; }`
						);
					}
				});
			}
		});

		return css;
	},
	general: (props) => {
		const { settingValue, settingDef } = props;
		const stylesDef = settingDef.style;
		const css: CSSForDevices = {
			allDevice: [],
			desktop: [],
			tablet: [],
			mobile: [],
		};

		if (!stylesDef) {
			return css;
		}

		stylesDef.forEach((styleDef) => {
			if (!meetsConditions(props.settings, styleDef)) {
				return;
			}
			let selector = replacePlaceholders(styleDef.selector, {
				WRAPPER: '.masteriyo-block-' + props.blockID,
			});

			if (typeof settingValue === 'object' && !Array.isArray(settingValue)) {
				if (
					settingValue.desktop ||
					settingValue.tablet ||
					settingValue.mobile
				) {
					['desktop', 'tablet', 'mobile'].forEach((deviceType) => {
						css[deviceType].push(
							replacePlaceholders(selector, {
								VALUE: settingValue[deviceType],
							})
						);
					});
				}
			} else {
				css.allDevice.push(
					replacePlaceholders(selector, {
						VALUE: settingValue,
					})
				);
			}
		});

		return css;
	},
};

function getSettingStyleGenerator(
	settingName: string,
	settingValue: BlockAttributeValue
): SettingStyleGenerator | undefined {
	if (typeof settingValue === 'object') {
		if (!Array.isArray(settingValue)) {
			if (settingValue.border) {
				return settingStyleGenerators.border;
			}
		}
		return settingStyleGenerators.general;
	}
	if (
		[
			'hideOnDesktop',
			'hideOnTablet',
			'hideOnMobile',
			'colReverseOnTablet',
			'colReverseOnMobile',
		].includes(settingName)
	) {
		return settingStyleGenerators.empty;
	}
	return settingStyleGenerators.general;
}

export const generateBlockCSS = (
	settings: BlockAttributesData,
	blockName: string,
	blockID: string,
	context: 'editor' | 'save' = 'editor'
): string => {
	if (!blockID) return '';

	const cssForDevices: CSSForDevices = {
		allDevice: [],
		desktop: [],
		tablet: [],
		mobile: [],
	};
	const attributesDef = getBlockAttributesDef('masteriyo/' + blockName);

	if (!attributesDef) return '';

	Object.entries(settings).forEach(([settingName, settingValue]) => {
		const attributeDef = attributesDef[settingName];
		const settingStylesDef = attributeDef?.style;

		if (!settingStylesDef) return;

		const settingStyleGenerator = getSettingStyleGenerator(
			settingName,
			settingValue
		);

		if (!settingStyleGenerator) return;

		const settingCssForDevices = settingStyleGenerator({
			blockID,
			settingDef: attributeDef,
			settingName,
			settingValue,
			settings,
		});

		cssForDevices.allDevice = cssForDevices.allDevice.concat(
			settingCssForDevices.allDevice
		);
		cssForDevices.desktop = cssForDevices.desktop.concat(
			settingCssForDevices.desktop
		);
		cssForDevices.tablet = cssForDevices.tablet.concat(
			settingCssForDevices.tablet
		);
		cssForDevices.mobile = cssForDevices.mobile.concat(
			settingCssForDevices.mobile
		);
	});
	let css = cssForDevices.allDevice.join('');

	if (context === 'editor') {
		if (getDeviceType() === 'desktop') {
			css += cssForDevices.desktop.join('');
		}
		if (getDeviceType() === 'tablet') {
			css += cssForDevices.tablet.join('');
		}
		if (getDeviceType() === 'mobile') {
			css += cssForDevices.mobile.join('');
		}
	} else if (context === 'save') {
		css += cssForDevices.desktop.join('');
		css += '@media (max-width: 780px) {' + cssForDevices.tablet.join('') + '}';
		css += '@media (max-width: 360px) {' + cssForDevices.mobile.join('') + '}';
	}
	return css;
};

const meetsConditions = (
	settings: StringToAnyObject,
	selectData: BlockSettingStyle[0]
): boolean => {
	let depends = true;

	if (selectData.condition) {
		selectData.condition.forEach((data) => {
			const previous = depends;

			if (data.relation === '==' || data.relation === '===') {
				if (
					typeof data.value === 'string' ||
					typeof data.value === 'number' ||
					typeof data.value === 'boolean'
				) {
					depends = settings[data.key] === data.value;
				} else {
					if (data.value?.includes(settings[data.key])) {
						depends = true;
					} else {
						depends = false;
					}
				}
			} else if (data.relation === '!=' || data.relation === '!==') {
				if (
					typeof data.value === 'string' ||
					typeof data.value === 'number' ||
					typeof data.value === 'boolean'
				) {
					depends = settings[data.key] !== data.value;
				} else {
					let select = false;

					data.value.forEach((arrData) => {
						if (settings[data.key] !== arrData) {
							select = true;
						}
					});

					if (select) {
						depends = true;
					}
				}
			}
			if (previous === false) {
				depends = false;
			}
		});
	}

	return depends;
};

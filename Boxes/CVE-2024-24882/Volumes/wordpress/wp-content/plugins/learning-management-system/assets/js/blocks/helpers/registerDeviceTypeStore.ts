import { dispatch, registerStore } from '@wordpress/data';
import { ucFirst } from '../utils/ucFirst';

export function registerDeviceTypeStore() {
	const INITIAL_STATE = {
		deviceType: 'desktop',
	};

	const ACTIONS = {
		setPreviewDeviceType: (deviceType: string) => {
			const { __experimentalSetPreviewDeviceType: setPreviewDeviceType } =
				dispatch('core/edit-post') || false;

			if (setPreviewDeviceType) {
				setPreviewDeviceType(ucFirst(deviceType));
			}
			if ('yes' === _MASTERIYO_BLOCKS_DATA_.isCustomizer) {
				wp.customize?.previewedDevice(deviceType.toLowerCase());
			}

			return {
				type: 'SET_PREVIEW_DEVICE_TYPE',
				payload: deviceType,
			};
		},
	};

	const SELECTORS = {
		getPreviewDeviceType: (state: any) => {
			const core = wp.data?.select('core/edit-post');

			if (core && core.__experimentalGetPreviewDeviceType) {
				return core.__experimentalGetPreviewDeviceType().toLowerCase();
			}
			return state.deviceType;
		},
	};

	const REDUCER = (prevState = INITIAL_STATE, action: any) => {
		switch (action.type) {
			case 'SET_PREVIEW_DEVICE_TYPE': {
				return {
					...prevState,
					deviceType: action.payload,
				};
			}
			default:
				return prevState;
		}
	};

	registerStore('masteriyo/device-type', {
		reducer: REDUCER,
		actions: ACTIONS,
		selectors: SELECTORS,
	});
}

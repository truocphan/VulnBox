import { dispatch, useSelect } from '@wordpress/data';
import { useCallback } from '@wordpress/element';

export const useDeviceType = (): [string, CallableFunction] => {
	const deviceType: string = useSelect(
		(select) => select('masteriyo/device-type').getPreviewDeviceType(),
		[]
	);
	const setDeviceType = useCallback((state) => {
		dispatch('masteriyo/device-type').setPreviewDeviceType(state);
	}, []);

	return [deviceType, setDeviceType];
};

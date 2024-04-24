import { dispatch } from '@wordpress/data';

export const setDeviceType = (deviceType: string) => {
	const core: any = dispatch('core/edit-post');

	if (core?.__experimentalSetPreviewDeviceType) {
		core.__experimentalSetPreviewDeviceType(deviceType);
	}
};

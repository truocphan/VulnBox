import { select } from '@wordpress/data';

export const getDeviceType = (): 'desktop' | 'tablet' | 'mobile' => {
	const core: any = select('core/edit-post');

	if (core?.__experimentalGetPreviewDeviceType) {
		return core.__experimentalGetPreviewDeviceType().toLowerCase();
	}
	return 'desktop';
};

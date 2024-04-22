import { __ } from '@wordpress/i18n';
import {
    __experimentalToggleGroupControl as ToggleGroupControl,
	__experimentalToggleGroupControlOption as ToggleGroupControlOption,
} from '@wordpress/components';

const DevicesTabControl = ( { device, setDevice } ) => { 
	return (
        <ToggleGroupControl
            label={ __( 'Select device', 'masterstudy-lms-learning-management-system' ) }
            onChange={(device) => setDevice(device)}
            size="default"
            value={device || 'desktop'}
        >
            <ToggleGroupControlOption
                label={ __( 'Mobile', 'masterstudy-lms-learning-management-system' ) }
                value="mobile"
            />
            <ToggleGroupControlOption
                label={ __( 'Tablet', 'masterstudy-lms-learning-management-system' ) }
                value="tablet"
            />
            <ToggleGroupControlOption
                label={ __( 'Desktop', 'masterstudy-lms-learning-management-system' )}
                value="desktop"
            />
        </ToggleGroupControl>
    );
};
export default DevicesTabControl;
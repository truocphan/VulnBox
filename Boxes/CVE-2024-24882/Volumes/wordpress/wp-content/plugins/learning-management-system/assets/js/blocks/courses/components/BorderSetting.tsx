import { __ } from '@wordpress/i18n';
import React from 'react';
import { Dimensions } from '../../components';

const BorderSetting: React.FC<{
	value: any;
	// eslint-disable-next-line no-unused-vars
	onChange: (value: any) => void;
}> = (props) => {
	const {
		value: { radius },
		onChange,
	} = props;

	const setSetting = (genre: any, val: any) => {
		const data = { [genre]: val };
		onChange(Object.assign({}, props.value, data));
	};

	return (
		<div className="masteriyo-control masteriyo-border">
			<div className="masteriyo-control-body masteriyo-border-body">
				<Dimensions
					label={__('Radius', 'masteriyo')}
					value={radius || {}}
					responsive
					units={['px', 'em', '%']}
					defaultUnit="px"
					min={0}
					max={100}
					onChange={(val: any) => setSetting('radius', val)}
					dimensionLabels={{
						top: __('Top Left', 'masteriyo'),
						right: __('Top Right', 'masteriyo'),
						bottom: __('Bottom Right', 'masteriyo'),
						left: __('Bottom Left', 'masteriyo'),
					}}
				/>
			</div>
		</div>
	);
};

export default BorderSetting;

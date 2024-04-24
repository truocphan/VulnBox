import { Tooltip } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import classnames from 'classnames';
import React from 'react';
import { useDeviceType } from '../../hooks/useDeviceType';
import Icon from '../icon';
import './editor.scss';

interface PropsType {}

const DeviceSelector: React.FC<PropsType> = () => {
	const [deviceType, setDeviceType] = useDeviceType();

	return (
		<div className="masteriyo-device-selector">
			<div className="masteriyo-devices" role="group">
				<Tooltip text={__('Desktop', 'masteriyo')}>
					<button
						className={classnames('masteriyo-device', {
							active: 'desktop' === deviceType,
						})}
						onClick={() => {
							setDeviceType('desktop');
						}}
					>
						<Icon type="controlIcon" name="desktop" size={20} />
					</button>
				</Tooltip>
				<Tooltip text={__('Tablet', 'masteriyo')}>
					<button
						className={classnames('masteriyo-device', {
							active: 'tablet' === deviceType,
						})}
						onClick={() => {
							setDeviceType('tablet');
						}}
					>
						<Icon type="controlIcon" name="tablet" size={20} />
					</button>
				</Tooltip>
				<Tooltip text={__('Mobile', 'masteriyo')}>
					<button
						className={classnames('masteriyo-device', {
							active: 'mobile' === deviceType,
						})}
						onClick={() => {
							setDeviceType('mobile');
						}}
					>
						<Icon type="controlIcon" name="mobile" size={20} />
					</button>
				</Tooltip>
			</div>
		</div>
	);
};

export default DeviceSelector;

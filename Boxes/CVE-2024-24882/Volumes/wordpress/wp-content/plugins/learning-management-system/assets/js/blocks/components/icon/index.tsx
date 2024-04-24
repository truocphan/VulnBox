import { Icon as WPIcon } from '@wordpress/components';
import classnames from 'classnames';
import React from 'react';
import icons from './icons';

interface PropsType {
	type: string;
	name: string;
	className?: string;
	size?: number;
	[key: string]: any;
}

const Icon: React.FC<PropsType> = (props) => {
	const { type, name, className, size = 24, ...otherProps } = props;
	const iconsNames = [];

	// TODO flat the object by using icon name with syntax [namespace.name]
	for (const typeKey of Object.keys(icons)) {
		for (const nameKey of Object.keys(icons[typeKey])) {
			iconsNames.push(nameKey);
		}
	}

	if (
		!['controlIcon', 'blockIcon', 'frontendIcon'].includes(type) ||
		!iconsNames.includes(name)
	) {
		return null;
	}

	return (
		<WPIcon
			className={classnames('masteriyo-icon', className)}
			icon={icons[type][name] || ''}
			size={size}
			type={type}
			name={name}
			{...otherProps}
		/>
	);
};

export default Icon;

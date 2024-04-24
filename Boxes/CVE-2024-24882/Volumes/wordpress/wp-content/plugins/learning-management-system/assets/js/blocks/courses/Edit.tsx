import { Fragment } from '@wordpress/element';
import React from 'react';
import { useBlockCSS } from '../hooks/useBlockCSS';
import useClientId from '../hooks/useClientId';
import { useDeviceType } from '../hooks/useDeviceType';
import BlockSettings from './components/BlockSettings';

const Edit: React.FC<any> = (props) => {
	const {
		attributes: { clientId },
		setAttributes,
	} = props;
	const ServerSideRender = wp.serverSideRender
		? wp.serverSideRender
		: wp.components.ServerSideRender;
	const [deviceType] = useDeviceType();

	useClientId(props.clientId, setAttributes, props.attributes);
	useBlockCSS({
		blockName: 'courses',
		clientId,
		attributes: props.attributes,
		deviceType,
	});

	return (
		<Fragment>
			<BlockSettings {...props} />
			<div
				className="masteriyo-block-editor-wrapper"
				onClick={(e) => e.preventDefault()}
			>
				<ServerSideRender
					block="masteriyo/courses"
					attributes={{
						clientId: clientId ? clientId : '',
						count: props.attributes.count,
						columns: props.attributes.columns,
						categoryIds: props.attributes.categoryIds,
					}}
				/>
			</div>
		</Fragment>
	);
};

export default Edit;

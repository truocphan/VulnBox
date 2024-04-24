import { Image } from '@chakra-ui/react';
import { InspectorControls } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { CourseCategoriesBlockGridDesign } from '../../../back-end/constants/images';
import { Panel, Slider, Tab, TabPanel } from '../../components';
import Toggle from '../../components/toggle';

const BlockSettings = (props: any) => {
	const {
		attributes: { count, columns, hide_courses_count, include_sub_categories },
		setAttributes,
	} = props;

	return (
		<InspectorControls>
			<TabPanel>
				<Tab tabTitle={__('Design', 'masteriyo')}>
					<div className="masteriyo-design-card">
						<div className="masteriyo-design-card__items masteriyo-design-card__items--active">
							<div className="preview-image">
								<Image
									src={CourseCategoriesBlockGridDesign}
									alt="Grid Design"
								/>
							</div>
							<div className="status">
								<span className="title">{__('Grid', 'masteriyo')}</span>
								<span className="active-label">
									{__('Active', 'masteriyo')}
								</span>
							</div>
						</div>
					</div>
					<div className="coming-soon-notice">
						<span>{__('New Design', 'masteriyo')}</span>
						<span>{__('Coming Soon', 'masteriyo')}</span>
					</div>
				</Tab>
				<Tab tabTitle={__('Settings', 'masteriyo')}>
					<Panel title={__('General', 'masteriyo')} initialOpen>
						<Slider
							onChange={(val: number) =>
								setAttributes({ count: val ? val : 1 })
							}
							label={__('No. of Categories', 'masteriyo')}
							min={1}
							step={1}
							value={count}
						/>
						<Toggle
							checked={hide_courses_count === 'yes'}
							onChange={(val: boolean) =>
								setAttributes({ hide_courses_count: val ? 'yes' : 'no' })
							}
							label={__('Hide courses count', 'masteriyo')}
						/>
						<Toggle
							checked={include_sub_categories}
							onChange={(val: boolean) =>
								setAttributes({ include_sub_categories: val })
							}
							label={__('Include sub-categories', 'masteriyo')}
						/>
					</Panel>
					<Panel title={__('Layout', 'masteriyo')}>
						<Slider
							onChange={(val: number) =>
								setAttributes({ columns: val ? val : 1 })
							}
							label={__('Columns', 'masteriyo')}
							min={1}
							max={4}
							step={1}
							value={columns}
						/>
					</Panel>
				</Tab>
			</TabPanel>
		</InspectorControls>
	);
};

export default BlockSettings;

import { Image } from '@chakra-ui/react';
import { InspectorControls } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import classNames from 'classnames';
import React from 'react';
import { CoursesBlockGridDesign } from '../../../back-end/constants/images';
import { Panel, Select, Slider, Tab, TabPanel } from '../../components';
import BorderSetting from './BorderSetting';

const categoryOptions = _MASTERIYO_BLOCKS_DATA_.categories.map((category) => ({
	label: category.name,
	value: category.slug,
}));

const BlockSettings = (props: any) => {
	const {
		attributes: {
			count,
			columns,
			startCourseButtonBorder,
			categoryIds,
			clientId,
		},
		setAttributes,
	} = props;

	return (
		<InspectorControls>
			<TabPanel>
				<Tab tabTitle={__('Design', 'masteriyo')}>
					<div className="masteriyo-design-card">
						<div className="masteriyo-design-card__items masteriyo-design-card__items--active">
							<div className="preview-image">
								<Image src={CoursesBlockGridDesign} alt="Grid Design" />
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
							label={__('No. of Courses', 'masteriyo')}
							min={1}
							step={1}
							value={count}
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
					<Panel title={__('Filter', 'masteriyo')}>
						<div
							className={classNames(
								'masteriyo-control',
								'masteriyo-select'
								// { 'masteriyo-inline': inline && !responsive }
							)}
						>
							<div className="masteriyo-control-head masteriyo-select-head">
								<label
									htmlFor={`masteriyo-select-button-${clientId}`}
									className="masteriyo-control-label masteriyo-select-label"
								>
									{__('Categories', 'masteriyo')}
								</label>
							</div>
							<div className="masteriyo-control-body masteriyo-select-body">
								<Select
									className="masteriyo-multi-select"
									id={`masteriyo-select-button-${clientId}`}
									value={categoryOptions.filter((cate) =>
										categoryIds.includes(cate.value)
									)}
									closeMenuOnSelect={false}
									isMulti
									options={categoryOptions}
									onChange={(val: any) =>
										setAttributes({
											categoryIds: val
												? val.map((item: any) => item.value)
												: [],
										})
									}
								/>
							</div>
						</div>
					</Panel>
					<Panel title={__('Button', 'masteriyo')}>
						<BorderSetting
							value={startCourseButtonBorder}
							onChange={(val) =>
								setAttributes({ startCourseButtonBorder: val })
							}
						/>
					</Panel>
				</Tab>
			</TabPanel>
		</InspectorControls>
	);
};

export default BlockSettings;

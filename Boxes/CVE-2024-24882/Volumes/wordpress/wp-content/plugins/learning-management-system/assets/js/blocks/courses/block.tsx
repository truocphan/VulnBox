import { __ } from '@wordpress/i18n';
import React from 'react';
import { Icon } from '../components';
import attributes from './attributes';
import Edit from './Edit';
import './editor.scss';

export function registerCoursesBlock() {
	wp.blocks.registerBlockType('masteriyo/courses', {
		title: 'Courses',
		description: __('Display a collection of courses.', 'masteriyo'),
		icon: <Icon type="blockIcon" name="courses" size={24} />,
		category: 'masteriyo',
		keywords: ['Courses Block'],
		attributes,
		supports: {
			align: false,
			html: false,
			color: {
				background: false,
				gradient: false,
				text: false,
			},
		},
		edit: Edit,
		save: () => null,
	});
}

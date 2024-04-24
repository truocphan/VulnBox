import { BlockAttributesDefinition } from '../types';

const attributes: BlockAttributesDefinition = {
	clientId: {
		type: String,
	},
	count: {
		type: Number,
		default: 12,
	},
	columns: {
		type: Number,
		default: 3,
	},
	categoryIds: {
		type: Array,
		default: [],
	},
	sortBy: {
		type: String,
		default: 'date',
	},
	sortOrder: {
		type: String,
		default: 'desc',
	},
	startCourseButtonBorder: {
		type: Object,
		default: {
			border: 1,
			radius: {
				desktop: {
					top: 50,
					right: 50,
					bottom: 50,
					left: 50,
					unit: 'px',
				},
				tablet: {
					top: 50,
					right: 50,
					bottom: 50,
					left: 50,
					unit: 'px',
				},
				mobile: {
					top: 50,
					right: 50,
					bottom: 50,
					left: 50,
					unit: 'px',
				},
			},
		},
		style: [
			{
				selector:
					'{{WRAPPER}} .masteriyo-btn-primary.masteriyo-btn-primary.masteriyo-btn-primary',
			},
		],
	},
};
export default attributes;

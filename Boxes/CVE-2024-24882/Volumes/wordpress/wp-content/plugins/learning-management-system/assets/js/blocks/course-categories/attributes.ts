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
	hide_courses_count: {
		type: String,
		default: 'no',
	},
	include_sub_categories: {
		type: Boolean,
		default: false,
	},
};
export default attributes;

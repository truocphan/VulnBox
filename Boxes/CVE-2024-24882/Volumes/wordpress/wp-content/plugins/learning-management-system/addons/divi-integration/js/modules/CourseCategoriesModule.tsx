import React, { Component } from 'react';
import { processModuleStyleTemplates } from '../utils';

class CourseCategoriesModule extends Component<any, { content: string }> {
	static slug = 'masteriyo_course_categories';

	static css(props: any) {
		return processModuleStyleTemplates(this.slug, props);
	}

	render() {
		if (this.props.__rendered_course_categories) {
			return (
				<div
					dangerouslySetInnerHTML={{
						__html: this.props.__rendered_course_categories,
					}}
				></div>
			);
		}
		return null;
	}
}

export default CourseCategoriesModule;

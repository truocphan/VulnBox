import React, { Component } from 'react';
import { processModuleStyleTemplates } from '../utils';

class CourseListModule extends Component<any, { content: string }> {
	static slug = 'masteriyo_course_list';

	static css(props: any) {
		return processModuleStyleTemplates(this.slug, props);
	}

	render() {
		if (this.props.__rendered_course_list) {
			return (
				<div
					dangerouslySetInnerHTML={{
						__html: this.props.__rendered_course_list,
					}}
				></div>
			);
		}
		return null;
	}
}

export default CourseListModule;

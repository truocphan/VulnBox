import { FormControl, FormLabel } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import Editor from '../../../../../../../assets/js/back-end/components/common/Editor';

interface Props {
	defaultValue?: string;
}

const Description: React.FC<Props> = (props) => {
	const { defaultValue } = props;

	return (
		<FormControl>
			<FormLabel>{__('Description', 'masteriyo')}</FormLabel>
			<Editor
				id="mto-announcement-description"
				name="description"
				defaultValue={defaultValue}
			/>
		</FormControl>
	);
};

export default Description;

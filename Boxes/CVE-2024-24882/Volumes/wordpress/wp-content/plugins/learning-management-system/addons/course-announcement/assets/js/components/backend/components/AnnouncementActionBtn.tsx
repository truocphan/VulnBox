import { Button, useBreakpointValue } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { UseFormReturn } from 'react-hook-form';
import { deepMerge } from '../../../../../../../assets/js/back-end/utils/utils';

interface Props {
	methods: UseFormReturn<any>;
	isLoading: boolean;
	onSubmit: (arg1: any, arg2?: 'publish' | 'draft') => void;
	announcementStatus?: string;
}

const AnnouncementActionBtn: React.FC<Props> = (props) => {
	const { methods, isLoading, onSubmit, announcementStatus } = props;
	const buttonSize = useBreakpointValue(['sm', 'md']);

	const isAnnouncementPublished = () => {
		if (announcementStatus && announcementStatus === 'publish') {
			return true;
		} else {
			return false;
		}
	};

	const isAnnouncementDrafted = () => {
		if (announcementStatus && announcementStatus === 'draft') {
			return true;
		} else {
			return false;
		}
	};

	return (
		<>
			<Button
				size={buttonSize}
				colorScheme="primary"
				isLoading={isLoading}
				onClick={methods.handleSubmit((data: any) => {
					onSubmit(deepMerge({ status: 'publish' }, data));
				})}
			>
				{isAnnouncementPublished()
					? __('Update', 'masteriyo')
					: __('Publish', 'masteriyo')}
			</Button>
			<Button
				variant="outline"
				colorScheme="primary"
				isLoading={isLoading}
				onClick={methods.handleSubmit((data: any) => {
					onSubmit(deepMerge({ status: 'draft' }, data));
				})}
			>
				{isAnnouncementDrafted()
					? __('Save To Draft', 'masteriyo')
					: isAnnouncementPublished()
					? __('Switch To Draft', 'masteriyo')
					: __('Save To Draft', 'masteriyo')}
			</Button>
		</>
	);
};

export default AnnouncementActionBtn;

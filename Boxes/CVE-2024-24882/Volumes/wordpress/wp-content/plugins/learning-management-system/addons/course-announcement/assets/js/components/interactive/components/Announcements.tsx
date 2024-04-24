import {
	Alert,
	AlertIcon,
	Badge,
	Icon,
	Popover,
	PopoverArrow,
	PopoverBody,
	PopoverContent,
	PopoverHeader,
	PopoverTrigger,
	Progress,
	Stack,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useState } from 'react';
import { BsMegaphone } from 'react-icons/bs';
import { useQuery } from 'react-query';
import { isEmpty } from '../../../../../../../assets/js/back-end/utils/utils';
import API from './../../../../../../../assets/js/back-end/utils/api';
import { urls } from './../../backend/constants/urls';
import { AnnouncementSchema } from './../../backend/types/announcement';
import Message from './Message';

interface Props {
	courseId: number;
}

const Announcements: React.FC<Props> = (props) => {
	const { courseId } = props;
	const announcementAPI = new API(urls.courseAnnouncement);
	const [readCount, setReadCount] = useState(0);

	const announcementQuery = useQuery(
		[`announcement${courseId}`, courseId],
		() =>
			announcementAPI.list({
				course_id: courseId,
				per_page: -1,
				status: 'publish',
				request_from: 'learn',
			}),
		{
			onSuccess: (announcements) => {
				const unreadAnnouncements = announcements?.data?.filter(
					(announcement: AnnouncementSchema) =>
						announcement[`has_user_read_${announcement?.id}`] === false
				);
				setReadCount(unreadAnnouncements?.length);
			},
			refetchInterval: 300000,
		}
	);

	const isEmptyAnnouncement =
		announcementQuery.isSuccess && isEmpty(announcementQuery?.data?.data);

	// For adjusting height of less than 3 announcements.
	const isLimitedAnnouncement =
		announcementQuery.isSuccess &&
		!isEmpty(announcementQuery?.data?.data) &&
		announcementQuery?.data?.data.length < 3;

	return (
		<Popover>
			<PopoverTrigger>
				<span
					style={{
						display: 'flex',
						justifyContent: 'center',
						alignItems: 'center',
					}}
				>
					<Icon
						as={BsMegaphone}
						boxSize="5"
						color={readCount > 0 ? 'red.500' : 'gray.500'}
						cursor="pointer"
					/>
					{readCount > 0 ? (
						<Badge
							bgColor="red"
							color="white"
							pos="relative"
							top="-11px"
							right="-2px"
							borderRadius="full"
						>
							{readCount}
						</Badge>
					) : null}
				</span>
			</PopoverTrigger>
			<PopoverContent
				w={['xs', 'md', 'xl']}
				h={
					isEmptyAnnouncement ||
					announcementQuery.isLoading ||
					isLimitedAnnouncement
						? 'fit-content'
						: 'xl'
				}
			>
				<PopoverArrow />
				<PopoverHeader fontWeight="semibold" fontSize="lg" textAlign="center">
					{__('Announcements', 'masteriyo')}
				</PopoverHeader>
				<PopoverBody overflowY="auto">
					<Stack direction="column-reverse" spacing="4" px="4" py="4">
						{announcementQuery?.isLoading ? (
							<Progress size="sm" isIndeterminate />
						) : isEmptyAnnouncement ? (
							<Alert status="info">
								<AlertIcon />
								{__('No announcements found.', 'masteriyo')}
							</Alert>
						) : (
							announcementQuery?.data?.data.map(
								(announcement: AnnouncementSchema) => (
									<Message key={announcement?.id} announcement={announcement} />
								)
							)
						)}
					</Stack>
				</PopoverBody>
			</PopoverContent>
		</Popover>
	);
};

export default Announcements;

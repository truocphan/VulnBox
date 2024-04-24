import {
	Avatar,
	Box,
	Divider,
	Flex,
	IconButton,
	Menu,
	MenuButton,
	MenuItem,
	MenuList,
	Stack,
	Text,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { BiDotsVerticalRounded, BiShow } from 'react-icons/bi';
import { useMutation, useQueryClient } from 'react-query';
import TimeAgo from 'timeago-react';
import API from '../../../../../../../assets/js/back-end/utils/api';
import { urls } from '../../backend/constants/urls';
import { AnnouncementSchema } from '../../backend/types/announcement';

interface Props {
	announcement: AnnouncementSchema;
}

const Message: React.FC<Props> = ({ announcement }) => {
	const announcementAPI = new API(urls.courseAnnouncement);
	const queryClient = useQueryClient();
	const toast = useToast();

	const bubbleStyle = {
		borderBottomRightRadius: 'lg',
		borderBottomLeftRadius: 'lg',
		borderTopRightRadius: 'lg',
		bg: announcement[`has_user_read_${announcement?.id}`]
			? '#f2f7fd'
			: '#7af3df',
		lineBreak: 'auto',
	};

	const updateAnnouncement = useMutation<AnnouncementSchema>(
		() =>
			announcementAPI.update(announcement?.id, {
				has_read: true,
				request_from: 'learn',
			}),
		{
			onSuccess: () => {
				queryClient.invalidateQueries(
					`announcement${announcement?.course?.id}`
				);
				toast({
					title: __('Marked as read.', 'masteriyo'),
					isClosable: true,
					status: 'success',
				});
			},

			onError: (error: any) => {
				const message: any = error?.message
					? error?.message
					: error?.data?.message;

				toast({
					title: __('Failed to marked as read.', 'masteriyo'),
					description: message ? `${message}` : undefined,
					status: 'error',
					isClosable: true,
				});
			},
		}
	);

	const readAnnouncement = () => {
		updateAnnouncement.mutate();
	};

	return (
		<Stack direction="column" spacing="2" flex="1" align="flex-start">
			<Flex w="full" justify="space-between">
				<Stack direction="row" spacing="2" align="center">
					<Avatar size="sm" src={announcement?.author?.avatar_url} />
					<Text fontSize="sm" fontWeight="medium">
						{announcement?.author?.display_name}
					</Text>
				</Stack>
				<Menu placement="bottom-end">
					<MenuButton
						as={IconButton}
						icon={<BiDotsVerticalRounded />}
						variant="outline"
						rounded="sm"
						fontSize="md"
						size="xs"
					/>
					<MenuList>
						<MenuItem
							isDisabled={
								announcement[`has_user_read_${announcement?.id}`] || false
							}
							onClick={readAnnouncement}
							icon={<BiShow />}
							_hover={{ color: 'primary.500' }}
						>
							{__('Mark as Read', 'masteriyo')}
						</MenuItem>
					</MenuList>
				</Menu>
			</Flex>
			<Text fontSize="lg" fontWeight="medium">
				{announcement?.title}
			</Text>
			<Box
				dangerouslySetInnerHTML={{ __html: announcement?.description }}
				fontSize="sm"
				p="4"
				py="4"
				sx={bubbleStyle}
				textAlign="start"
				w="full"
			/>
			<Text fontSize="xs" color="gray.400">
				<TimeAgo datetime={`${announcement?.date_created} UTC`} live={false} />
			</Text>

			<Divider py="2" />
		</Stack>
	);
};

export default Message;

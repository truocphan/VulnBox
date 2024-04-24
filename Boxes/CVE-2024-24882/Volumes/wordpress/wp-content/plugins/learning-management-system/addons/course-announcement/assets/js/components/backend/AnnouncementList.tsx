import {
	Avatar,
	Badge,
	Button,
	ButtonGroup,
	Checkbox,
	Icon,
	IconButton,
	Link,
	Menu,
	MenuButton,
	MenuItem,
	MenuList,
	Stack,
	Text,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import {
	BiCalendar,
	BiDotsVerticalRounded,
	BiEdit,
	BiShow,
	BiTrash,
} from 'react-icons/bi';
import { Link as RouterLink } from 'react-router-dom';
import { Td, Tr } from 'react-super-responsive-table';
import routes from '../../../../../../assets/js/back-end/constants/routes';
import { getWordpressLocalTime } from '../../../../../../assets/js/back-end/utils/utils';
import { AnnouncementSchema } from './types/announcement';

interface Props {
	data: AnnouncementSchema;
	onDeletePress: (id: number) => void;
	onTrashPress: (id: number) => void;
	onRestorePress: (id: number) => void;
	setBulkIds: (value: string[]) => void;
	bulkIds: string[];
	isLoading?: boolean;
}

const AnnouncementList: React.FC<Props> = (props) => {
	const {
		data,
		onDeletePress,
		onTrashPress,
		onRestorePress,
		setBulkIds,
		bulkIds,
		isLoading,
	} = props;

	return (
		<Tr>
			<Td>
				<Checkbox
					isDisabled={isLoading}
					isChecked={bulkIds.includes(data?.id.toString())}
					onChange={(e) =>
						setBulkIds(
							e.target.checked
								? [...bulkIds, data?.id.toString()]
								: bulkIds.filter((item) => item !== data?.id.toString())
						)
					}
				/>
			</Td>
			<Td>
				{data?.status === 'trash' ? (
					<Text fontWeight="semibold">{data?.title}</Text>
				) : (
					<Link
						as={RouterLink}
						to={routes.courseAnnouncement.edit.replace(
							':courseAnnouncementId',
							data?.id.toString()
						)}
						fontWeight="semibold"
						_hover={{ color: 'primary.500' }}
					>
						{data?.title}
						{data?.status === 'draft' ? (
							<Badge bg="primary.200" fontSize="10px" ml="2" mt="-2">
								{__('Draft', 'masteriyo')}
							</Badge>
						) : null}
					</Link>
				)}
			</Td>
			<Td>
				<Stack direction="row" spacing="2" alignItems="center">
					<Avatar src={data?.author?.avatar_url} size="xs" />
					<Text fontSize="xs" fontWeight="medium" color="gray.600">
						{data?.author?.display_name}
					</Text>
				</Stack>
			</Td>
			<Td>
				<Text color="gray.600" fontSize="sm">
					{data?.course?.name}
				</Text>
			</Td>
			<Td>
				<Stack direction="row" spacing="2" alignItems="center" color="gray.600">
					<Icon as={BiCalendar} />
					<Text fontSize="xs" fontWeight="medium">
						{getWordpressLocalTime(data?.date_created, 'Y-m-d, h:i A')}
					</Text>
				</Stack>
			</Td>
			<Td>
				{data?.status === 'trash' ? (
					<Menu placement="bottom-end">
						<MenuButton
							as={IconButton}
							icon={<BiDotsVerticalRounded />}
							variant="outline"
							rounded="sm"
							fontSize="large"
							size="xs"
						/>
						<MenuList>
							<MenuItem
								onClick={() => onRestorePress(data?.id)}
								icon={<BiShow />}
								_hover={{ color: 'primary.500' }}
							>
								{__('Restore', 'masteriyo')}
							</MenuItem>
							<MenuItem
								onClick={() => onDeletePress(data?.id)}
								icon={<BiTrash />}
								_hover={{ color: 'red.500' }}
							>
								{__('Delete Permanently', 'masteriyo')}
							</MenuItem>
						</MenuList>
					</Menu>
				) : (
					<ButtonGroup>
						<RouterLink
							to={routes.courseAnnouncement.edit.replace(
								':courseAnnouncementId',
								data?.id.toString()
							)}
						>
							<Button colorScheme="primary" leftIcon={<BiEdit />} size="xs">
								{__('Edit', 'masteriyo')}
							</Button>
						</RouterLink>
						<Menu placement="bottom-end">
							<MenuButton
								as={IconButton}
								icon={<BiDotsVerticalRounded />}
								variant="outline"
								rounded="sm"
								fontSize="large"
								size="xs"
							/>
							<MenuList>
								<MenuItem
									onClick={() => onTrashPress(data?.id)}
									icon={<BiTrash />}
									_hover={{ color: 'red.500' }}
								>
									{__('Trash', 'masteriyo')}
								</MenuItem>
							</MenuList>
						</Menu>
					</ButtonGroup>
				)}
			</Td>
		</Tr>
	);
};

export default AnnouncementList;

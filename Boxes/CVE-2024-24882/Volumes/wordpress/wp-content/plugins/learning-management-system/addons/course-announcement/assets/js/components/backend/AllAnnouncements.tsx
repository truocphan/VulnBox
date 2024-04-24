import {
	Box,
	Checkbox,
	Container,
	Icon,
	Stack,
	Text,
	useDisclosure,
	useMediaQuery,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import { Add } from 'iconsax-react';
import React, { useState } from 'react';
import {
	BiBookmarks,
	BiBookOpen,
	BiSolidMegaphone,
	BiTrash,
} from 'react-icons/bi';
import { MdOutlineArrowDropDown, MdOutlineArrowDropUp } from 'react-icons/md';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { useNavigate } from 'react-router-dom';
import { Table, Tbody, Th, Thead, Tr } from 'react-super-responsive-table';
import ActionDialog from '../../../../../../assets/js/back-end/components/common/ActionDialog';
import EmptyInfo from '../../../../../../assets/js/back-end/components/common/EmptyInfo';
import FilterTabs from '../../../../../../assets/js/back-end/components/common/FilterTabs';
import FloatingBulkAction from '../../../../../../assets/js/back-end/components/common/FloatingBulkAction';
import {
	Header,
	HeaderLeftSection,
	HeaderLogo,
	HeaderPrimaryButton,
	HeaderRightSection,
	HeaderTop,
} from '../../../../../../assets/js/back-end/components/common/Header';
import MasteriyoPagination from '../../../../../../assets/js/back-end/components/common/MasteriyoPagination';
import routes from '../../../../../../assets/js/back-end/constants/routes';
import API from '../../../../../../assets/js/back-end/utils/api';
import {
	deepMerge,
	isEmpty,
} from '../../../../../../assets/js/back-end/utils/utils';
import { urls } from '../../../../../course-announcement/assets/js/components/backend/constants/urls';
import AnnouncementList from './AnnouncementList';
import AnnouncementFilter from './components/AnnouncementFilter';
import { SkeletonAnnouncementList } from './components/AnnouncementSkeleton';

const tabButtons: FilterTabs = [
	{
		status: 'any',
		name: __('All Announcements', 'masteriyo'),
		icon: <BiSolidMegaphone />,
	},
	{
		status: 'publish',
		name: __('Published', 'masteriyo'),
		icon: <BiBookOpen />,
	},
	{
		status: 'draft',
		name: __('Draft', 'masteriyo'),
		icon: <BiBookmarks />,
	},
	{
		status: 'trash',
		name: __('Trash', 'masteriyo'),
		icon: <BiTrash />,
	},
];

interface FilterParams {
	search?: string;
	status?: string;
	per_page?: number;
	page?: number;
	orderby: string;
	order: 'asc' | 'desc';
}

const AllAnnouncements = () => {
	const announcementAPI = new API(urls.courseAnnouncement);
	const navigate = useNavigate();
	const toast = useToast();
	const [filterParams, setFilterParams] = useState<FilterParams>({
		order: 'desc',
		orderby: 'date',
	});
	const [deleteAnnouncementId, setDeleteAnnouncementId] = useState<number>();
	const queryClient = useQueryClient();
	const { onClose, onOpen, isOpen } = useDisclosure();
	const [active, setActive] = useState('any');
	const [bulkAction, setBulkAction] = useState<string>('');
	const [bulkIds, setBulkIds] = useState<string[]>([]);

	const [min360px] = useMediaQuery('(min-width: 360px)');

	const announcementQuery = useQuery(
		['announcementList', filterParams],
		() => announcementAPI.list(filterParams),
		{
			keepPreviousData: true,
		}
	);

	const deleteAnnouncement = useMutation(
		(id: number) => announcementAPI.delete(id, { force: true, children: true }),
		{
			onSuccess: () => {
				queryClient.invalidateQueries('announcementList');
				onClose();
			},
		}
	);

	const restoreAnnouncement = useMutation(
		(id: number) => announcementAPI.restore(id),
		{
			onSuccess: () => {
				toast({
					title: __('Announcement Restored', 'masteriyo'),
					isClosable: true,
					status: 'success',
				});
				queryClient.invalidateQueries('announcementList');
			},
		}
	);

	const trashAnnouncement = useMutation(
		(id: number) => announcementAPI.delete(id),
		{
			onSuccess: () => {
				queryClient.invalidateQueries('announcementList');
				toast({
					title: __('Announcement Trashed', 'masteriyo'),
					isClosable: true,
					status: 'success',
				});
			},
		}
	);

	const onTrashPress = (courseId: number) => {
		courseId && trashAnnouncement.mutate(courseId);
	};

	const onDeletePress = (courseId: number) => {
		onOpen();
		setBulkAction('');
		setDeleteAnnouncementId(courseId);
	};

	const onDeleteConfirm = () => {
		deleteAnnouncementId
			? deleteAnnouncement.mutate(deleteAnnouncementId)
			: null;
	};

	const onRestorePress = (courseId: number) => {
		courseId ? restoreAnnouncement.mutate(courseId) : null;
	};

	const onChangeAnnouncementStatus = (status: string) => {
		setActive(status);
		setFilterParams(
			deepMerge(filterParams, {
				status: status,
			})
		);
		setBulkIds([]);
		setBulkAction('');
	};

	const filterAnnouncementsBy = (order: 'asc' | 'desc', orderBy: string) =>
		setFilterParams(
			deepMerge({
				...filterParams,
				order: order,
				orderby: orderBy,
			})
		);

	const onBulkActionApply = {
		delete: useMutation(
			(data: any) =>
				announcementAPI.bulkDelete('delete', {
					ids: data,
					force: true,
					children: true,
				}),
			{
				onSuccess() {
					queryClient.invalidateQueries('announcementList');
					onClose();
					setBulkIds([]);
					toast({
						title: __('Announcements Deleted', 'masteriyo'),
						isClosable: true,
						status: 'success',
					});
				},
			}
		),
		trash: useMutation(
			(data: any) => announcementAPI.bulkDelete('delete', { ids: data }),
			{
				onSuccess() {
					queryClient.invalidateQueries('announcementList');
					onClose();
					setBulkIds([]);
					toast({
						title: __('Announcements Trashed', 'masteriyo'),
						isClosable: true,
						status: 'success',
					});
				},
			}
		),
		restore: useMutation(
			(data: any) => announcementAPI.bulkRestore('restore', { ids: data }),
			{
				onSuccess() {
					queryClient.invalidateQueries('announcementList');
					onClose();
					setBulkIds([]);
					toast({
						title: __('Announcements Restored', 'masteriyo'),
						isClosable: true,
						status: 'success',
					});
				},
			}
		),
	};

	return (
		<Stack direction="column" spacing="8" alignItems="center">
			<Header>
				<HeaderTop>
					<HeaderLeftSection>
						<HeaderLogo />
						<FilterTabs
							tabs={tabButtons}
							defaultActive="any"
							onTabChange={onChangeAnnouncementStatus}
							counts={announcementQuery.data?.meta.announcement_count}
							isCounting={announcementQuery.isLoading}
						/>
					</HeaderLeftSection>
					<HeaderRightSection>
						<HeaderPrimaryButton
							onClick={() => navigate(routes.courseAnnouncement.add)}
							leftIcon={min360px ? <Add /> : undefined}
						>
							{__('Add New Announcement', 'masteriyo')}
						</HeaderPrimaryButton>
					</HeaderRightSection>
				</HeaderTop>
			</Header>

			<Container maxW="container.xl">
				<Box bg="white" py={{ base: 6, md: 12 }} shadow="box" mx="auto">
					<Stack direction="column" spacing="10">
						<AnnouncementFilter
							setFilterParams={setFilterParams}
							filterParams={filterParams}
						/>
						<Stack
							direction="column"
							spacing="8"
							mt={{
								base: '15px !important',
								sm: '15px !important',
								md: '2.5rem !important',
								lg: '2.5rem !important',
							}}
						>
							<Table>
								<Thead>
									<Tr>
										<Th>
											<Checkbox
												isDisabled={
													announcementQuery.isLoading ||
													announcementQuery.isFetching ||
													announcementQuery.isRefetching
												}
												isIndeterminate={
													announcementQuery?.data?.data?.length !==
														bulkIds.length && bulkIds.length > 0
												}
												isChecked={
													announcementQuery?.data?.data?.length ===
														bulkIds.length &&
													!isEmpty(announcementQuery?.data?.data as boolean)
												}
												onChange={(e) =>
													setBulkIds(
														e.target.checked
															? announcementQuery?.data?.data?.map(
																	(announcement: any) =>
																		announcement.id.toString()
															  )
															: []
													)
												}
											/>
										</Th>
										<Th>
											<Stack direction="row" alignItems="center">
												<Text fontSize="xs">{__('Title', 'masteriyo')}</Text>
												<Stack direction="column">
													<Icon
														as={
															filterParams?.order === 'desc'
																? MdOutlineArrowDropDown
																: MdOutlineArrowDropUp
														}
														h={6}
														w={6}
														cursor="pointer"
														color={
															filterParams?.orderby === 'title'
																? 'black'
																: 'lightgray'
														}
														transition="1s"
														_hover={{ color: 'black' }}
														onClick={() =>
															filterAnnouncementsBy(
																filterParams?.order === 'desc' ? 'asc' : 'desc',
																'title'
															)
														}
													/>
												</Stack>
											</Stack>
										</Th>
										<Th>{__('Author', 'masteriyo')}</Th>
										<Th>{__('Course', 'masteriyo')}</Th>
										<Th>
											<Stack direction="row" alignItems="center">
												<Text fontSize="xs">{__('Date', 'masteriyo')}</Text>
												<Stack direction="column">
													<Icon
														as={
															filterParams?.order === 'desc'
																? MdOutlineArrowDropDown
																: MdOutlineArrowDropUp
														}
														h={6}
														w={6}
														cursor="pointer"
														color={
															filterParams?.orderby === 'date'
																? 'black'
																: 'lightgray'
														}
														transition="1s"
														_hover={{ color: 'black' }}
														onClick={() =>
															filterAnnouncementsBy(
																filterParams?.order === 'desc' ? 'asc' : 'desc',
																'date'
															)
														}
													/>
												</Stack>
											</Stack>
										</Th>
										<Th>{__('Actions', 'masteriyo')}</Th>
									</Tr>
								</Thead>
								<Tbody>
									{announcementQuery.isLoading ||
									!announcementQuery.isFetched ? (
										<SkeletonAnnouncementList />
									) : announcementQuery.isSuccess &&
									  isEmpty(announcementQuery?.data?.data) ? (
										<EmptyInfo
											message={__('No announcement found.', 'masteriyo')}
										/>
									) : (
										announcementQuery?.data?.data?.map((announcement: any) => (
											<AnnouncementList
												key={announcement?.id}
												data={announcement}
												bulkIds={bulkIds}
												onDeletePress={onDeletePress}
												onRestorePress={onRestorePress}
												onTrashPress={onTrashPress}
												setBulkIds={setBulkIds}
												isLoading={
													announcementQuery.isLoading ||
													announcementQuery.isFetching ||
													announcementQuery.isRefetching
												}
											/>
										))
									)}
								</Tbody>
							</Table>
						</Stack>
					</Stack>
				</Box>
				{announcementQuery.isSuccess &&
					!isEmpty(announcementQuery?.data?.data) && (
						<MasteriyoPagination
							metaData={announcementQuery?.data?.meta}
							setFilterParams={setFilterParams}
							perPageText={__('Announcements Per Page:', 'masteriyo')}
							extraFilterParams={{
								order: filterParams?.order,
								orderby: filterParams?.orderby,
								search: filterParams?.search,
								status: filterParams?.status,
							}}
						/>
					)}
			</Container>
			<FloatingBulkAction
				openToast={onOpen}
				status={active}
				setBulkAction={setBulkAction}
				bulkIds={bulkIds}
				setBulkIds={setBulkIds}
				trashable={true}
			/>
			<ActionDialog
				isOpen={isOpen}
				onClose={onClose}
				confirmButtonColorScheme={
					'restore' === bulkAction ? 'primary' : undefined
				}
				onConfirm={
					'' === bulkAction
						? onDeleteConfirm
						: () => {
								onBulkActionApply[bulkAction].mutate(bulkIds);
						  }
				}
				action={bulkAction}
				isLoading={
					'' === bulkAction
						? deleteAnnouncement.isLoading
						: onBulkActionApply?.[bulkAction]?.isLoading ?? false
				}
				dialogTexts={{
					default: {
						header: __('Deleting announcement', 'masteriyo'),
						body: __(
							'Are you sure? You can’t restore after deleting.',
							'masteriyo'
						),
						confirm: __('Move to Trash', 'masteriyo'),
					},
					trash: {
						header: __('Moving announcements to trash', 'masteriyo'),
						body: __(
							'Are you sure? The selected announcements will be moved to trash.',
							'masteriyo'
						),
						confirm: __('Move to Trash', 'masteriyo'),
					},
					delete: {
						header: __('Deleting Announcements', 'masteriyo'),
						body: __('Are you sure? You can’t restore after deleting.'),
						confirm: __('Delete', 'masteriyo'),
					},
					restore: {
						header: __('Restoring Announcements', 'masteriyo'),
						body: __(
							'Are you sure? The selected announcements will be restored from the trash.',
							'masteriyo'
						),
						confirm: __('Restore', 'masteriyo'),
					},
				}}
			/>
		</Stack>
	);
};

export default AllAnnouncements;

import {
	Box,
	Container,
	Icon,
	Stack,
	Text,
	useDisclosure,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useMemo, useState } from 'react';
import {
	BiCheckCircle,
	BiLoaderCircle,
	BiWalletAlt,
	BiXCircle,
} from 'react-icons/bi';
import { MdOutlineArrowDropDown, MdOutlineArrowDropUp } from 'react-icons/md';
import { useQuery } from 'react-query';
import { useSearchParams } from 'react-router-dom';
import { Table, Tbody, Th, Thead, Tr } from 'react-super-responsive-table';
import EmptyInfo from '../../../../../../assets/js/back-end/components/common/EmptyInfo';
import FilterTabs from '../../../../../../assets/js/back-end/components/common/FilterTabs';
import {
	Header,
	HeaderLeftSection,
	HeaderLogo,
	HeaderTop,
} from '../../../../../../assets/js/back-end/components/common/Header';
import MasteriyoPagination from '../../../../../../assets/js/back-end/components/common/MasteriyoPagination';
import API from '../../../../../../assets/js/back-end/utils/api';
import {
	deepMerge,
	isEmpty,
} from '../../../../../../assets/js/back-end/utils/utils';
import { urls } from '../../constants/urls';
import { WithdrawDataMap, WithdrawResponseDataMap } from '../../types/withdraw';
import ActionDialog from './components/ActionDialog';
import SkeletonWithdrawsList from './components/SkeletonWithdrawsList';
import WithdrawRow from './components/WithdrawRow';
import WithdrawsFilter from './components/WithdrawsFilter';

type FilterParams = {
	per_page?: number;
	page?: number;
	status: string;
	after?: string;
	before?: string;
	orderby: string;
	order: 'asc' | 'desc';
	instructor?: number;
};

type WithdrawCount = {
	any: number | undefined;
	approved: number | undefined;
	pending: number | undefined;
	rejected: number | undefined;
};

const WITHDRAWS_TABS = [
	{
		status: 'any',
		name: __('All', 'masteriyo'),
		icon: <BiWalletAlt />,
	},
	{
		status: 'approved',
		name: __('Approved', 'masteriyo'),
		icon: <BiCheckCircle />,
	},
	{
		status: 'pending',
		name: __('Pending', 'masteriyo'),
		icon: <BiLoaderCircle />,
	},
	{
		status: 'rejected',
		name: __('Rejected', 'masteriyo'),
		icon: <BiXCircle />,
	},
];

const AllWithdraws: React.FC = () => {
	const [param] = useSearchParams();

	const [filterParams, setFilterParams] = useState<FilterParams>({
		status: param.get('status') ?? 'any',
		order: 'desc',
		orderby: 'date',
	});

	const [withdrawStatus, setWithdrawStatus] = useState<string>(
		param.get('status') ?? 'any'
	);
	const [withdrawStatusCount, setWithdrawStatusCount] = useState<WithdrawCount>(
		{
			any: undefined,
			approved: undefined,
			pending: undefined,
			rejected: undefined,
		}
	);
	const { isOpen, onClose, onOpen } = useDisclosure();
	const [action, setAction] = useState<string>('');
	const [actionId, setActionId] = useState<number>(0);

	const withdrawAPI = new API(urls.withdraws);
	const withdrawsQuery = useQuery<WithdrawResponseDataMap>(
		['withdrawsList', filterParams],
		() => withdrawAPI.list(filterParams),
		{
			keepPreviousData: true,
			onSuccess(data: any) {
				const withdrawCount = data?.meta?.withdraws_count;
				setWithdrawStatusCount({
					any: withdrawCount?.any,
					approved: withdrawCount?.approved,
					pending: withdrawCount?.pending,
					rejected: withdrawCount?.rejected,
				});
			},
		}
	);

	const filterBy = (order: 'asc' | 'desc', orderBy: string) =>
		setFilterParams(
			deepMerge({
				...filterParams,
				order: order,
				orderby: orderBy,
			})
		);

	const onChangeStatusFilter = (status: string) => {
		setWithdrawStatus(status);
		setFilterParams(
			deepMerge(filterParams, {
				status,
			})
		);
	};

	const onUpdate = (id: number, action: string) => {
		setAction(action);
		setActionId(id);
		onOpen();
	};

	const selectedWithdraw = useMemo(() => {
		return withdrawsQuery.data?.data?.find((item) => item.id === actionId);
	}, [actionId, withdrawsQuery.data?.data]);

	return (
		<Stack direction="column" spacing={8} alignItems="center">
			<Header>
				<HeaderTop>
					<HeaderLeftSection>
						<HeaderLogo />
						<FilterTabs
							tabs={WITHDRAWS_TABS}
							defaultActive="any"
							onTabChange={onChangeStatusFilter}
							counts={withdrawStatusCount}
							isCounting={withdrawsQuery.isLoading}
						/>
					</HeaderLeftSection>
				</HeaderTop>
			</Header>
			<Container maxW="container.xl" mt="6">
				<Box bg="white" py={{ base: 6, md: 12 }} shadow="box" mx="auto">
					<WithdrawsFilter setFilterParams={setFilterParams} />
					<Stack direction="column" spacing="10">
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
											<Stack direction="row" alignItems="center">
												<Text fontSize="xs">
													{__('Requested On', 'masteriyo')}
												</Text>
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
															filterBy(
																filterParams?.order === 'desc' ? 'asc' : 'desc',
																'date'
															)
														}
													/>
												</Stack>
											</Stack>
										</Th>
										<Th>
											<Stack direction="row" alignItems="center">
												<Text fontSize="xs">
													{__('Requested By', 'masteriyo')}
												</Text>
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
															filterParams?.orderby === 'id'
																? 'black'
																: 'lightgray'
														}
														transition="1s"
														_hover={{ color: 'black' }}
														onClick={() =>
															filterBy(
																filterParams?.order === 'desc' ? 'asc' : 'desc',
																'id'
															)
														}
													/>
												</Stack>
											</Stack>
										</Th>
										<Th>{__('Amount', 'masteriyo')}</Th>
										<Th>{__('Withdraw Method', 'masteriyo')}</Th>
										<Th>{__('Status', 'masteriyo')}</Th>
										<Th>{__('Actions', 'masteriyo')}</Th>
									</Tr>
								</Thead>
								<Tbody>
									{withdrawsQuery.isLoading || !withdrawsQuery.isFetched ? (
										<SkeletonWithdrawsList />
									) : withdrawsQuery.isSuccess &&
									  !isEmpty(withdrawsQuery?.data?.data) ? (
										withdrawsQuery.data.data.map((withdraw: any) => (
											<WithdrawRow
												key={withdraw?.id}
												data={withdraw}
												onUpdate={onUpdate}
											/>
										))
									) : (
										<EmptyInfo
											message={__('No withdraw requests found', 'masteriyo')}
										/>
									)}
								</Tbody>
							</Table>
						</Stack>
					</Stack>
				</Box>
				{withdrawsQuery.isSuccess && !isEmpty(withdrawsQuery?.data?.data) && (
					<MasteriyoPagination
						extraFilterParams={{
							status: filterParams?.status,
							order: filterParams?.order,
							orderby: filterParams?.orderby,
						}}
						metaData={withdrawsQuery?.data?.meta}
						setFilterParams={setFilterParams}
						perPageText={__('Withdraws Per Page:', 'masteriyo')}
					/>
				)}
			</Container>
			<ActionDialog
				isOpen={isOpen}
				onClose={onClose}
				action={action}
				data={selectedWithdraw as WithdrawDataMap}
				id={actionId}
			/>
		</Stack>
	);
};

export default AllWithdraws;

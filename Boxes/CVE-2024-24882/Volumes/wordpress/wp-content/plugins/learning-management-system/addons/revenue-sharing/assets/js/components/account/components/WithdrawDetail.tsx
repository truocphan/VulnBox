import { Box, Button, Stack, Text, useDisclosure } from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { BiMoney, BiMoneyWithdraw } from 'react-icons/bi';
import { BsPersonFillGear } from 'react-icons/bs';
import { useQuery } from 'react-query';
import localized from '../../../../../../../assets/js/account/utils/global';
import urls from '../../../../../../../assets/js/back-end/constants/urls';
import { UserSchema } from '../../../../../../../assets/js/back-end/schemas';
import API from '../../../../../../../assets/js/back-end/utils/api';
import CountBox from './CountBox';
import SkeletonWithdrawDetails from './SkeletonWithdrawDetails';
import WithdrawMethodForm from './WithdrawMethodForm';
import WithdrawRequestForm from './WithdrawRequestForm';

const withdrawMethods = {
	e_check: __('E-Check', 'masteriyo'),
	bank_transfer: __('Bank Transfer', 'masteriyo'),
	paypal: __('PayPal', 'masteriyo'),
};

const WithdrawDetail: React.FC = () => {
	const userAPI = new API(urls.currentUser);

	const userDataQuery = useQuery<UserSchema>('userProfile', () =>
		userAPI.get()
	);
	const { isOpen, onOpen, onClose } = useDisclosure();

	const withdrawPreference =
		userDataQuery.data?.revenue_sharing?.withdraw_method_preference?.method ??
		'';

	if (userDataQuery.isLoading || !userDataQuery.isFetched) {
		return <SkeletonWithdrawDetails />;
	}

	return (
		<Box>
			<Stack
				direction={['column', 'row', 'row']}
				justify="space-between"
				align="center"
				mb="5"
			>
				<Stack
					direction={{ base: 'column', sm: 'column', md: 'row', lg: 'row' }}
					align="center"
					spacing="4"
				>
					<CountBox
						title={__('Total Balance', 'masteriyo')}
						subtitle={
							userDataQuery.data?.revenue_sharing?.available_amount_formatted ??
							localized.currency.symbol + '0'
						}
						colorScheme="primary"
						icon={<BiMoney />}
					/>
					<CountBox
						title={__('Withdrawable Balance', 'masteriyo')}
						subtitle={
							userDataQuery.data?.revenue_sharing
								?.withdrawable_amount_formatted ??
							localized.currency.symbol + '0'
						}
						colorScheme="green"
						icon={<BiMoneyWithdraw />}
					/>
					<CountBox
						title={__('Withdraw Method', 'masteriyo')}
						subtitle={
							<Stack direction="row" align="center" spacing="2">
								<Text>
									{withdrawMethods?.[withdrawPreference] ??
										__('Not set', 'masteriyo')}
								</Text>
								<Button
									fontWeight="normal"
									size="xs"
									onClick={onOpen}
									colorScheme="primary"
									variant="outline"
								>
									{__('Edit', 'masteriyo')}
								</Button>
							</Stack>
						}
						colorScheme="cyan"
						icon={<BsPersonFillGear />}
					/>
				</Stack>
			</Stack>
			<WithdrawRequestForm data={userDataQuery.data as UserSchema} />
			<WithdrawMethodForm
				data={userDataQuery.data?.revenue_sharing?.withdraw_method_preference}
				isOpen={isOpen}
				onClose={onClose}
			/>
		</Box>
	);
};

export default WithdrawDetail;

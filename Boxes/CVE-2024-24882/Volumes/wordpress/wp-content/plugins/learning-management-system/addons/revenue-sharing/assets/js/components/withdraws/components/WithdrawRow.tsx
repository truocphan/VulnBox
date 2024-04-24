import {
	Badge,
	Button,
	ButtonGroup,
	Icon,
	Stack,
	Text,
} from '@chakra-ui/react';
import { sprintf, __ } from '@wordpress/i18n';
import React from 'react';
import { BiCalendar } from 'react-icons/bi';
import { Td, Tr } from 'react-super-responsive-table';
import { getWordpressLocalTime } from '../../../../../../../assets/js/back-end/utils/utils';
import { WithdrawStatus } from '../../../enums/Enum';
import { WithdrawDataMap } from '../../../types/withdraw';

const REJECTION_MESSAGE = {
	invalid_payment: __('Invalid payment method', 'masteriyo'),
	invalid_request: __('Invalid request', 'masteriyo'),
};

type Props = {
	data: WithdrawDataMap;
	onUpdate: (id: number, action: string) => void;
};

const WithdrawRow: React.FC<Props> = (props) => {
	const {
		data: {
			status,
			id,
			withdraw_amount,
			withdraw_method,
			withdrawer,
			date_created,
			rejection_detail,
			date_modified,
		},
		onUpdate,
	} = props;

	const withdrawStatus =
		status == WithdrawStatus.Approved
			? 'green'
			: status == WithdrawStatus.Pending
			? 'yellow'
			: status == WithdrawStatus.Rejected
			? 'red'
			: 'gray';

	const withdrawMethod = withdraw_method?.method;

	const WithdrawMethodInfo = () => {
		if (!withdrawMethod) return null;

		const methodMap = {
			e_check: 'E-Check',
			bank_transfer: 'Bank Transfer',
			paypal: 'PayPal',
		};

		return (
			<Stack direction="column" spacing="2">
				<Text fontWeight="bold" color="gray.600" fontSize="sm">
					{methodMap[withdrawMethod]}
				</Text>
				{'e_check' === withdrawMethod && (
					<Text color="gray.600" fontSize="xs" align="left">
						{sprintf(
							__('Physical Address: %s', 'masteriyo'),
							withdraw_method?.physical_address
						)}
					</Text>
				)}
				{'paypal' === withdrawMethod && (
					<Text color="gray.600" fontSize="xs" align="left">
						{sprintf(
							__('Email: %s', 'masteriyo'),
							withdraw_method?.paypal_email
						)}
					</Text>
				)}
				{'bank_transfer' === withdrawMethod && (
					<>
						<Text color="gray.600" fontSize="xs" align="left">
							{sprintf(
								__('Bank Name: %s', 'masteriyo'),
								withdraw_method?.bank_name
							)}
						</Text>
						<Text color="gray.600" fontSize="xs" align="left">
							{sprintf(
								__('A/C Name: %s', 'masteriyo'),
								withdraw_method?.account_number
							)}
						</Text>
						<Text color="gray.600" fontSize="xs" align="left">
							{sprintf(
								__('A/C Number: %s', 'masteriyo'),
								withdraw_method?.account_number
							)}
						</Text>
						<Text color="gray.600" fontSize="xs" align="left">
							{sprintf(__('IBAN: %s', 'masteriyo'), withdraw_method?.iban)}
						</Text>
						<Text color="gray.600" fontSize="xs" align="left">
							{sprintf(
								__('BIC/SWIFT CODE: %s', 'masteriyo'),
								withdraw_method?.swift_code
							)}
						</Text>
					</>
				)}
			</Stack>
		);
	};

	return (
		<Tr>
			<Td>
				<Stack direction="row" spacing="2" alignItems="center" color="gray.600">
					<Icon as={BiCalendar} />
					<Text fontSize="xs" fontWeight="medium">
						{getWordpressLocalTime(date_created, 'Y-m-d, h:i A')}
					</Text>
				</Stack>
			</Td>
			<Td>
				<Text fontWeight="semibold">{`#${id} ${withdrawer.display_name}`}</Text>
				<Text fontSize="xs" color="gray.600">
					{withdrawer.email}
				</Text>
			</Td>
			<Td>
				<Text fontSize="sm" color="gray.600">{`${withdraw_amount}`}</Text>
			</Td>
			<Td>
				<WithdrawMethodInfo />
			</Td>
			<Td>
				<Badge colorScheme={withdrawStatus}>{status}</Badge>
			</Td>
			<Td>
				{WithdrawStatus.Approved === status ? (
					<Stack
						direction="row"
						spacing="2"
						alignItems="center"
						color="gray.600"
						justify="end"
					>
						<Icon as={BiCalendar} />
						<Text fontSize="xs" fontWeight="medium">
							{getWordpressLocalTime(date_modified, 'Y-m-d, h:i A')}
						</Text>
					</Stack>
				) : status === WithdrawStatus.Rejected ? (
					<Stack justify="end">
						<Stack
							direction="row"
							spacing="2"
							alignItems="center"
							color="gray.600"
							justify="end"
						>
							<Icon as={BiCalendar} />
							<Text fontSize="xs" fontWeight="medium">
								{getWordpressLocalTime(date_modified, 'Y-m-d, h:i A')}
							</Text>
						</Stack>
						<Text maxW="150" fontSize="xs" fontWeight="normal" alignSelf="end">
							{'other' === rejection_detail?.reason
								? rejection_detail?.other_reason
								: REJECTION_MESSAGE[rejection_detail?.reason ?? '']}
						</Text>
					</Stack>
				) : (
					<ButtonGroup>
						<Button
							colorScheme="primary"
							size="xs"
							onClick={() => onUpdate(id, 'approve')}
						>
							{__('Approve', 'masteriyo')}
						</Button>
						<Button
							colorScheme="primary"
							size="xs"
							variant="outline"
							onClick={() => onUpdate(id, 'reject')}
						>
							{__('Reject', 'masteriyo')}
						</Button>
					</ButtonGroup>
				)}
			</Td>
		</Tr>
	);
};

export default WithdrawRow;

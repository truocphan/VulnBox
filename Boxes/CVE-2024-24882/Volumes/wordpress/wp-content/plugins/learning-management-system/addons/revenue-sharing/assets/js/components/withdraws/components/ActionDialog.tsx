import {
	AlertDialog,
	AlertDialogBody,
	AlertDialogContent,
	AlertDialogFooter,
	AlertDialogHeader,
	AlertDialogOverlay,
	Button,
	ButtonGroup,
	Collapse,
	FormControl,
	FormErrorMessage,
	Input,
	Text,
	useToast,
} from '@chakra-ui/react';
import { sprintf, __ } from '@wordpress/i18n';
import React, { useRef } from 'react';
import { Controller, useForm } from 'react-hook-form';
import { useMutation, useQueryClient } from 'react-query';
import Select from '../../../../../../../assets/js/back-end/components/common/Select';
import API from '../../../../../../../assets/js/back-end/utils/api';
import { urls } from '../../../constants/urls';
import { WithdrawStatus } from '../../../enums/Enum';
import { WithdrawDataMap } from '../../../types/withdraw';

type Props = {
	isOpen: boolean;
	onClose: () => void;
	action: string;
	data: WithdrawDataMap;
	id: number;
};

const WITHDRAW_REJECTION_OPTIONS = [
	{
		value: 'invalid_payment',
		label: __('Invalid payment method', 'masteriyo'),
	},
	{
		value: 'invalid_request',
		label: __('Invalid request', 'masteriyo'),
	},
	{
		value: 'other',
		label: __('Other', 'masteriyo'),
	},
];

const ActionDialog: React.FC<Props> = (props) => {
	const { isOpen, onClose, action, data, id } = props;
	const {
		register,
		handleSubmit,
		control,
		watch,
		reset,
		formState: { errors },
	} = useForm({
		defaultValues: {
			reason: 'invalid_payment',
			other_reason: '',
		},
	});
	const cancelRef = useRef<any>();

	const queryClient = useQueryClient();
	const toast = useToast();
	const watchRejectReason = useRef<string>();
	watchRejectReason.current = watch('reason', '');

	const withdrawAPI = new API(urls.withdraws);

	const approveWithdraw = useMutation(
		(id: number) =>
			withdrawAPI.update(id, {
				status: WithdrawStatus.Approved,
			}),
		{
			onSuccess() {
				queryClient.invalidateQueries('withdrawsList');
				onClose();
				reset();
				toast({
					title: __('Withdraw approved successfully', 'masteriyo'),
					isClosable: true,
					status: 'success',
				});
			},
		}
	);

	const rejectWithdraw = useMutation(
		(data: {
			id: number;
			rejection: {
				reason: string;
				detail: string;
			};
		}) =>
			withdrawAPI.update(id, {
				status: WithdrawStatus.Rejected,
				rejection_detail: data.rejection,
			}),
		{
			onSuccess() {
				queryClient.invalidateQueries('withdrawsList');
				onClose();
				reset();
				toast({
					title: __('Withdraw rejected successfully', 'masteriyo'),
					isClosable: true,
					status: 'success',
				});
			},
		}
	);

	return (
		<AlertDialog
			isOpen={isOpen}
			onClose={() => {
				onClose();
				reset();
			}}
			isCentered
			leastDestructiveRef={cancelRef}
		>
			<AlertDialogOverlay>
				<AlertDialogContent>
					<AlertDialogHeader>
						{'reject' === action
							? __('Reject Withdraw', 'masteriyo')
							: __('Approve Withdraw', 'masteriyo')}
					</AlertDialogHeader>
					<AlertDialogBody>
						{'reject' === action ? (
							<>
								<Text mb={4}>
									{sprintf(
										__(
											'Are you sure you want to reject %s withdraw request from %s?'
										),
										data?.withdraw_amount,
										data?.withdrawer.display_name
									)}
								</Text>
								<form onSubmit={(e) => e.preventDefault()}>
									<Controller
										control={control}
										name="reason"
										defaultValue="invalid_payment"
										render={({ field: { onChange, value } }) => (
											<Select
												options={WITHDRAW_REJECTION_OPTIONS}
												onChange={(v: any) => {
													onChange(v.value);
												}}
												value={WITHDRAW_REJECTION_OPTIONS.find(
													(x) => x.value === value
												)}
											/>
										)}
									/>
									<Collapse in={'other' === watchRejectReason.current}>
										<FormControl isInvalid={!!errors.other_reason}>
											<Input
												placeholder={__('Add rejection reason', 'masteriyo')}
												mt={4}
												{...register('other_reason', {
													required:
														'other' === watchRejectReason.current
															? __('Add rejection reason', 'masteriyo')
															: false,
												})}
											/>
											{errors.other_reason && (
												<FormErrorMessage>
													{errors.other_reason.message as string}
												</FormErrorMessage>
											)}
										</FormControl>
									</Collapse>
								</form>
							</>
						) : (
							sprintf(
								__(
									'Are you sure you want to approve %s withdraw request from %s?'
								),
								data?.withdraw_amount,
								data?.withdrawer.display_name
							)
						)}
					</AlertDialogBody>
					<AlertDialogFooter>
						<ButtonGroup>
							<Button onClick={onClose} variant="outline" ref={cancelRef}>
								{__('Cancel', 'masteriyo')}
							</Button>
							<Button
								colorScheme={'reject' === action ? 'red' : 'primary'}
								isLoading={
									'reject' === action
										? rejectWithdraw.isLoading
										: approveWithdraw.isLoading
								}
								onClick={() =>
									'reject' === action
										? handleSubmit((data: any) => {
												rejectWithdraw.mutate({
													id: id,
													rejection: data,
												});
										  })()
										: approveWithdraw.mutate(id)
								}
							>
								{'reject' === action
									? __('Reject', 'masteriyo')
									: __('Approve', 'masteriyo')}
							</Button>
						</ButtonGroup>
					</AlertDialogFooter>
				</AlertDialogContent>
			</AlertDialogOverlay>
		</AlertDialog>
	);
};

export default ActionDialog;

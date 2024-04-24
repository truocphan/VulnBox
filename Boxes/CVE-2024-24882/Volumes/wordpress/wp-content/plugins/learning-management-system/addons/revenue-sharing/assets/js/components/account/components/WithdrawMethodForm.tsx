import {
	Button,
	FormControl,
	FormLabel,
	Input,
	Modal,
	ModalBody,
	ModalCloseButton,
	ModalContent,
	ModalFooter,
	ModalHeader,
	ModalOverlay,
	Select,
	Stack,
	Text,
	useToast,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React, { useRef } from 'react';
import { useForm } from 'react-hook-form';
import { useMutation, useQueryClient } from 'react-query';
import localized from '../../../../../../../assets/js/account/utils/global';
import urls from '../../../../../../../assets/js/back-end/constants/urls';
import API from '../../../../../../../assets/js/back-end/utils/api';
import { WithdrawPreferenceDataMap } from '../../../types/withdraw';

const WITHDRAW_METHODS = [
	{
		id: 'paypal',
		name: __('Paypal', 'masteriyo'),
	},
	{
		id: 'bank_transfer',
		name: __('Bank Transfer', 'masteriyo'),
	},
	{
		id: 'e_check',
		name: __('E-Check', 'masteriyo'),
	},
];

type Props = {
	data?: WithdrawPreferenceDataMap;
	isOpen: boolean;
	onClose: () => void;
};

const WithdrawMethodForm: React.FC<Props> = (props) => {
	const { data, onClose, isOpen } = props;
	const { register, handleSubmit, watch } =
		useForm<WithdrawPreferenceDataMap>();

	const queryClient = useQueryClient();

	const withdrawMethod = useRef<string>();
	withdrawMethod.current = watch('method', data?.method ?? '');

	const userAPI = new API(urls.currentUser);
	const toast = useToast();

	const updateWithdrawData = useMutation(
		(data: WithdrawPreferenceDataMap) =>
			userAPI.store({
				withdraw_method_preference: data,
			}),
		{
			onSuccess() {
				queryClient.invalidateQueries('userProfile');
				onClose();
				toast({
					title: __('Withdraw method updated successfully', 'masteriyo'),
					status: 'success',
					isClosable: true,
					containerStyle: {
						fontSize: 'sm',
					},
				});
			},
			onError(error: Error) {
				onClose();
				toast({
					status: 'error',
					isClosable: true,
					title: __('Failed to update withdraw method', 'masteriyo'),
					description: error.message,
					containerStyle: {
						fontSize: 'sm',
					},
				});
			},
		}
	);

	const onSubmit = (data: WithdrawPreferenceDataMap) => {
		updateWithdrawData.mutate(data);
	};

	return (
		<Modal isOpen={isOpen} onClose={onClose} isCentered>
			<ModalOverlay />
			<ModalContent>
				<ModalHeader px="10" pt={10}>
					{__('Withdraw Preference', 'masteriyo')}
				</ModalHeader>
				<ModalCloseButton />
				<ModalBody
					px="10"
					pb={!localized.withdraw_methods?.length ? 10 : undefined}
				>
					{!localized.withdraw_methods?.length ? (
						<Text color="gray.500" fontSize="sm" mt="4">
							{__(
								"A withdrawal method hasn't been chosen yet. Kindly reach out to the Site Admin to select your preferred withdrawal option.",
								'masteriyo'
							)}
						</Text>
					) : (
						<form onSubmit={handleSubmit(onSubmit)}>
							<Stack spacing="9" w="100%">
								<Stack direction="column" spacing="4">
									<FormControl>
										<FormLabel>{__('Withdraw Method', 'masteriyo')}</FormLabel>
										<Select
											placeholder={__('Select a withdraw method', 'masteriyo')}
											{...register('method')}
											defaultValue={data?.method}
										>
											{WITHDRAW_METHODS.filter((x) =>
												localized.withdraw_methods?.includes(x.id)
											).map((x) => (
												<option key={x.id} value={x.id}>
													{x.name}
												</option>
											))}
										</Select>
									</FormControl>
									{'e_check' === withdrawMethod.current && (
										<FormControl>
											<FormLabel>
												{__('Physical Address', 'masteriyo')}
											</FormLabel>
											<Input
												{...register('physical_address')}
												defaultValue={data?.physical_address}
											/>
										</FormControl>
									)}
									{'paypal' === withdrawMethod.current && (
										<FormControl>
											<FormLabel>
												{__('Paypal Email Address', 'masteriyo')}
											</FormLabel>
											<Input
												{...register('paypal_email')}
												defaultValue={data?.paypal_email}
											/>
										</FormControl>
									)}
									{'bank_transfer' === withdrawMethod.current && (
										<>
											<FormControl>
												<FormLabel>{__('Bank Name', 'masteriyo')}</FormLabel>
												<Input
													{...register('bank_name')}
													defaultValue={data?.bank_name}
												/>
											</FormControl>
											<FormControl>
												<FormLabel>{__('Account Name', 'masteriyo')}</FormLabel>
												<Input
													{...register('account_name')}
													defaultValue={data?.account_name}
												/>
											</FormControl>
											<FormControl>
												<FormLabel>
													{__('Account Number', 'masteriyo')}
												</FormLabel>
												<Input
													{...register('account_number')}
													defaultValue={data?.account_number}
												/>
											</FormControl>
											<FormControl>
												<FormLabel>
													{__(
														'International Bank Account Number (IBAN)',
														'masteriyo'
													)}
												</FormLabel>
												<Input
													{...register('iban')}
													defaultValue={data?.iban}
												/>
											</FormControl>
											<FormControl>
												<FormLabel>
													{__('BIC / SWIFT Code', 'masteriyo')}
												</FormLabel>
												<Input
													{...register('swift_code')}
													defaultValue={data?.swift_code}
												/>
											</FormControl>
										</>
									)}
								</Stack>
							</Stack>
						</form>
					)}
				</ModalBody>
				{localized.withdraw_methods?.length && (
					<ModalFooter
						display="flex"
						justifyContent="space-between"
						pb="10"
						pt="8"
						px="10"
					>
						<Button
							colorScheme="primary"
							variant="outline"
							mr={3}
							onClick={onClose}
							isDisabled={updateWithdrawData.isLoading}
						>
							{__('Cancel', 'masteriyo')}
						</Button>
						<Button
							colorScheme="primary"
							onClick={handleSubmit(onSubmit)}
							isLoading={updateWithdrawData.isLoading}
						>
							{__('Save', 'masteriyo')}
						</Button>
					</ModalFooter>
				)}
			</ModalContent>
		</Modal>
	);
};

export default WithdrawMethodForm;

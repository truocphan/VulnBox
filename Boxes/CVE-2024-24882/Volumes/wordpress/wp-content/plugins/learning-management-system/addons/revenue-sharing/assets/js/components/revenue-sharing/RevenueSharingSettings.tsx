import {
	Box,
	Collapse,
	FormLabel,
	Icon,
	Input,
	InputGroup,
	InputRightAddon,
	NumberDecrementStepper,
	NumberIncrementStepper,
	NumberInput,
	NumberInputField,
	NumberInputStepper,
	Radio,
	RadioGroup,
	Stack,
	Switch,
	Textarea,
	Tooltip,
} from '@chakra-ui/react';
import { __ } from '@wordpress/i18n';
import React from 'react';
import { Controller, useFormContext, useWatch } from 'react-hook-form';
import { BiInfoCircle } from 'react-icons/bi';
import FormControlTwoCol from '../../../../../../assets/js/back-end/components/common/FormControlTwoCol';
import Select from '../../../../../../assets/js/back-end/components/common/Select';
import { infoIconStyles } from '../../../../../../assets/js/back-end/config/styles';
import { RevenueSharingSettingMap } from '../../../../../../assets/js/back-end/types';

type Props = {
	data?: RevenueSharingSettingMap;
};

const PAYOUT_METHODS = [
	{ label: 'Paypal', value: 'paypal' },
	{ label: 'E-Check', value: 'e_check' },
	{ label: 'Bank Account', value: 'bank_transfer' },
];

const RevenueSharingSettings: React.FC<Props> = (props) => {
	const { register, getValues } = useFormContext();

	const watchRevenueSharingEnable = useWatch({
		name: 'payments.revenue_sharing.enable',
		defaultValue: props.data?.enable ?? false,
	});

	const watchFeeEnable = useWatch({
		name: 'payments.revenue_sharing.deductible_fee.enable',
		defaultValue: props.data?.deductible_fee?.enable ?? false,
	});

	const watchFeeType = useWatch({
		name: 'payments.revenue_sharing.deductible_fee.type',
		defaultValue: props.data?.deductible_fee?.type ?? 'percentage',
	});

	const watchPayoutMethods = useWatch({
		name: 'payments.revenue_sharing.withdraw.methods',
		defaultValue: props.data?.withdraw?.methods ?? [],
	});

	const currency = getValues()?.payments?.currency?.currency ?? 'USD';

	return (
		<Stack direction="column" spacing="6">
			<FormControlTwoCol>
				<FormLabel>{__('Enable', 'masteriyo')}</FormLabel>
				<Switch
					defaultChecked={props.data?.enable}
					{...register('payments.revenue_sharing.enable')}
				/>
			</FormControlTwoCol>
			<Collapse in={watchRevenueSharingEnable}>
				<Stack direction="column" spacing="6">
					<FormControlTwoCol>
						<FormLabel>
							{__('Admin commission rate', 'masteriyo')}
							<Tooltip
								label={__(
									'Percentage retained by the admin/platform from course sales.',
									'masteriyo'
								)}
								hasArrow
								fontSize="xs"
							>
								<Box as="span" sx={infoIconStyles}>
									<Icon as={BiInfoCircle} />
								</Box>
							</Tooltip>
						</FormLabel>
						<Controller
							name={'payments.revenue_sharing.admin_rate'}
							defaultValue={props.data?.admin_rate}
							render={({ field }) => (
								<InputGroup>
									<NumberInput
										min={0}
										max={100}
										defaultValue={field.value}
										onChange={field.onChange}
									>
										<NumberInputField />
										<NumberInputStepper>
											<NumberIncrementStepper />
											<NumberDecrementStepper />
										</NumberInputStepper>
									</NumberInput>
									<InputRightAddon>{'%'}</InputRightAddon>
								</InputGroup>
							)}
						/>
					</FormControlTwoCol>
					<FormControlTwoCol>
						<FormLabel>
							{__('Instructor commission rate', 'masteriyo')}
							<Tooltip
								label={__(
									'Percentage paid to instructors from course sales',
									'masteriyo'
								)}
								hasArrow
								fontSize="xs"
							>
								<Box as="span" sx={infoIconStyles}>
									<Icon as={BiInfoCircle} />
								</Box>
							</Tooltip>
						</FormLabel>
						<Controller
							name={'payments.revenue_sharing.instructor_rate'}
							defaultValue={props.data?.instructor_rate}
							render={({ field }) => (
								<InputGroup>
									<NumberInput
										min={0}
										max={100}
										defaultValue={field.value}
										onChange={field.onChange}
									>
										<NumberInputField />
										<NumberInputStepper>
											<NumberIncrementStepper />
											<NumberDecrementStepper />
										</NumberInputStepper>
									</NumberInput>
									<InputRightAddon>{'%'}</InputRightAddon>
								</InputGroup>
							)}
						/>
					</FormControlTwoCol>
					<FormControlTwoCol>
						<FormLabel>
							{__('Enable Deductible Fee', 'masteriyo')}
							<Tooltip
								label={__(
									'Enabling this deducts a fee from the sale price before commission distribution.',
									'masteriyo'
								)}
								hasArrow
								fontSize="xs"
							>
								<Box as="span" sx={infoIconStyles}>
									<Icon as={BiInfoCircle} />
								</Box>
							</Tooltip>
						</FormLabel>
						<Switch
							defaultChecked={props.data?.deductible_fee.enable}
							{...register('payments.revenue_sharing.deductible_fee.enable')}
						/>
					</FormControlTwoCol>
					<Collapse in={watchFeeEnable}>
						<Stack direction="column" spacing="6">
							<FormControlTwoCol>
								<FormLabel>{__('Deductible Fee Name', 'masteriyo')}</FormLabel>
								<Input
									defaultValue={props.data?.deductible_fee.name}
									{...register('payments.revenue_sharing.deductible_fee.name')}
								/>
							</FormControlTwoCol>
							<FormControlTwoCol>
								<FormLabel>{__('Deductible Fee Type', 'masteriyo')}</FormLabel>
								<Controller
									name="payments.revenue_sharing.deductible_fee.type"
									defaultValue={props.data?.deductible_fee.type}
									render={({ field }) => (
										<RadioGroup
											defaultValue={field.value}
											onChange={field.onChange}
											display="flex"
											gap="2"
										>
											<Radio value="percentage">{__('Percentage')}</Radio>
											<Radio value="fixed">{__('Fixed')}</Radio>
										</RadioGroup>
									)}
								/>
							</FormControlTwoCol>
							<FormControlTwoCol>
								<FormLabel>
									{__('Deductible Fee Amount', 'masteriyo')}
								</FormLabel>
								<Controller
									name={'payments.revenue_sharing.deductible_fee.amount'}
									defaultValue={props.data?.deductible_fee.amount}
									render={({ field }) => (
										<InputGroup>
											<NumberInput
												min={0}
												max={
													'percentage' === (watchFeeType ?? 'percentage')
														? 100
														: undefined
												}
												defaultValue={field.value}
												onChange={field.onChange}
											>
												<NumberInputField />
												<NumberInputStepper>
													<NumberIncrementStepper />
													<NumberDecrementStepper />
												</NumberInputStepper>
											</NumberInput>
											<InputRightAddon>
												{'percentage' === (watchFeeType ?? 'percentage')
													? '%'
													: currency}
											</InputRightAddon>
										</InputGroup>
									)}
								/>
							</FormControlTwoCol>
						</Stack>
					</Collapse>
					<FormControlTwoCol>
						<FormLabel>
							{__('Minimum Payout Amount', 'masteriyo')}
							<Tooltip
								label={__(
									'Defines the minimum earnings required for instructor withdrawals.',
									'masteriyo'
								)}
								hasArrow
								fontSize="xs"
							>
								<Box as="span" sx={infoIconStyles}>
									<Icon as={BiInfoCircle} />
								</Box>
							</Tooltip>
						</FormLabel>
						<Controller
							name={'payments.revenue_sharing.withdraw.min_amount'}
							defaultValue={props.data?.withdraw.min_amount}
							render={({ field }) => (
								<InputGroup>
									<NumberInput
										min={0}
										max={100}
										defaultValue={field.value}
										onChange={field.onChange}
									>
										<NumberInputField />
										<NumberInputStepper>
											<NumberIncrementStepper />
											<NumberDecrementStepper />
										</NumberInputStepper>
									</NumberInput>
									<InputRightAddon>{currency}</InputRightAddon>
								</InputGroup>
							)}
						/>
					</FormControlTwoCol>
					<FormControlTwoCol>
						<FormLabel>
							{__('Maturity Period', 'masteriyo')}
							<Tooltip
								label={__(
									'Specifies the required number of days sales revenue must remain in the account before withdrawal is allowed.',
									'masteriyo'
								)}
								hasArrow
								fontSize="xs"
							>
								<Box as="span" sx={infoIconStyles}>
									<Icon as={BiInfoCircle} />
								</Box>
							</Tooltip>
						</FormLabel>
						<Controller
							name={'payments.revenue_sharing.withdraw.maturity_period'}
							defaultValue={props.data?.withdraw.maturity_period}
							render={({ field }) => (
								<InputGroup>
									<NumberInput
										min={1}
										defaultValue={field.value}
										onChange={field.onChange}
									>
										<NumberInputField />
										<NumberInputStepper>
											<NumberIncrementStepper />
											<NumberDecrementStepper />
										</NumberInputStepper>
									</NumberInput>
									<InputRightAddon>{__('DAY', 'masteriyo')}</InputRightAddon>
								</InputGroup>
							)}
						/>
					</FormControlTwoCol>
					<FormControlTwoCol>
						<FormLabel>
							{__('Payout Methods', 'masteriyo')}
							<Tooltip
								label={__(
									'Select how instructors can request withdrawals.',
									'masteriyo'
								)}
								hasArrow
								fontSize="xs"
							>
								<Box as="span" sx={infoIconStyles}>
									<Icon as={BiInfoCircle} />
								</Box>
							</Tooltip>
						</FormLabel>
						<Controller
							name={'payments.revenue_sharing.withdraw.methods'}
							defaultValue={props.data?.withdraw.methods ?? []}
							render={({ field }) => (
								<Select
									isMulti
									value={PAYOUT_METHODS.filter(
										(x) => field.value?.includes(x.value) ?? false
									)}
									onChange={(x) => {
										field.onChange(x.map((y) => y.value));
									}}
									placeholder={__('Select Payout Methods', 'masteriyo')}
									options={PAYOUT_METHODS}
								/>
							)}
						/>
					</FormControlTwoCol>
					<Collapse in={watchPayoutMethods?.includes('bank_transfer')}>
						<FormControlTwoCol>
							<FormLabel>{__('Bank Instructions', 'masteriyo')}</FormLabel>
							<Textarea
								defaultValue={props.data?.withdraw.bank_instruction}
								{...register(
									'payments.revenue_sharing.withdraw.bank_instructions'
								)}
								resize="vertical"
								rows={5}
							/>
						</FormControlTwoCol>
					</Collapse>
				</Stack>
			</Collapse>
		</Stack>
	);
};

export default RevenueSharingSettings;

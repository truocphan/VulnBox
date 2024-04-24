import { UserSchema } from '../../../../../assets/js/back-end/schemas';

export interface WithdrawPreferenceDataMap {
	method: string;
	physical_address: string;
	account_name: string;
	account_number: string;
	bank_name: string;
	swift_code: string;
	iban: string;
	paypal_email: string;
}

export interface UserRevenueSharingDataMap {
	withdraw_method_preference?: WithdrawPreferenceDataMap;
	withdrawable_amount: number;
	withdrawn_amount: number;
	available_amount: number;
	withdrawable_amount_formatted: string;
	withdrawn_amount_formatted: string;
	available_amount_formatted: string;
	minimum_withdraw_amount: number;
	minimum_withdraw_amount_formatted: string;
}

export interface WithdrawDataMap {
	id: number;
	status: string;
	withdraw_amount: number;
	withdraw_method?: WithdrawPreferenceDataMap;
	withdrawer: UserSchema;
	date_created: string;
	date_modified: string;
	rejection_detail?: {
		reason: string;
		other_reason: string;
	};
}

export interface WithdrawResponseDataMap {
	data: WithdrawDataMap[];
	meta: {
		total: number;
		pages: number;
		current_page: number;
		per_page: number;
	};
}

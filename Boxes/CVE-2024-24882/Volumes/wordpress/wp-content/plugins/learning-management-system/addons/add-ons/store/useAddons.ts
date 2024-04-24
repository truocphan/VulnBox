import { createReduxStore } from '@wordpress/data';
import localized from '../../../assets/js/back-end/utils/global';

interface AddonsState {
	addons: Addons;
}

const initialState: AddonsState = {
	addons: localized.addons,
};

const actions = {
	updateAddons(slug: string, status: boolean) {
		return {
			type: 'UPDATE_ADDONS',
			slug,
			status,
		};
	},
};

export const useAddonsStore = createReduxStore('addOns', {
	reducer(state: AddonsState = initialState, action: any) {
		switch (action.type) {
			case 'UPDATE_ADDONS':
				return {
					addons: state.addons.map((x) =>
						x.slug === action.slug ? { ...x, active: action.status } : x
					),
				};
		}
		return state;
	},
	actions,
	selectors: {
		getAddons(state: AddonsState) {
			return state.addons;
		},
	},
});

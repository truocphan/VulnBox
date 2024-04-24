interface UiPlacementData {
	id: any;
	reward_type: string;
	types?: any[];
	page?: string;
	location?: string;
	title?: string;
	[key: string]: any;
}

interface GamiPressRewardTypes {
	[slug: string]: {
		ID: any;
		singular_name: string;
		plural_name: string;
	};
}

declare module '@wordpress/media-utils';

type Addon = {
	slug: string;
	active: boolean;
	addon_name: string;
	addon_type: string;
	addon_uri: string;
	description: string;
	author: string;
	author_uri: string;
	thumbnail: string;
	requires: string;
	requirement_fulfilled: string;
	plan: 'Starter' | 'Growth' | 'Scale';
	locked: boolean;
};

type Addons = Addon[];

interface AsyncSelectOption {
	value: string | number;
	label: string;
	avatar_url?: string;
}

type PaginatedApiResponse<T = object> = { data: T[]; meta: Meta };

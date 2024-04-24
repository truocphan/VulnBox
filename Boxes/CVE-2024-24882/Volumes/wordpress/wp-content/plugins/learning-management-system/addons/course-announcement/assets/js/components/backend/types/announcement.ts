type AnnouncementTypeCount = {
	publish: number;
	future: number;
	draft: number;
	pending: number;
	private: number;
	trash: number;
	autoDraft: number;
	inherit: number;
	any: number;
};

type AnnouncementMeta = {
	announcement_count: AnnouncementTypeCount;
	total: number;
	pages: number;
	current_page: number;
	per_page: number;
};

export interface AnnouncementSchema {
	id: number;
	title: string;
	slug: string;
	description: string;
	menu_order: number;
	status: string;
	permalink: string;
	course: {
		id: number;
		name: string;
	};
	author: {
		id: number;
		display_name: string;
		avatar_url: string;
	};
	date_created: string; //ISO UTCFormat
	date_modified: string; //ISO UTCFormat
	_links: {
		self: [
			{
				href: string;
			}
		];
		collection: [
			{
				href: string;
			}
		];
	};
}

export interface AnnouncementsSchema {
	data: AnnouncementSchema;
	meta: AnnouncementMeta;
}

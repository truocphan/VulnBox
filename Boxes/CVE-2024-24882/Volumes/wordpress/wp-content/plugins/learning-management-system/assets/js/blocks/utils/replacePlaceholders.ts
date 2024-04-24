export type PlaceholdersType = {
	[tag: string]: string;
};

export function replacePlaceholders(
	str: string,
	placeholders: PlaceholdersType
) {
	Object.entries(placeholders).forEach(([tag, value]) => {
		str = str.replaceAll(`{{${tag}}}`, value);
	});
	return str;
}

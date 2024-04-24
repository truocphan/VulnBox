export const makeUiPlacementRouteSlug = (placement: UiPlacementData) => {
	let titleSlug = '';

	if (placement.title) {
		titleSlug = placement.title.toLowerCase().replaceAll(' ', '-') + '-';
	}
	return titleSlug + placement.id;
};

export const makeUiPlacementAccountPageRoute = (placement: UiPlacementData) => {
	return '/' + makeUiPlacementRouteSlug(placement);
};

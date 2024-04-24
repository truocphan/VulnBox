import React from 'react';
import localized from '../../../../assets/js/interactive/utils/global';
import { PlacementPage } from '../enums/enums';
import PlacementOnLocation from './PlacementOnLocation';

type Props = React.ComponentProps<typeof PlacementOnLocation>;

const PlacementsInLearnPage: React.FC<Props> = (props) => {
	const {
		page = PlacementPage.LEARN_PAGE,
		pointTypes = localized.gamipress?.point_types,
		rankTypes = localized.gamipress?.rank_types,
		achievementTypes = localized.gamipress?.achievement_types,
		uiPlacements = localized.gamipress?.ui_placements,
	} = props;

	return (
		<PlacementOnLocation
			{...props}
			page={page}
			pointTypes={pointTypes}
			rankTypes={rankTypes}
			achievementTypes={achievementTypes}
			uiPlacements={uiPlacements}
		/>
	);
};

export default PlacementsInLearnPage;

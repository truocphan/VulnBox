import React from 'react';
import localized from '../../../../assets/js/account/utils/global';
import { PlacementPage } from '../enums/enums';
import PlacementOnLocation from './PlacementOnLocation';

type Props = React.ComponentProps<typeof PlacementOnLocation>;

const PlacementsInAccountPage: React.FC<Props> = (props) => {
	const {
		page = PlacementPage.ACCOUNT_PAGE,
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

export default PlacementsInAccountPage;

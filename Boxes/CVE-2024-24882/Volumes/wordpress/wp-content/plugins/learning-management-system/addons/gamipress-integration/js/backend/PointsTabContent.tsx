import React from 'react';
import { useFormContext, useWatch } from 'react-hook-form';
import localized from '../../../../assets/js/back-end/utils/global';
import { isArray } from '../../../../assets/js/back-end/utils/utils';
import UiPlacementManagement from './UiPlacementManagement/UiPlacementManagement';

interface Props {
	ui_placements?: UiPlacementData[];
}

const PointsTabContent: React.FC<Props> = (props) => {
	const { ui_placements = [] } = props;
	const { setValue, control } = useFormContext();

	const watchedUIPlacements: UiPlacementData[] = useWatch({
		name: 'gamipress.point_type_ui_placements',
		defaultValue: isArray(ui_placements) ? ui_placements : [],
		control,
	});

	const setUiPlacements = (uiPlacements: UiPlacementData[]) => {
		setValue('gamipress.point_type_ui_placements', uiPlacements);
	};

	return (
		<UiPlacementManagement
			data={watchedUIPlacements}
			onChange={setUiPlacements}
			rewardTypes={localized.gamipress?.point_types}
			rewardType="point"
		/>
	);
};

export default PointsTabContent;

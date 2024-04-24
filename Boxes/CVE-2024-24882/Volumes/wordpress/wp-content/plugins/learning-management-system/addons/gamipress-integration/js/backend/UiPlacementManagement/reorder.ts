import { DropResult } from 'react-beautiful-dnd';

export const reorderPlacements = (
	result: DropResult,
	data: UiPlacementData[]
) => {
	const { source, destination } = result;

	// if dropped outside the droppable area
	if (!destination) {
		return false;
	}

	// moving to same place
	if (
		destination.droppableId === source.droppableId &&
		destination.index === source.index
	) {
		return false;
	}

	const array = [...data];

	array.splice(destination.index, 0, array.splice(source.index, 1)[0]);

	return array;
};

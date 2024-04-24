import { Box, useRadio, UseRadioProps } from '@chakra-ui/react';
import React from 'react';

type Props = UseRadioProps & { children: any };

const LocationRadioItem: React.FC<Props> = (props) => {
	const { getInputProps, getRadioProps } = useRadio(props);

	const input = getInputProps();
	const checkbox = getRadioProps();

	return (
		<Box as="label">
			<input {...input} />
			<Box
				{...checkbox}
				cursor="pointer"
				borderRadius="5px"
				bg="#F2F3FA"
				_checked={{
					bg: '#4584FF',
					color: 'white',
				}}
				_focus={{
					boxShadow: 'outline',
				}}
				p="12px"
			>
				{props.children}
			</Box>
		</Box>
	);
};

export default LocationRadioItem;

import { Icon, Stack, Text } from '@chakra-ui/react';
import React from 'react';
import { BiInfoCircle } from 'react-icons/bi';
import { Td, Tr } from 'react-super-responsive-table';
interface Props {
	message: string;
}

const EmptyInfo: React.FC<Props> = (props) => {
	const { message } = props;
	return (
		// Adjust in table structure of backend course list, orders and categories.
		<Tr>
			<Td>
				<Stack direction="row" spacing="1" align="center">
					<Icon as={BiInfoCircle} color="primary.400" />
					<Text as="span" fontWeight="medium" color="gray.600" fontSize="sm">
						{message}
					</Text>
				</Stack>
			</Td>
			<Td></Td>
			<Td></Td>
			<Td></Td>
			<Td></Td>
			<Td></Td>
			<Td></Td>
			<Td></Td>
		</Tr>
	);
};

export default EmptyInfo;
